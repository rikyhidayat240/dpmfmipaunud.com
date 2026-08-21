<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class NotulensiMonev extends Model
{
    /** @use HasFactory<\Database\Factories\NotulensiMonevFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'jadwal',
    //     'user',
    // ];
    protected $casts = [
        'tim_monev' => 'array',
        'scores' => 'array',
        'descriptions' => 'array',
        'photos' => 'array',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalMonev::class, 'id_jadwal');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function penilaian()
    {
        return $this->belongsToMany(PenilaianMonev::class, 'nilai_notulensis', 'id_notulensi', 'id_nilai')
                    ->withPivot('score', 'description');
    }
}
