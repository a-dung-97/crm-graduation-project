<?php

namespace App\Http\Controllers;

use App\Invitation;
use App\Mail\InvitationEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $query =  User::latest();

        if ($search) $query = $query->where('full_name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone_number', 'like', '%' . $search . '%');;
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return UserResource::collection($query);
    }
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response(['message' => 'Cập nhật thành công'], Response::HTTP_ACCEPTED);
    }
    public function inviteUser(Invitation $request)
    {
        $user = auth()->user();
        if (User::where(['email', $request->email])->whereNotNull('company_id'))
            return response(['message' => 'Email này đã tồn tại ở công ty khác'], 400);
        $request = $request->all();
        $request['invite_code'] = Str::random(60);
        $request['expired_at'] = Carbon::now()->addDay()->toDateTimeString();
        $user->invitations()->create($request);
        Mail::to($request->email)->send(new InvitationEmail($user->name,  $request['invite_code']));
        return ['message' => 'Gửi lời mời thành công'];
    }
    public function comfirmInvitationEmail($inviteCode)
    {
        $invitation = Invitation::where([
            ['invite_code', $inviteCode],
            ['expired_at', '<=', Carbon::now()->toDateTimeString()],
            ['is_accepted', false]
        ])->first();
        if ($invitation) {
            return view('auth.accept_invitation');
        } else
            return "<h1>Lời mời không tồn tại hoặc đã hết hiệu lực</h1>";
    }
    public function acceptInvitation(Request $request, $inviteCode)
    {
        $invitation = Invitation::where('invite_code', $inviteCode)->where('is_accepted', false)->first();
        if ($invitation) {
            $invitation->update(['is_accepted' => true]);
            User::create([
                'full_name' => $request->full_name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'position_id' => $invitation->position_id,
                'department_id' => $invitation->department_id,
                'role_id' => $invitation->role_id,
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'company_id' => User::find($invitation->user_id)->company_id
            ]);
            return "<h1>Đăng kí tài khoản thành công!</h1>";
        }
        abort(404);
    }
}
