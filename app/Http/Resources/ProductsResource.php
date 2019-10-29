<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
            'code' => $this->code,
            'manufacturer' => $this->manufacturer,
            'type' => $this->type,
            'barcode' => $this->barcode,
            'brand' => $this->brand,
        ];
    }
}
