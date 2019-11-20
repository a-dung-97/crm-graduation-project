<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomersResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function store(CustomerRequest $request)
    {
        $customer = company()->customers()->create($request->all());
        return created($customer);
    }
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());
        return updated();
    }
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->customers();
        if ($request->query('list')) {
            $name = $request->query('name');
            $query = $query->select('id', 'name', 'email', 'phone_number', 'mobile_number');
            if ($request->query('name')) $query = $query->where('name', 'like', '%' . $name . '%');
            return  CustomerListResource::collection($query->paginate($perPage));
        }

        $search = $request->query('search');
        $source = $request->query('source');
        $branch = $request->query('branch');
        $tags = $request->query('tags');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');

        $createdAt = $request->query('createdAt');
        $birthday = $request->query('birthday');
        if ($search) $query = $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%')
                ->orWhere('mobile_number', 'like', '%' . $search . '%');
        });
        if ($source) $query = $query->where('source_id', $source);

        if ($branch) $query = $query->where('branch_id', $branch);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);
        if ($birthday) $query = $query->whereBetWeen('birthday', $birthday);
        if ($createdAt) $query = $query->whereBetween('created_at', $createdAt);
        if ($tags) $query = $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('name', $tags);
        });
        return CustomersResource::collection($query->with('ownerable:id,name')->paginate($perPage));
    }
    public function show(Request $request, Customer $customer)
    {
        if ($request->query('edit'))
            return ['data' => collect($customer)->merge(['parent' => $customer->parent->name])];
        else return new CustomerResource($customer);
    }
    public function destroy(Customer $customer)
    { }
}
