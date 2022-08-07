<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Pharmacy;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResources extends JsonResource
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
            'driver_id' => $this->driver_id,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'products' => $this->products(),
            'total_price' => $this->total_price,
            'expected_time' =>$this->expected_time
        ];
    }

    public function products()
    {
        $products = [];
        $carts = Cart::where('order_id', $this->id)->get();
        foreach ($carts as $cart) {
            $prodact = Product::find($cart->product_id);
            $products[] = new SimpleProductResources($prodact);
        }

        return $products;
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
