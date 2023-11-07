<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListInvoceRequest;
use App\Http\Requests\UploadInvoiceRequest;
use App\Models\Invoice;
use App\Models\InvoiceFile;
use App\Models\Organization;
use App\Services\InvoiceFileService;

class InvoiceController extends Controller
{
    private InvoiceFileService $invoiceFileService;

    public function __construct()
    {
        $this->invoiceFileService = new InvoiceFileService;
    }

    public function list(ListInvoceRequest $request)
    {
        $invoiceFileQuery = $this->getInvoiceFilesQuery();

        if ($request->get('getherers')) {
            $organizationIds = Organization::whereIn('getherer_id', $request->get('getherers'))->get()->pluck('id');
            $invoiceFileQuery->where('sent_by_organization_id', $organizationIds);
        }

        $total = $invoiceFileQuery->count();

        $invoiceFile = $this->prepareInvoiceFilesDataResponse($request, $invoiceFileQuery);

        return response()->json([
            'data' => $invoiceFile->all(),
            'top' => $request->get('top', 10),
            'skip' => (int) $request->get('skip', 0),
            'total' => $total,
        ]);
    }

    public function organizationList(ListInvoceRequest $request, Organization $organization)
    {
        $invoiceFileQuery = $this->getInvoiceFilesQuery();

        if ($organization->isManagingOrganization()) {
            $invoiceFileQuery->where('sent_by_organization_id', $organization->id);
        }

        $total = $invoiceFileQuery->count();

        $invoiceFile = $this->prepareInvoiceFilesDataResponse($request, $invoiceFileQuery);

        return response()->json([
            'data' => $invoiceFile->all(),
            'top' => $request->get('top', 10),
            'skip' => (int) $request->get('skip', 0),
            'total' => $total,
        ]);
    }

    public function upload(UploadInvoiceRequest $request)
    {
        $data = $request->validated();

        $invoices = $data['invoices'];
        $organizationId = Organization::where('getherer_id', $request->get('getherer'))->first()->id;

        $log = collect([]);
        foreach ($invoices as $invoice) {
            // Add XML Validation
            $response = $this->invoiceFileService->saveFile($organizationId, auth()->user(), $invoice);
            $log->push($response);
        }

        return response()->json([
            'message' => 'ok',
            'logs' => $log,
        ]);
    }

    public function details(string $invoiceAccessKey)
    {
        $data = Invoice::with([
            'invoiceFiles',
            'invoiceFiles.sentByOrganization',
            'issuer',
            'recipient',
            'invoiceItems',
            'invoiceItems.materialType',
            'invoiceItems.materialType.materialTypeParent',
            'invoice'
        ])->where('access_key', $invoiceAccessKey)->first();

        $gatherers = $data->invoiceFiles->map(function ($invoiceFile) use ($data) {
            $organization = $invoiceFile->sentByOrganization;

            return [
                "guid" => $invoiceFile->id,
                "gatherer_guid" => $organization ? $organization->getherer_id : null,
                "invoice_guid" => $data->id,
                "ecoduty" => null,
                "collidence" => $invoiceFile->isCollidence(),
                "invoice_status" => $data->status,
                'organization' => $organization
            ];
        });

        $data = $data->toArray();
        $data['gatherers'] = $gatherers;

        return response()->json([
            'data' => $data
        ]);
    }

    public function deleteInvoiceFile(string $fileGuid)
    {
        $invoiceFile = InvoiceFile::find($fileGuid);

        // Add reprocess files with collidence

        if ($invoiceFile) {
            $invoiceFile->delete();
        }

        return response()->json([
            'data' => ['message' => 'ok']
        ]);
    }

    private function getInvoiceFilesQuery()
    {
        $invoiceFileQuery = InvoiceFile::query();

        $invoiceFileQuery->with([
            'invoice',
            'invoice.issuer',
            'invoice.recipient',
            'invoice.invoiceItems',
            'invoice.invoiceItems.materialType',
            'invoice.invoiceItems.materialType.materialTypeParent',
        ]);

        $invoiceFileQuery->orderByDesc('created_at');

        return $invoiceFileQuery;
    }

    private function prepareInvoiceFilesDataResponse($request, $invoiceFileQuery) {
        if ($skip = (int) $request->get('skip', 0)) {
            $invoiceFileQuery->offset($skip);
        }

        if ($top = (int) $request->get('top', 10)) {
            $invoiceFileQuery->limit($top);
        }

        return $invoiceFileQuery->get()->map(function ($invoiceFile) {
            $aggregatedMaterialMass = [];

            if ($invoiceFile->invoice) {
                $invoiceFile->invoice->invoiceItems->each(function($invoiceItem) use (&$aggregatedMaterialMass) {
                    if ($materialType = $invoiceItem->materialType) {
                        if ($materialType->materialTypeParent) {
                            $materialType = $materialType->materialTypeParent;
                        }

                        if (!array_key_exists($materialType->name, $aggregatedMaterialMass)) {
                            $aggregatedMaterialMass[$materialType->name] = $invoiceItem->mass_kg;
                            return;
                        }

                        $aggregatedMaterialMass[$materialType->name] += $invoiceItem->mass_kg;
                    }
                });
            }

            return [
                'ecoduty' => null,
                'email_guid' => null,
                'file_location' => null,
                'file_type' => null,
                'filename' => $invoiceFile->file_name,
                'gatherer' => $invoiceFile->sentByOrganization,
                'gatherer_guid' => $invoiceFile->sentByOrganization->getherer_guid,
                'guid' => $invoiceFile->id,
                'handled_with' => null,
                'invoices' => [$invoiceFile->invoice],
                'invoice' => $invoiceFile->invoice,
                'private_uri' => $invoiceFile->file_path,
                'public_uri' => $invoiceFile->file_path,
                'reading_duration_ms' => null,
                'status' => $invoiceFile->status,
                'status_reason' => $invoiceFile->status_reason,
                'tries_counter' => null,
                'aggregated_material_mass' => count($aggregatedMaterialMass) ? $aggregatedMaterialMass : null,
                'collidence' => $invoiceFile->status === $invoiceFile->isCollidence(),
            ];
        });
    }
}
