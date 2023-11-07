<?php

namespace App\Services;

use App\Events\InvoiceFileCreated;
use App\Models\InvoiceFile;
use App\Models\User;
use DOMDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use XSLTProcessor;

class InvoiceFileService
{

    public function saveFile(string $organizationId, User $user, UploadedFile $file)
    {
        $invoiceFile = InvoiceFile::create([
            'file_path' => $file,
            'content' => $file->getContent(),
            'sent_by_user_id' => $user->id,
            'sent_by_organization_id' => $organizationId,
        ]);

        event(new InvoiceFileCreated($invoiceFile));

        return $invoiceFile;
    }

    public function transformInvoiceFileContentToArray(InvoiceFile $invoiceFile)
    {
        $filePath = $invoiceFile->file_path;

        if (env('APP_ENV') === 'local') {
            $filePath = "../storage/app/" . $invoiceFile->getTable() . "/" . $filePath;
        }

        $xmlDoc = new DOMDocument();
        $xmlDoc->load($filePath);

        $xslDoc = new DOMDocument();
        $xslDoc->load(resource_path('xsl/nfe-collector.xsl'));

        try {
            $proc = new XSLTProcessor();
            $proc->importStylesheet($xslDoc);

            $textContent = $proc->transformToDoc($xmlDoc)->textContent;

            return json_decode($textContent, true);
        } catch (\Throwable $th) {
            Log::error($th);
            throw $th;
        }
    }

}
