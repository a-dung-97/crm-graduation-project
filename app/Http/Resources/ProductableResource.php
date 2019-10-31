<?php

namespace App\Http\Resources;

use App\Warehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductableResource extends JsonResource
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
            'detail' => [
                'warehouse_id' => $this->detail->warehouse_id,
                'warehouse_name' => Warehouse::find($this->detail->warehouse_id)->name,
                'quantity' => $this->detail->quantity,
                'unit' => $this->detail->unit,
                'tax' => $this->detail->tax,
                'price' => $this->detail->price,
            ]
        ];
    }
}
