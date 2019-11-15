<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'size' => round($this->size / 1024, 2) . ' KB',
            'user' => $this->user->name,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
