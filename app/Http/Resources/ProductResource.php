<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'code' => $this->code,
            'unit' => $this->unit,
            'manufacturer' => $this->manufacturer,
            'brand' => $this->brand,
            'barcode' => $this->barcode,
            'perchase_price' => $this->perchase_price,
            'perchase_detail' => $this->perchase_detail,
            'tax' => $this->tax,
            'sale_price' => $this->sale_price,
            'distributor' => $this->distributor,
            'sale_detail' => $this->sale_detail,
            'created_at' => $this->created_at,
            'images' => ProductImageResource::collection($this->images()->latest('default')->get())
        ];
    }
}
