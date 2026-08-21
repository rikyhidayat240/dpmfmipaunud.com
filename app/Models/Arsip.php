<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arsip extends Model
{
    /** @use HasFactory<\Database\Factories\ArsipFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'file' => 'array',
    ];
    // protected $with = [
    //     'user',
    //     'kategori',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function kategori()
    {
        return $this->belongsTo(KategoriArsip::class, 'id_kategori');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_arsip');
    }
    public function mentions()
    {
        return $this->hasMany(Mention::class, 'id_arsip');
    }
}
