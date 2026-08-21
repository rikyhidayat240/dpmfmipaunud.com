<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAspirasi extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriAspirasiFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'id_kategori');
    }
}
