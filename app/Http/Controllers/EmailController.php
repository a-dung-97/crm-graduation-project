<?php

namespace App\Http\Controllers;

use App\Email;
use App\Http\Requests\MailRequest;
use App\Http\Resources\EmailsResource;
use App\Jobs\SendEmailToCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $type = $request->query('type');
        $id = $request->query('id');
        $model = getModel($type, $id);
        if ($type && $id)
            return EmailsResource::collection($model->emails()->with('mailable:id,name')->paginate($perPage))->additional([
                'recipient' => [
                    'name' => $model->name,
                    'email' => $model->email
                ]
            ]);
    }
    public function store(MailRequest $request)
    {
        $data = Arr::except($request->all(), ['type', 'id']);
        $email = Email::create($data);
        $model = getModel($request->type, $request->id);
        $model->emails()->attach($email->id);
        $data = array_merge($data, ['email' => $model->email, 'name' => $model->name]);
        SendEmailToCustomer::dispatch($data, $email);
        return created();
    }
}
