<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Http\Requests\CatalogRequest;
use App\Http\Resources\CatalogResource;

class CatalogController extends Controller
{

    public function index()
    {
        return CatalogResource::collection(Catalog::root()->with(['childrens' => function ($query) {
            $query->where('company_id', company()->id)->orWhereNull('company_id');
        }])->get());
    }

    public function store(CatalogRequest $request)
    {
        company()->catalogs()->create($request->all());
        return created();
    }

    public function update(CatalogRequest $request, Catalog $catalog)
    {
        $catalog->update($request->all());
        return updated();
    }

    public function destroy(Catalog $catalog)
    {
        delete($catalog);
    }
}
