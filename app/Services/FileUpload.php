<?php

namespace App\Services;

class FileUpload
{
    public static function addFile($request,  $model)
    {
        $file = self::handleUpload($request->file);
        $model->files()->create([
            'name' => $file['name'],
            'size' => $file['size'],
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'company_id' => company()->id,
        ]);
        return ['message' => 'added'];
    }
    private static function handleUpload($file)
    {
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . uniqid() . '.' . $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        $file->storeAs(
            'files',
            $fileName
        );
        return collect(['name' => $fileName, 'size' => $fileSize]);
    }
}
