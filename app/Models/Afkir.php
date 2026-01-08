<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afkir extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function aset()
    {
        return $this->belongsTo(Asets::class, 'aset_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
