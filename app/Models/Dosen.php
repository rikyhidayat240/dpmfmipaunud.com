<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    /** @use HasFactory<\Database\Factories\DosenFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function programStudiKaprodi()
    {
        return $this->hasMany(ProgramStudi::class, 'id_kaprodi');
    }
    public function programStudiDospem()
    {
        return $this->hasMany(ProgramStudi::class, 'id_dospem');
    }
}
