<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\NoteRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
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
        $type = $request->query('type');
        $query =  company()->products()->latest();
        if ($type) $query = $query->where('type', $type);
        if ($search) $query = $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('barcode', 'like', '%' . $search . '%')
                ->orWhere('brand', 'like', '%' . $search . '%')
                ->orWhere('manufacturer', 'like', '%' . $search . '%');
        });

        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return ProductsResource::collection($query);
    }

    public function store(ProductRequest $request)
    {
        company()->products()->create($request->all());
        return response(['message' => 'created'], Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());
        return response(['message' => 'updated']);
    }


    public function addFileToProduct(FileRequest $request, Product $product)
    {
        $file = $this->handleUpload($request->file('file'));
        $product->files()->create([
            'name' => $file['name'],
            'size' => $file['size'],
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'company_id' => company()->id,
        ]);
        return ['message' => 'added'];
    }

    private function handleUpload($file)
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $file->move(public_path('storage/upload/') . $fileName);
        return collect(['name' => $fileName, 'size' => $fileSize]);
    }

    public function addNoteToProduct(NoteRequest $request, Product $product)
    {
        $request = $request->all();
        $request['user_id'] = auth()->user()->id;
        $request['company_id'] = company()->id;
        $product->notes()->create($request);
        return response(['message' => "added"]);
    }

    public function destroy(Product $product)
    {
        //
    }
}
