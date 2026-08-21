<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspirasi extends Model
{
    /** @use HasFactory<\Database\Factories\AspirasiFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'programStudi',
    //     'kategori',
    // ];
    protected $casts = [
        'photos' => 'array',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriAspirasi::class, 'id_kategori');
    }
}
