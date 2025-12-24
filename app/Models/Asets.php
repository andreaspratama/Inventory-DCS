<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asets extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tgl_beli' => 'date',   // ⬅️ INI PENTING
        'harga' => 'float',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
}
