<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function destroy(File $file)
    {
        Storage::delete('upload/' . $file->name);
        $file->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
    public  function download(Request $request)
    {
        return Storage::download('upload/' . $request->name);
    }
}
