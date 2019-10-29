<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function destroy(File $file)
    {
        Storage::delete('public/upload/' . $file->name);
        $file->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
