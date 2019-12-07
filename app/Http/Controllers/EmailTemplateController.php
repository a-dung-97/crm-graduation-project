<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Http\Requests\EmailTemplateRequest;
use App\Http\Resources\EmailTemplateListResource;
use App\Http\Resources\EmailTemplatesResource;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $request->query('list');
        $name = $request->query('name');
        $perPage = $request->query('perPage', 10);
        $query = company()->emailTemplates();
        $search = $request->query('search');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        if ($name) $query = $query->where('name', 'like', '%' . $search . '%');
        if ($list) return EmailTemplateListResource::collection($query->select('id', 'name', 'content', 'created_at')->paginate($perPage));
        return EmailTemplatesResource::collection($query->with('user:id,name')->paginate($perPage));
    }
    public function store(EmailTemplateRequest $request)
    {
        $request = $request->all();
        $request['user_id'] = user()->id;
        company()->emailTemplates()->create($request);
        return created();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(EmailTemplate $emailTemplate)
    {
        return ['data' => $emailTemplate];
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        delete($emailTemplate);
    }
}
