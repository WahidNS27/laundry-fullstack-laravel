<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransOrder extends Model
{
    use SoftDeletes;

    protected $table = 'trans_order';

    protected $fillable = [
        'id_customer',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_pay',
        'order_change',
        'total',
    ];

    protected $casts = [
        'order_date'     => 'date',
        'order_end_date' => 'date',
        'order_status'   => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransOrderDetail::class, 'id_order');
    }

    public function pickup(): HasOne
    {
        return $this->hasOne(TransLaundryPickup::class, 'id_order');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->order_status == 0 ? 'Baru' : 'Sudah Diambil';
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->order_status == 0 ? 'warning' : 'success';
    }
}
