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
            'body' => 'This is for testing email using smtp.'
        ];
         
        Mail::to('vivekdubey5500@gmail.com')->send(new MailableName($mailData));
           
     
        echo "<script> alert('Email is sent successfully.') </script>";


    
    }
}
