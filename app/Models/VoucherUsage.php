<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    protected $table = 'voucher_usages';

    protected $fillable = [
        'voucher_id',
        'customer_id',
        'used_date'
    ];

    // relasi ke voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    // relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}