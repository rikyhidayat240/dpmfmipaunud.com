<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'Buat Fungsionaris Baru';

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'role' => $data['role'],
            'isPimpinan' => !str_contains($data['specifiedRole'], 'Anggota'),
            'specifiedRole' => $data['specifiedRole'],
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']) ?? Hash::make('password'),
            'photo' => $data['photo'] ?? null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        return $user;
    }
}
