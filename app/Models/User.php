<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Filament\Models\Contracts\HasName;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasAvatar, HasName, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];
    // protected $with = [
    //     'prodi',
    //     'programKerjas',
    //     'jadwalMonevs',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo ? Storage::url('public/' . $this->photo) : null;
    }
    public function getFilamentName(): string
    {
        return $this->username;
    }


    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi');
    }
    public function programKerjas()
    {
        return $this->belongsToMany(ProgramKerja::class, 'tim_monevs', 'id_user', 'id_proker');
    }
    public function jadwalMonevs()
    {
        return $this->belongsToMany(JadwalMonev::class, 'jadwal_tim_monevs', 'id_user', 'id_jadwal');
    }
    public function notulensi()
    {
        return $this->hasMany(NotulensiMonev::class, 'id_user');
    }
    public function arsip()
    {
        return $this->hasMany(Arsip::class, 'id_user');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_user');
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'id_user');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user');
    }
    public function signatures()
    {
        return $this->hasMany(Signature::class, 'id_user');
    }
}
