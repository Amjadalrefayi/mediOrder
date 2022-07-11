<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOrderResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return[

            'id' => $this->id,
            // 'customer_id' => $this->customer_id,
            // 'pharmacy_id' => $this->pharmacy_id,
            // 'driver_id' => $this->driver_id,
            // 'remember_token' => $this->remember_token,
            'total_price' => $this->total_price,
            'expected_time' =>$this->expected_time
        ];
    }
}
