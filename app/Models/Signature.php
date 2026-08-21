<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    /** @use HasFactory<\Database\Factories\SuratFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    { 
        return $this->belongsTo(User::class, 'id_user');
    }
    public function lembaga()
    { 
        return $this->belongsTo(Lembaga::class, 'id_lembaga');
    }
}
