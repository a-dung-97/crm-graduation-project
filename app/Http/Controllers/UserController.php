<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\InvitationRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Invitation;
use App\Mail\InvitationEmail;
use App\Scopes\CompanyScope;
use App\User;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }
    public function index(Request $request)
    {
        $perPage = $request->query('perPage');
        $search = $request->query('search');
        $query =  User::latest();
        if ($request->query('list')) {
            $name = $request->query('name');
            $query = $query->select('id', 'name', 'email');
            if ($request->query('name')) $query = $query->where('name', 'like', '%' . $name . '%');
            return  UserResource::collection($query->paginate($perPage));
        }
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('phone_number', 'like', '%' . $search . '%');

        if ($perPage) {
            $query = $query->paginate($perPage);
            return UserResource::collection($query);
        }
        return ['data' => $query->select('id', 'name', 'email')->get()];
    }
    public function update(Request $request, User $user)
    {
        if (isset($request->active)) {
            if ($request->active != $this->user->active && $user->id == $this->user->id) return response(['message' => 'Không thể hủy kích hoạt chủ tài khoản'], 400);
        }

        $user->update($request->all());
        return response(['message' => 'Cập nhật thành công'], Response::HTTP_ACCEPTED);
    }
    public function inviteUser(InvitationRequest $request)
    {
        if (User::withoutGlobalScope(CompanyScope::class)->where('email', $request->email)->whereNotNull('company_id')->where('active', true)->first())
            return response(['message' => 'Email này đã tồn tại ở công ty khác'], 400);
        $data = $request->all();
        $data['invite_code'] = Str::random(60);
        $data['expired_at'] = Carbon::now()->addDay()->toDateTimeString();
        $this->user->invitations()->create($data);
        Mail::to($request->email)->queue(new InvitationEmail($this->user->name,  $data['invite_code']));
        return ['message' => 'Gửi lời mời thành công'];
    }
    public function comfirmInvitationEmail($inviteCode)
    {
        $invitation = Invitation::where([
            ['invite_code', $inviteCode],
            ['expired_at', '>=', Carbon::now()->toDateTimeString()],
            ['is_accepted', false]
        ])->first();
        if ($invitation) {
            return view('auth.accept_invitation', ['email' => $invitation->email]);
        } else
            return "<h1>Lời mời không tồn tại hoặc đã hết hiệu lực</h1>";
    }
    public function acceptInvitation(Request $request, $inviteCode)
    {

        $invitation = Invitation::where('invite_code', $inviteCode)->where('is_accepted', false)->first();
        if ($invitation) {
            $invitation->update(['is_accepted' => true]);
            User::create([
                'name' => $request->name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'position_id' => $invitation->position_id,
                'department_id' => $invitation->department_id,
                'role_id' => $invitation->role_id,
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'company_id' => User::find($invitation->user_id)->company_id
            ]);
            return "<h1>Đăng ký tài khoản thành công!</h1>";
        }
        abort(404);
    }
    public function changeAvatar(Request $request)
    {
        $oldAvatar = $this->user->avatar;
        if ($oldAvatar) {
            Storage::delete('avatar/', $oldAvatar);
        }
        if ($request->avatar) {
            $image = $request->avatar;
            $name = time() . uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];

            Storage::put('avatars/' . $name, \Image::make($image)->stream());
            $this->user->update(['avatar' => $name]);
            return ['message' => 'Cập nhật thành công', 'data' => ['avatar' => Storage::url('avatars/' . $name)]];
        } else return response(['message' => 'Bạn chưa tải ảnh lên'], 400);
    }
    public function getCompany()
    {
        return ['data' => $this->user->company];
    }
    public function updateCompany(CompanyRequest $request)
    {
        $this->user->company()->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }
    public function destroy(User $user)
    {
        if ($user->id == $this->user->id) return response(['message' => 'Bạn không thể xóa chính mình'], 400);
        try {
            $user->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response(['message' => 'Bạn không thể xóa người dùng này'], 400);
        }
    }
    public function getNotifications(Request $request)
    {
        return NotificationResource::collection(user()->notifications()->latest()->offset($request->query('offset'))->limit(6)->get())->additional([
            'unread' => user()->unreadNotifications()->count()
        ]);
    }
    public function markAsRead()
    {
        user()->unreadNotifications->markAsRead();
        return updated();
    }
}
