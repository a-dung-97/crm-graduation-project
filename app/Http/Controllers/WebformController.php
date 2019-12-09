<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebFormRequest;
use App\Http\Resources\WebFormsResource;
use App\Webform;
use Illuminate\Http\Request;

class WebformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');
        $query = company()->webforms()->latest();
        if ($search) $query = $query->where('name', 'like', "%{$search}%")->orWhere('campaign', 'like', "%{$search}%");
        return WebFormsResource::collection($query->paginate($perPage));
    }


    public function store(WebFormRequest $request)
    {
        $webform = company()->webforms()->create($request->all());
        return created($webform);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Webform  $webform
     * @return \Illuminate\Http\Response
     */
    public function show(Webform $webform)
    {
        return ['data' => collect($webform)->merge(['owner' => $webform->ownerable->name])];
    }

    public function update(WebFormRequest $request, Webform $webform)
    {
        $webform->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Webform  $webform
     * @return \Illuminate\Http\Response
     */
    public function destroy(Webform $webform)
    {
        delete($webform);
    }
    public function createLead(Request $request, Webform $webform)
    {
        $phoneNumber = $request->phone_number;
        $mobileNumber = $request->mobile_number;
        $email = $request->email;
        $owner = $webform->ownerable;
        $company = $webform->company;
        $query = $company->leads();
        $duplicateEmail = false;
        $duplicateMobileNumber = false;
        $duplicatePhoneNumber = false;
        if ($phoneNumber) $duplicatePhoneNumber = $query->where('phone_number', $phoneNumber)->first();
        if ($mobileNumber) $duplicateMobileNumber = $query->where('mobile_number', $mobileNumber)->first();
        if ($email) $duplicateEmail = $query->where('email', $email)->first();
        if ($duplicateEmail || $duplicateMobileNumber || $duplicatePhoneNumber) return;
        $catalog = $webform->company->catalogs()->whereName('Webform')->first();
        $catalogId = $catalog ? $catalog->id : '';
        $owner->leads()->create(array_merge($request->all(), ['source_id' => $catalogId, 'name' => $request->last_name, 'company_id' => $company->id]));
        return created();
    }
    public function getWebformFromIframe($id)
    {
        $webform = Webform::find($id);
        if (!$webform) return error('Không tồn tại webform');
        return ['data' => collect($webform)->only('name', 'field', 'url')];
    }
}
