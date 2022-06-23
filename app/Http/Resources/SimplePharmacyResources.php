<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SimplePharmacyResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return parent::toArray($request);

       return[
        'id' => $this->id,
        'name' =>$this->name,
        'phone' => $this->phone,
        'location' => $this->location,
        'image' => $this->image,
        'state' =>$this->state
    ];
    }
}
