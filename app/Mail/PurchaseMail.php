<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseMail extends Mailable
{
    use Queueable, SerializesModels;
    public $total_cost;
    public $tax;
    public $shipping;
    public $product_total_cost;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($total_cost,$tax,$shipping,$product_total_cost)
    {
        $this->total_cost = $total_cost;
        $this->tax = $tax;
        $this->shipping = $shipping;
        $this->product_total_cost = $product_total_cost;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from:  new Address('saiwinoo@saiwinoo.com', 'E-commerec Project') ,
            subject: 'Product Purchase',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.Purchase',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
