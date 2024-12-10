<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'price', // Menambahkan kolom 'price' agar bisa diisi dalam operasi mass assignment
        'category_id', // Tambahkan juga 'category_id' jika perlu disimpan melalui mass assignment
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer', // Mengatur casting 'price' ke integer jika data harga disimpan dalam satuan rupiah tanpa desimal
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
