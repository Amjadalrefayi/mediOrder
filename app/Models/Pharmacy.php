<?php

namespace App\Models;

use App\Http\Traits\child;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends User
{
    use HasFactory , child;


    /**
     * Get all of the orders for the Pharmacy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /**
     * Get all of the products for the Pharmacy
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
