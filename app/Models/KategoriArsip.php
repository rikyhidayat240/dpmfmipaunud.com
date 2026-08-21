<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriArsip extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriArsipFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
