<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function test()
    {
        $data = array('name'=>"David");
   
        Mail::send(['text'=>'mails.test'], $data, function($message) {
            $message->to('akalodave@gmail.com', 'David Aneke')->subject
                ('Laravel Basic Testing Mail');
            $message->from('admin@princeak.com','PrinceAK Blog');
        });
        echo "Basic Email Sent. Check your inbox.";
    }
}
