<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'category',
        'sku'
    ];
    /**
     * Relacionamento: Produto pertence a uma categoria
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
