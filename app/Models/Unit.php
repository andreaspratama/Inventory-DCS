<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function asets()
    {
        return $this->hasMany(Asets::class);
    }

    public function ruangs()
    {
        return $this->hasMany(Ruang::class);
    }
}
