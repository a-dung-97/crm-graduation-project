<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Resources\FileResource;
use App\Services\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function destroy(File $file)
    {
        Storage::delete('files/' . $file->name);
        $file->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
    public  function download(Request $request)
    {
        return Storage::download('files/' . $request->name);
    }
    public function addFiles(Request $request, $type, $id)
    {
        return FileUpload::addFile($request, getModel($type, $id));
    }
    public function getFiles(Request $request, $type, $id)
    {
        return FileResource::collection(getModel($type, $id)->files()->paginate($request->query('per_page', 5)));
    }
}
