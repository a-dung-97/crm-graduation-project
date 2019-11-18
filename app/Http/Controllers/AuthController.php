<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\VerifyEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
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
        $this->middleware('auth:api', ['except' => ['login', 'register', 'verifyEmail', 'resendVerifyEmail']]);
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
                return view('auth.verify_email');
            } else abort(404);
        } else abort(404);
    }
    public function resendVerifyEmail(User $user)
    {
        $newEmailToken = Str::random(60);
        $user->update(['email_token' => $newEmailToken]);
        Mail::to($user)->send(new VerifyEmail($newEmailToken));
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
            'roles' => $user->role_id ?  [$user->role->code] : [],
            'company' => $user->company_id ? $user->company->name : null,
            'department' => $user->department_id ? $user->department->name : null,
            'position' => $user->position_id ? $user->position->name : null,
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
        auth()->user()->update(['company_id' => $company->id, 'position_id' => $firstPosition->id, 'role_id' => $firstRole->id, 'department_id' => $firstDepartment->id]);
        return response('created', Response::HTTP_CREATED);
    }
}
