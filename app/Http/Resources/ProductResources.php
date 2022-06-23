<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResources extends JsonResource
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
            'id' => $this->id,
            'pharmacy_id' =>$this->pharmacy_id,
            'name' => $this->name,
            'company'=>$this->company,
            'image'=>$this->image,
            'price'=>$this->price,
            'type'=>$this->type,
            'available'=>$this->available,
        ];
        //return parent::toArray($request);
    }
}
