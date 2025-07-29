<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\VerifyMail;

class MailController extends Controller
{
    public function index()
    {
        Mail::to('itxabdullah7x@gmail.com')->send(new VerifyMail([
            'title' => 'The Title',
            'body' => 'The Body',
        ]));
    }
}
