<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenilaianMonev extends Model
{
    /** @use HasFactory<\Database\Factories\PenilaianMonevFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function notulensis()
    {
        return $this->hasMany(NotulensiMonev::class, 'nilai_notulensis', 'id_nilai')
                    ->withPivot('score', 'description');
    }
}
