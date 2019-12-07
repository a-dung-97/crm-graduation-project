<?php

namespace App\Http\Controllers;

use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function tracking(Request $request)
    {
        $response = Mailgun::api()->put("lists/iutyuzizae1@crm.adung.software", [
            'name'         => 'xyz',
            'description'  => 'abc',
        ]);
        return $response;
    }
}
