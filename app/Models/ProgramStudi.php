<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudi extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramStudiFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'kaprodi',
    //     'dosenPembina'
    // ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_prodi');
    }
    public function kaprodi()
    {
        return $this->belongsTo(Dosen::class, 'id_kaprodi');
    }
    public function dosenPembina()
    {
        return $this->belongsTo(Dosen::class, 'id_dospem');
    }
    public function aspirasi()
    {
        return $this->hasMany(Aspirasi::class, 'id_prodi');
    }
}
