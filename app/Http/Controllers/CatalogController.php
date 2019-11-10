<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Http\Requests\CatalogRequest;
use App\Http\Resources\CatalogResource;
use Symfony\Component\HttpFoundation\Request;

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
    public function listCatalogs(Request $request)
    {
        $root = $request->query('root');
        $parent = $request->query('parent');
        return ['data' => Catalog::where([
            ['parent_id', null],
            ['name', $root],
        ])->first()->catalogs()->where('name', $parent)->first()->catalogs()->select('id', 'name')->get()];
    }
}
