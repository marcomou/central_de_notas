<?php

namespace App\Events;

use App\Models\InvoiceFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceFileCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public InvoiceFile $invoiceFile;

    public function __construct(InvoiceFile $invoiceFile)
    {
        $this->invoiceFile = $invoiceFile;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
