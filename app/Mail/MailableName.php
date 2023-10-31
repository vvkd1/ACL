<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;


class MailableName extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }


    //     public function envelope()
    // {
    //    return new Envelope(
    //        from: new Address('example@example.com', 'Test Sender'),
    //        subject: 'Test Email',
    //    );
    // }

    public function build()
    {
        return $this->subject('Mail from Security Softwere solution')
                    ->view('demoMail')
                    ->attach(public_path('excel_file/GC_Sample.xlsx'));
    }

}
