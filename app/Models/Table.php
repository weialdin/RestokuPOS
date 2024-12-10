<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    /**
     * Tentukan tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tables';

    /**
     * Tentukan atribut yang dapat diisi (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'table_number',
        'status',
    ];
}
