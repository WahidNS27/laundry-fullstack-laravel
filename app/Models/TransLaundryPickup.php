<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransLaundryPickup extends Model
{
    protected $table = 'trans_laundry_pickup';

    protected $fillable = [
        'id_order',
        'id_customer',
        'pickup_date',
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(TransOrder::class, 'id_order');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
