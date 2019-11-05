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
            'name' => $this->name,
            'code' => $this->code,
            'manufacturer' => $this->manufacturer,
            'type' => $this->type,
            'barcode' => $this->barcode,
            'brand' => $this->brand,
            'sale_price' => $this->sale_price,
            'unit' => $this->unit,
            'tax' => $this->tax,
            'image' => new ProductImageResource($this->images->where('default', true)->first() ? $this->images->where('default', true)->first() : $this->images->first())
        ];
    }
}
