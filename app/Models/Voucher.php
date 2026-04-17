<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'discount',
        'valid_date'
    ];

    // relasi ke penggunaan voucher
    public function usages()
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id');
    }
}