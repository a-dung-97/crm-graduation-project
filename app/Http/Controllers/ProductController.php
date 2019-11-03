<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\NoteRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\FIleResouce;
use App\Http\Resources\NoteResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductsResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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
        $query =  company()->products()->with('images')->latest();
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
        $product = company()->products()->create(Arr::except($request->all(), 'images'));
        $this->addImagesToProduct($request->images, $product);
        return response(['message' => 'created', 'data' => ['id' => $product->id]], Response::HTTP_CREATED);
    }


    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function getNotes(Product $product, Request $request)
    {
        return NoteResource::collection($product->notes()->paginate($request->query('per_page', 5)));
    }

    public function getFiles(Product $product, Request $request)
    {
        return FIleResouce::collection($product->files()->paginate($request->query('per_page', 5)));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->handleEditImage($request->images, $product);
        $product->update(Arr::except($request->all(), 'images'));
        return response(['message' => 'updated']);
    }

    private function addImagesToProduct($images, $product)
    {
        foreach ($images as $image) {
            $name = time() . '.' . explode('/', explode(':', substr($image['path'], 0, strpos($image['path'], ';')))[1])[1];
            Storage::put('products/' . $name, \Image::make($image['path'])->stream());
            $product->images()->create(['name' => $name, 'default' => $image['default']]);
        }
    }

    private function handleEditImage($images, $product)
    {
        $images = collect($images);
        $currentImages = $product->images;
        $currentImages->pluck('name')->diff(Arr::pluck($images, 'name'))->each(function ($image) use ($product) {
            $product->images()->where('name', $image)->first()->delete();
            Storage::delete('products/' . $image);
        });
        $newImage = collect($images)->pluck('name')->diff($currentImages->pluck('name')->all())->flatten();
        collect($images)->whereNotIn('name', $newImage)->values()->each(function ($image) use ($product) {
            $product->images()->where('name', $image['name'])->first()->update(['default' => $image['default']]);
        });
        $this->addImagesToProduct(collect($images)->whereIn('name', $newImage)->values(), $product);
    }

    public function addFileToProduct(FileRequest $request, Product $product)
    {
        $file = $this->handleUpload($request->file);
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
        $file->storeAs(
            'upload',
            $fileName
        );
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
