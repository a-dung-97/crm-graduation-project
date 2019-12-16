<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactListResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\ContactsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $data = $request->all();
        $data = array_merge($data, ['company_id' => company()->id, 'name' => $request->first_name . ' ' . $request->last_name]);
        $contact = company()->contacts()->create($data);
        return created($contact);
    }
    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($request->all());
        return updated();
    }
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->contacts();
        if ($request->query('list')) {
            $name = $request->query('name');
            $id = $request->query('customer');
            if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
            if ($id) $query = $query->where('customer_id', $id);
            $query = $query->select('id', 'name', 'email', 'phone_number');
            return  ContactListResource::collection($query->paginate($perPage));
        }

        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $name = $request->query('name');
        $email = $request->query('email');
        $phoneNumber = $request->query('phoneNumber');
        $mobileNumber = $request->query('mobileNumber');
        $position = $request->query('position');
        $gender = $request->query('gender');
        $position = $request->query('position');
        $createdAt = $request->query('createdAt');
        $ownerable = $request->query('ownerable');
        if ($ownerable) $ownerable = json_decode($ownerable);


        if ($name) $query = $query->where('name', $name);
        if ($email) $query = $query->where('email', $email);
        if ($phoneNumber) $query = $query->where('phone_number', $phoneNumber);
        if ($mobileNumber) $query = $query->where('mobile_number', $mobileNumber);
        if ($position) $query = $query->where('position_id', $position);
        if ($gender != null) $query = $query->where('gender', $gender);
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);
        return ContactsResource::collection($query->with('ownerable:id,name', 'customer:id,name', 'position:id,name')->paginate($perPage));
    }
    public function show(Contact $contact, Request $request)
    {
        if ($request->query('edit'))
            return ['data' => collect($contact)->merge(['customer' => $contact->customer->name])];
        else return new ContactResource($contact);
    }
    public function destroy(Contact $contact)
    { }
}
