<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Wishlist extends Model
{
    protected $table = 'wishlists';

    public $guarded = [];

    protected $data_array = ['product_id'];

    /**
     * Get the product that owns the wishlist.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
