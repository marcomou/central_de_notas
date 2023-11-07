<?php

namespace App\Listeners;

use App\Enums\InvoiceItemStatusReason;
use App\Enums\InvoiceStatusReason;
use App\Events\InvoiceCreated;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\MaterialInference;
use App\Models\MaterialType;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ValidateInvoice
{

    private InvoiceService $invoiceService;

    private Collection $invoiceItemsValid;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService;
        $this->invoiceItemsValid = collect([]);
    }

    /**
     * @throws Exception
     */
    public function handle(InvoiceCreated $event)
    {
        $invoice = $event->invoice;

        try {
            \DB::beginTransaction();

            $this->validateInvoiceItems($invoice);

            if ($this->invoiceItemsValid->isEmpty()) {
                $invoice->makeInvalid(InvoiceStatusReason::NONE_INVOICE_ITEMS_VALID);
            } elseif (!$invoice->isOutput()) {
                $invoice->makeReject(InvoiceStatusReason::NOT_ACCEPTED_INVOICE_OUTPUT);
            } else {
                $invoice->makeValid();
            }

            $invoice->save();

            \DB::commit();
        } catch (Exception $ex) {
            \DB::rollBack();

            Log::error($ex);
            throw $ex;
        }
    }

    private function validateInvoiceItems(Invoice $invoice)
    {
        $this->invoiceItemsValid = $invoice->invoiceItems->filter(function(InvoiceItem $invoiceItem) {
            if ($this->validateUnit($invoiceItem) && $this->validateMaterial($invoiceItem)) {
                $invoiceItem->makeValid();
            }

            $invoiceItem->save();
            
            return $invoiceItem->isValid();
        });
    }

    private function validateMaterial($invoiceItem)
    {
        $ncmMaterialType = MaterialType::findMaterialByNcm($invoiceItem->ncm);

        if (is_null($ncmMaterialType)) {
            $invoiceItem->makeInvalid(InvoiceItemStatusReason::NCM_IS_INVALID);
            return false;
        }

        $materialInfer = MaterialInference::with(['materialType'])
            ->byDescription($invoiceItem->product_description)->first();

        if (
            empty($materialInfer) || (
                empty($materialInfer->materialType->parent_material_id)
                && $materialInfer->material_type_id !== $ncmMaterialType->id
            ) || (
                !empty($materialInfer->materialType->parent_material_id)
                && $materialInfer->materialType->parent_material_id !== $ncmMaterialType->id
            )
        ) {
            $invoiceItem->makeInvalid(InvoiceItemStatusReason::NCM_IS_INVALID);
            return false;
        }

        $invoiceItem->material_inference_id = $materialInfer->id;
        $invoiceItem->material_type_id = $materialInfer->materialType->id;
        $invoiceItem->infered_is_packaging_source = $materialInfer->is_packaging_source;

        return true;
    }

    private function validateUnit($invoiceItem)
    {
        if (!$invoiceItem->isKgOrTon()) {
            $invoiceItem->makeInvalid(InvoiceItemStatusReason::UNIT_IS_INVALID);
            return false;
        }

        $invoiceItem->mass_kg = $invoiceItem->comercial_quantity * $invoiceItem->comercial_unit_value;

        if ($invoiceItem->isTon()) {
            $invoiceItem->mass_kg /= 100;
        }

        return true;
    }

}
