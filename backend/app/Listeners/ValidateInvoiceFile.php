<?php

namespace App\Listeners;

use App\Enums\InvoiceFileStatus;
use App\Enums\InvoiceFileStatusReason;
use App\Events\InvoiceCreated;
use App\Events\InvoiceFileCreated;
use App\Models\Invoice;
use App\Models\InvoiceFile;
use App\Services\InvoiceFileService;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Support\Facades\Log;

class ValidateInvoiceFile
{
    private InvoiceFileService $invoiceFileService;
    private InvoiceService $invoiceService;

    public function __construct()
    {
        $this->invoiceFileService = new InvoiceFileService;
        $this->invoiceService = new InvoiceService;
    }

    /**
     * @throws Exception
     */
    public function handle(InvoiceFileCreated $event)
    {
        $invoiceFile = $event->invoiceFile;
        $invoiceFileDuplicated = InvoiceFile::where([
            ['content', "=", $invoiceFile->content],
            ['sent_by_organization_id', "=", $invoiceFile->sent_by_organization_id],
            ['id', '!=', $invoiceFile->id],
            ['status', '!=', InvoiceFileStatus::DUPLICATED],
        ])->first();

        if ($invoiceFileDuplicated) {
            $invoiceFile->makeDuplicated();
            $invoiceFile->invoice_id = $invoiceFileDuplicated->invoice_id;
            $invoiceFile->save();
            return;
        }

        try {
            \DB::beginTransaction();

            $invoiceDraft = $this->invoiceFileService->transformInvoiceFileContentToArray($invoiceFile);

            $nfe = $invoiceDraft['doc']['nfe'];

            if (is_null($invoice = Invoice::where('access_key', $nfe['access_key'])->first())) {
                $invoice = $this->invoiceService->createInvoice($nfe);
                event(new InvoiceCreated($invoice));
            } else {
                // TODO: Check collidence
            }

            $invoiceFile->invoice_id = $invoice->id;
            $invoiceFile->makeValid();
            $invoiceFile->save();

            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();

            $invoiceFile->makeInvalid(InvoiceFileStatusReason::FILE_CONTENT_IS_NOT_NFE);
            $invoiceFile->save();

            Log::error($ex);
            throw $ex;
        }
    }
}
