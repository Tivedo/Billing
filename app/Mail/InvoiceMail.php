<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nomor_invoice;
    public $nama_customer;
    public $url_invoice;
    public $tanggal_invoice;

    public function __construct($nomor_invoice, $nama_customer, $tanggal_invoice, $url_invoice)
    {
        $this->nomor_invoice = $nomor_invoice;
        $this->nama_customer = $nama_customer;
        $this->tanggal_invoice = $tanggal_invoice;
        $this->url_invoice = $url_invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Recurring'. ' - ' . $this->nomor_invoice,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-mail',
            with: [
                'no_invoice' => $this->nomor_invoice,
                'nama_customer' => $this->nama_customer,
                'tanggal_invoice' => $this->tanggal_invoice,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->url_invoice)
                ->as('invoice_' . $this->nomor_invoice . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
