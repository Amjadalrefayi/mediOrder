<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable=['customer_id','pharmacy_id','driver_id','note'];
    protected $dates = ['deleted_at'];

    /**
     * Get the customer that owns the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the pharmacy that owns the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * Get the driver that owns the Complaint
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }


}
