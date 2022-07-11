<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'note'=>$this->note,
            'customer_id'=>$this->customer_id,
            'driver_id'=>$this->driver_id,
            'pharmacy_id'=>$this->pharmacy_id,
        ];
}
}
