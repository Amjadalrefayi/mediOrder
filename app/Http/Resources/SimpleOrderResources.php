<?php

namespace App\Http\Resources;
use App\Models\Pharmacy;

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
            'customer_id' => $this->customer_id,
            'pharmacy_id' => $this->getPharmacy(),
            'image' => $this->image,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'status' => $this->state,
            'total_price' => $this->total_price,
        ];
    }

    public function getPharmacy()
    {
        if ($this->pharmacy_id != null) {
            $pharmacy = Pharmacy::find($this->pharmacy_id);
            return new SimplePharmacyResources($pharmacy);
        }
        return null;
    }
}
