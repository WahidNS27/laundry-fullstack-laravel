<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customer';

    protected $fillable = [
        'customer_name',
        'phone',
        'address',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(TransOrder::class, 'id_customer');
    }

    public function pickups(): HasMany
    {
        return $this->hasMany(TransLaundryPickup::class, 'id_customer');
    }
}
