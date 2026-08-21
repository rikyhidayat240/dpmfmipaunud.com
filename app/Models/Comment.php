<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'user',
    //     'surat',
    //     'laporan',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat');
    }
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }
    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'id_arsip');
    }
}
