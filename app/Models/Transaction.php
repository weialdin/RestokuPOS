<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Pastikan kolom-kolom yang diperlukan sudah didefinisikan
    protected $fillable = [
        'customer_name',
        'menu_name',
        'quantity',
        'total_purchase',
        'payment_type',
        'created_at',
    ];
}
