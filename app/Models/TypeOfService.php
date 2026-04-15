<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeOfService extends Model
{
    use SoftDeletes;

    protected $table = 'type_of_service';

    protected $fillable = [
        'service_name',
        'price',
        'description',
    ];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(TransOrderDetail::class, 'id_service');
    }
}
