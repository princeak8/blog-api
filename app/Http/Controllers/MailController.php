<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function test()
    {
        $data = array('name'=>"David");
   
        Mail::mailer('nnedi')->send('mails.test', $data, function($message) {
            $message->to('akalodave@gmail.com', 'David Aneke')->subject
                ('Laravel HTML Testing Mail');
            $message->from('noreply@premium69.web-hosting.com','Success Nnene Eric');
        });
        echo "Basic Email Sent. Check your inbox.";
    }

    public function test_mail_view()
    {
        $name = "David";
        return view('mails.test', compact('name'));
    }
}
