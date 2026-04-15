<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransOrderDetail extends Model
{
    protected $table = 'trans_order_detail';

    protected $fillable = [
        'id_order',
        'id_service',
        'qty',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'double',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(TransOrder::class, 'id_order');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(TypeOfService::class, 'id_service');
    }
}
