<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lembaga extends Model
{
    /** @use HasFactory<\Database\Factories\LembagaFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'id_lembaga');
    }
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_lembaga');
    }
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_lembaga');
    }
    public function signatures()
    {
        return $this->hasMany(Signature::class, 'id_lembaga');
    }
}
