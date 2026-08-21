<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Surat extends Model
{
    /** @use HasFactory<\Database\Factories\SuratFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'lembaga',
    //     'programKerja',
    //     'kategori',
    // ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'id_lembaga');
    }
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_proker');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_surat');
    }
    public function mentions()
    {
        return $this->hasMany(Mention::class, 'id_surat');
    }
}
