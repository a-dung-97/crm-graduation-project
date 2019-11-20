<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactListResource;
use App\Http\Resources\ContactsResource;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $request = $request->all();
        $request['ownerable_id'] = $request['ownerable']['id'];
        $request['ownerable_type'] = $request['ownerable']['type'];
        $request['name'] = $request['first_name'] . ' ' . $request['last_name'];
        unset($request['ownerable']);
        $contact = company()->contacts()->create($request);
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
        $query = company()->leads();
        if ($request->query('list')) {
            $name = $request->query('name');
            $query = $query->select('id', 'name', 'email', 'phone_number');
            if ($request->query('name')) $query = $query->where('name', 'like', '%' . $name . '%');
            return  ContactListResource::collection($query->paginate($perPage));
        }

        $fullName = $request->query('fullName');
        $email = $request->query('email');
        $phoneNumber = $request->query('phoneNumber');
        $mobileNumber = $request->query('mobile_number');
        $customer = $request->query('customer');
        $position = $request->query('position');
        $gender = $request->query('gender');
        $position = $request->query('position');
        $createdAt = $request->query('createdAt');
        $birthday = $request->query('birthday');
        $ownerable = $request->query('ownerable');
        if ($ownerable) $ownerable = json_decode($ownerable);


        if ($fullName) $query = $query->where('name', $fullName);
        if ($email) $query = $query->where('email', $email);
        if ($phoneNumber) $query = $query->where('phone_number', $phoneNumber);
        if ($customer) $query = $query->where('customer', $customer);
        if ($position) $query = $query->where('name', $position);
        if ($birthday) $query = $query->whereBetWeen('birthday', $birthday);
        if ($gender) $query = $query->whereBetWeen('gender', $gender);
        if ($mobileNumber) $query = $query->whereBetWeen('mobile_number', $mobileNumber);
        if ($createdAt) $query = $query->whereBetween('created_at', $createdAt);
        if ($ownerable) $query = $query->where([['ownerable_type', $ownerable->type], ['ownerable_id', $ownerable->id]]);
        return ContactsResource::collection($query->with('ownerable:id,name')->paginate($perPage, 10));
    }
    public function show(Contact $contact)
    {
        return ['data' => $contact];
    }
    public function destroy(Contact $contact)
    { }
}
