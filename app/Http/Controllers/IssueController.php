<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Http\Resources\IssueResource;
use App\Http\Resources\ReceiptAndIssueWithProductResource;
use App\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $query =  company()->issues()->latest('date');
        if ($search) $query = $query->where('code', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return IssueResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssueRequest $request)
    {
        // return collect($request->products)->keyBy('product_id');
        $issue = company()->issues()->create(Arr::except($request->all(), ['products']));
        $issue->products()->attach(collect($request->products)->keyBy('product_id')->all());
        return created();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        return new ReceiptAndIssueWithProductResource($issue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        $issue->update(Arr::except($request->all(), ['products']));
        $issue->products()->sync(collect($request->products)->keyBy('product_id')->all());
        return updated();
    }

    public function confirm(Issue $issue)
    {
        $issue->update(['confirmed' => true]);
        return updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        if ($issue->confirmed) return response(['message' => 'Không thể xóa phiếu nhập đã xác nhận']);
        $issue->products()->detach();
        return delete($issue);
    }
}
