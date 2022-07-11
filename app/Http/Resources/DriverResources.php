<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResources extends JsonResource
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
            'remember_token' => $this->remember_token,
            'name' =>$this->name,
            'email' =>$this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'location' => $this->location,
            'image' => $this->image,

        ];
    }
}
