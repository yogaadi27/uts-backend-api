<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    //nama tabel
    protected $table = 'products';

    //field/kolom yang berada pada table characters di database
    protected $fillable = [
        'category_id',
        'product',
        'description',
        'price',
        'stok',
        'image',
    ];

    /*
        Penjelasan :
        1 Produk hanya mempunyai 1 category
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
