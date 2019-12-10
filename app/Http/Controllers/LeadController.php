<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Customer;
use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Http\Resources\LeadsResource;
use App\Http\Resources\ListLeadResource;
use App\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->leads();
        if ($request->query('list')) {
            $name = $request->query('name');
            $query = $query->select('id', 'name', 'email', 'phone_number', 'mobile_number');
            if ($request->query('name')) $query = $query->where('name', 'like', '%' . $name . '%');
            return  ListLeadResource::collection($query->paginate($perPage));
        }

        $search = $request->query('search');
        $interactive = $request->query('interactive');
        $source = $request->query('source');
        $company = $request->query('company');
        $branch = $request->query('branch');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $tags = $request->query('tags');
        $createdAt = $request->query('createdAt');
        $scoreFrom = $request->query('scoreFrom');
        $scoreTo = $request->query('scoreTo');
        $birthday = $request->query('birthday');
        if ($search) $query = $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%');
        });
        if ($source) $query = $query->where('source_id', $source);
        if ($company) $query = $query->where('company', $company);
        if ($branch) $query = $query->where('branch_id', $branch);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);
        if ($birthday) $query = $query->whereBetWeen('birthday', $birthday);
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        if ($scoreFrom) $query = $query->where('score', '>=', $scoreFrom);
        if ($scoreTo) $query = $query->where('score', '<=', $scoreTo);
        if ($tags) $query = $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('name', $tags);
        });
        if ($interactive) {
            if ($interactive == "task") $query = $query->doesntHave('tasks');
            if ($interactive == "note") $query = $query->doesntHave('notes');
        }
        return LeadsResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadRequest $request)
    {
        $data = $request->all();
        $data = array_merge($data, ['company_id' => company()->id, 'name' => $request->first_name . ' ' . $request->last_name]);
        $lead = company()->leads()->create($data);
        return created($lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead, Request $request)
    {
        if ($request->query('edit'))
            return ['data' => $lead];
        else if ($request->query('getName')) return ['data' => ['name' => $lead->name]];
        else return new LeadResource($lead);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(LeadRequest $request, Lead $lead)
    {
        $request = $request->all();
        $request['name'] = $request['first_name'] . ' ' . $request['last_name'];
        $lead->update($request);
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }
    public function convert(Request $request, Lead $lead)
    {
        if ($request->opportunity) {
            $validator = Validator::make($request->data, [
                'ownerable_id' => 'required',
                'name' => 'required',
                'end_date' => 'required',
            ]);
            if (!$validator->passes()) return error('Dữ liệu nhập không hợp lệ');
        }
        if ($lead->email) {
            if (Customer::where('email', $lead->email)->first()) {
                return response(['message' => 'Email đã tồn tại ở một khách hàng khác'], 400);
            }
            if (Contact::where('email', $lead->email)->first()) {
                return response(['message' => 'Email đã tồn tại ở một liên hệ khác'], 400);
            }
        }
        if ($lead->mobile_number) {
            if (Customer::where('mobile_number', $lead->mobile_number)->first()) {
                return response(['message' => 'Số di động đã tồn tại ở một khách hàng khác'], 400);
            }
            if (Contact::where('mobile_number', $lead->mobile_number)->first()) {
                return response(['message' => 'Số di động đã tồn tại ở một liên hệ khác'], 400);
            }
        }
        if ($lead->phone_number) {
            if (Customer::where('phone_number', $lead->phone_number)->first()) {
                return response(['message' => 'Số điện thoại đã tồn tại ở một khách hàng khác'], 400);
            }
            if (Contact::where('phone_number', $lead->phone_number)->first()) {
                return response(['message' => 'Số điện thoại đã tồn tại ở một liên hệ khác'], 400);
            }
        }
        $leadColumns = collect(Schema::getColumnListing('leads'));
        $customerColumns = collect(Schema::getColumnListing('customers'));
        $contactColumns = collect(Schema::getColumnListing('contacts'));
        $customerData = $this->getSameColumn($leadColumns, $customerColumns, $lead);
        $contactData = $this->getSameColumn($leadColumns, $contactColumns, $lead);
        $notes = $lead->notes;
        $files = $lead->files;
        $tasks = $lead->tasks;
        $customerData['code'] = 'KH' . sprintf("%05d",  DB::select("show table status like 'customers'")[0]->Auto_increment);
        try {
            DB::beginTransaction();
            $customer = Customer::create($customerData);
            $contact = $customer->contacts()->create($contactData);
            $notes->each(function ($item) use ($customer, $contact) {
                unset($item['id']);
                $customer->notes()->create($item->toArray());
                $contact->notes()->create($item->toArray());
            });
            $files->each(function ($item) use ($customer, $contact) {
                unset($item['id']);
                $customer->files()->create($item->toArray());
                $contact->files()->create($item->toArray());
            });
            $tasks->each(function ($item) use ($customer, $contact) {
                unset($item['id']);
                $item->contact_id = $contact->id;
                $customer->tasks()->create($item->toArray());
            });
            if ($request->opportunity) {
                $request = $request->data;
                $request['customer_id'] = $customer->id;
                $request['contact_id'] = $contact->id;
                company()->opportunities()->create($request);
            }
            $lead->update(['converted' => true]);
            $lead->tasks()->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return error($th);
        }

        return created($customer);
    }
    private function getSameColumn($arr1, $arr2, $lead)
    {
        $sameColumns = $arr1->intersect($arr2)->filter(function ($item) {
            if ($item == 'id' || $item == 'updated_by' || $item == 'created_by' || $item == 'created_at' || $item == 'updated_at') return false;
            return true;
        })->values()->all();
        $data = [];
        foreach ($sameColumns as $column) {
            $data = array_merge($data, [$column => $lead[$column]]);
        }
        return $data;
    }
}
