<?php

namespace App\Modules\Order\Jobs;

use App\Modules\Order\Mail\SendInvoiceEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $details)
    {
        $this->email = $email;
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = $this->details->receipt;
        Mail::to($this->email)->send(new SendInvoiceEmail($this->details, $file.'.pdf'));
    }
}
