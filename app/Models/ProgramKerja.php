<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKerja extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramKerjaFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'lembaga',
    //     'timMonev',
    // ];

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'id_lembaga');
    }
    public function timMonev()
    {
        return $this->belongsToMany(User::class, 'tim_monevs', 'id_proker', 'id_user');
    }
    public function jadwalMonevs()
    {
        return $this->hasMany(JadwalMonev::class, 'id_proker');
    }
    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_proker');
    }
    public function surats()
    {
        return $this->hasMany(Surat::class, 'id_proker');
    }
}
