<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\ContactsResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomersResource;
use App\Http\Resources\OpportunitiesResource;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\QuotesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function store(CustomerRequest $request)
    {
        $customer = company()->customers()->create($request->all());
        return created($customer);
    }
    public function update(CustomerRequest $request, Customer $customer)
    {
        $result = collect([]);
        $customer->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            $this->getAllChildren($item, $result);
        });
        if ($result->contains($request->parent_id)) return response(['message' => "Không thể chọn vì khách hàng là cấp dưới"], 400);
        if ($request->parent_id == $customer->id) return response(['message' => 'Không thể chọn khách hàng hiện tại là khách hàng cha'], 400);
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
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        if ($tags) $query = $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('name', $tags);
        });
        return CustomersResource::collection($query->with('ownerable:id,name')->paginate($perPage));
    }
    public function show(Request $request, Customer $customer)
    {
        if ($request->query('edit'))
            return ['data' => collect($customer)->merge(['parent' => $customer->parent ? $customer->parent->name : null])];
        else return new CustomerResource($customer);
    }
    public function destroy(Customer $customer)
    { }
    public function getAllChildren($department, &$result)
    {

        $department->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            if ($item->childrenRecursive->count() > 0) $this->getAllChildren($item, $result);
        });
    }

    public function getChildrenRecursive()
    {
        return ['data' => Department::whereNull('parent_id')->with('childrenRecursive')->get()];
    }
    public function getRelatedInfo(Customer $customer, $type, Request $request)
    {
        switch ($type) {
            case 'opportunity':
                return OpportunitiesResource::collection($customer->opportunities()->paginate($request->query('perPage', 10)));
                break;
            case 'contact':
                return ContactsResource::collection($customer->contacts()->paginate($request->query('perPage', 10)));
                break;
            case 'quote':
                return QuotesResource::collection($customer->quotes()->paginate($request->query('perPage', 10)));
                break;
            case 'contact':
                return QuotesResource::collection($customer->contacts()->paginate($request->query('perPage', 10)));
                break;
            default:
                return OrdersResource::collection($customer->orders()->paginate($request->query('perPage', 10)));
                break;
        }
    }
}
