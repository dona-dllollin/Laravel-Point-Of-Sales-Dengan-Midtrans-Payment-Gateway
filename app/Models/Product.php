<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public $table = 'products';

    protected $fillable = [
        'kode_barang',
        'image',
        'nama_barang',
        'satuan',
        'stok',
        'harga_beli',
        'harga_jual',
        'keterangan',
        'market_id'
    ];


    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
    
    public function satuanId(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class, 'category_product', 'product_id', 'category_id');
    }
}
