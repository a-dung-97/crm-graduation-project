<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\PasswordReset;
use App\Mail\VerifyEmail;
use App\Menu;
use App\Scopes\CompanyScope;
use App\Services\TinyDrive;
use App\User;
use Carbon\Carbon;
use CatalogSeeder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyEmail', 'resendVerifyEmail', 'sendEmailResetPassword', 'resetPassword']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $info = $request->all();
        $info['password'] = Hash::make($info['password']);
        $info['email_token'] = Str::random(60);
        $user = User::create($info);

        if ($user) {
            Mail::to($user)->queue(new VerifyEmail($user->email_token));
        }
        return response(['data' => ['user_id' => $user->id]], Response::HTTP_CREATED);
    }

    public function verifyEmail($email_token)
    {
        $user = User::where('email_token', $email_token)->first();
        if ($user) {
            if (!$user->email_verified_at) {
                $user->update(['email_verified_at' => Carbon::now()]);
                $user->emailAddresses()->create([
                    'email' => $user->email,
                    'verified' => 2
                ]);
                return view('auth.verify_email');
            } else abort(404);
        } else abort(404);
    }
    public function resendVerifyEmail(User $user)
    {
        $newEmailToken = Str::random(60);
        $user->update(['email_token' => $newEmailToken]);
        Mail::to($user)->queue(new VerifyEmail($newEmailToken));
        return response(['message' => 'Gửi lại email kích hoạt thành công'], Response::HTTP_OK);
    }
    public function login(LoginRequest $request)
    {

        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Sai tài khoản hoặc mật khẩu'], 401);
        }
        if (!auth()->user()->active) return response(['message' => 'Tài khoản của bạn đang bị khóa'], 401);
        if (!auth()->user()->email_verified_at)
            return response(['message' => 'Bạn chưa xác nhận email', 'data' => ['user_id' => auth()->user()->id]], Response::HTTP_NOT_ACCEPTABLE);
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        return response(['data' => [
            "id" => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ?  Storage::url('avatars/' . $user->avatar) : null,
            'phone_number' => $user->phone_number,
            'roles' => $user->role_id ?  [$user->role->id] : [],
            'company' => $user->company_id ? $user->company->name : null,
            'department' => $user->department_id ? $user->department->name : null,
            'position' => $user->position_id ? $user->position->name : null,
            'tiny_drive_token' => TinyDrive::generateToken($user)
        ]]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->tokenById(auth()->user()->id));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }
    public function setup(CompanyRequest $request)
    {
        $company = Company::create($request->all());
        $firstDepartment = $company->departments()->create(['name' => 'Công ty', 'description' => 'Công ty']);
        $firstPosition = $company->positions()->create(['name' => 'CEO']);
        $firstRole = $company->roles()->create(['name' => 'Full', 'code' => 'full']);
        $firstRole->menus()->attach(Menu::select('id')->get()->pluck('id')->all());
        CatalogSeeder::run($company->id);
        auth()->user()->update(['company_id' => $company->id, 'position_id' => $firstPosition->id, 'role_id' => $firstRole->id, 'department_id' => $firstDepartment->id]);
        return response('created', Response::HTTP_CREATED);
    }
    public function sendEmailResetPassword(Request $request)
    {
        $email = $request->query('email');
        if (!$email) return error('Bạn chưa nhập email');
        $user = User::withoutGlobalScope(CompanyScope::class)->where('email', $email)->first();
        if (!$user) return error('Không tồn tại email này');
        $code = mt_rand(100000, 999999);
        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'confirm_code' => $code,
            'expired_at' => Carbon::now()->addMinutes(5),
        ]);
        Mail::to($user)->queue(new PasswordReset($code));
        return ['message' => 'OK'];
    }
    public function resetPassword(PasswordResetRequest $request)
    {
        $reset = DB::table('password_resets')->where('confirm_code', $request->confirm_code)->whereTime('expired_at', '<', Carbon::now()->toDateTimeString())->first();
        if (!$reset) return error('Mã xác nhận không đúng hoặc đã hết hạn');
        User::find($reset->user_id)->update(['password' => Hash::make($request->password)]);
        return ['message' => 'OK'];
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        if (!Hash::check($request->old_password, user()->password))
            return error('Mật khẩu cũ không đúng');
        user()->update(['password' => Hash::make($request->password)]);
        return ['message' => 'OK'];
    }
}
