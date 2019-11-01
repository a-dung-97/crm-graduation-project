<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'name' => $this->name,
            'path' => config('app.url') . '/storage/products/' . $this->name,
            'default' => $this->default,
            'highlight' => $this->default ? 1 : 0,
        ];
    }
}
