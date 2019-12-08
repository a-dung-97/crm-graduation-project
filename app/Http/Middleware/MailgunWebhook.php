<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

/**
 * Validate Mailgun Webhooks
 * @see https://documentation.mailgun.com/user_manual.html#securing-webhooks
 */
class MailgunWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->isMethod('post')) {
            abort(Response::HTTP_FORBIDDEN, 'Only POST requests are allowed.');
        }

        if ($this->verify($request)) {
            return $next($request);
        }

        return error('Bạn không có quyền vào đây');
    }
    public function verify($request)
    {
        $token = $request->input('signature.token');
        $timestamp = $request->input('signature.timestamp');
        $signature = $request->input('signature.signature');

        // if (abs(time() - $timestamp) > 15) {
        //     return false;
        // }

        return hash_hmac('sha256', $timestamp . $token, config('services.mailgun.secret')) === $signature;
    }
}
