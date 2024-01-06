<?php

namespace App\Modules\Order\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $detail;
    private $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail, $file)
    {
        $this->detail = $detail;
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function build()
    {

        return $this->subject('JAP - invoice')->view('email.invoice')->with([
            'order' => $this->detail
        ])->attachData($this->createPDF(), 'invoice.pdf');
    }

    /**
     * @return mixed
     */
    public function createPDF()
    {
        $data = [
            'order' => $this->detail,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data)->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->output();
    }

}
