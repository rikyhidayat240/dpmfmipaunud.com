<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    /** @use HasFactory<\Database\Factories\LaporanFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'lembaga',
    //     'programKerja',
    // ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'id_lembaga');
    }
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_proker');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_laporan');
    }
    public function mentions()
    {
        return $this->hasMany(Mention::class, 'id_laporan');
    }
}
