<?php

namespace App\Http\Controllers;
use Mail;
use Illuminate\Mail\Mailable;
use App\Mail\MailableName;

use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function index()
    {
        
        $mailData = [
            'title' => 'Mail from ItSolutionStuff.com',
            'body' => 'This is testing email body.'
        ];
         
        Mail::to('dev4@scube.net.in')->send(new MailableName($mailData));
           
        return redirect()->back()->with('success', 'Email has been sent successfully!');
    }
}
