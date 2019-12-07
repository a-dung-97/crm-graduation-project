<?php

namespace App\Http\Controllers;

use App\EmailAddress;
use App\Http\Requests\EmailAddressRequest;
use App\Http\Resources\EmailAddressesResource;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailAddressController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('list')) return ['data' => user()->emailAddresses()->where('verified', 2)->latest('primary')->select('id', 'email')->get()];
        return EmailAddressesResource::collection(user()->emailAddresses()->latest('primary')->get());
    }
    public function store(EmailAddressRequest $request)
    {
        user()->emailAddresses()->create(['email' => $request->email]);
        return created();
    }
    public function destroy(EmailAddress $emailAddress)
    {
        delete($emailAddress);
    }
    public function setPrimary(EmailAddress $emailAddress)
    {
        user()->emailAddresses()->update(['primary' => false]);
        $emailAddress->update(['primary' => true]);
        return updated();
    }
    public function verify($token)
    {
        $emailAddress = EmailAddress::where('token', $token)->first();
        if ($emailAddress) {
            if ($emailAddress->verified != 2) {
                $emailAddress->update(['verified' => 2]);
                return "<h1>Xác nhận email thành công!</h1>";
            } else abort(404);
        } else abort(404);
    }
    public function sendConfirmEmail(EmailAddress $emailAddress)
    {
        $token = Str::random(60);
        $emailAddress->update(['token' => $token, 'verified' => 1]);
        Mail::to(
            $emailAddress->email,
            user()->name
        )->queue(new VerifyEmail($token, true));
        return updated();
    }
}
