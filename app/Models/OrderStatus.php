<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use App\Models\Order;

class OrderStatus extends Model
{

    protected $table = 'order_status';

    public $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('serial', 'asc');
        });
    }

    /**
     * @return hasMany
     */
    public function orders(): hasMany
    {
        return $this->hasMany(Order::class, 'status');
    }
}
