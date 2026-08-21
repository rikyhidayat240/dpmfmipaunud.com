<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Models\Arsip;
use App\Models\Dosen;
use App\Models\Surat;
use App\Models\Comment;
use App\Models\Laporan;
use App\Models\Lembaga;
use App\Models\Mention;
use App\Models\Aspirasi;
use App\Models\JadwalMonev;
use App\Models\ProgramKerja;
use App\Models\ProgramStudi;
use App\Policies\BlogPolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Models\KategoriArsip;
use App\Models\KategoriSurat;
use App\Policies\ArsipPolicy;
use App\Policies\DosenPolicy;
use App\Policies\SuratPolicy;
use App\Models\NotulensiMonev;
use App\Models\PenilaianMonev;
use App\Policies\CommentPolicy;
use App\Policies\LaporanPolicy;
use App\Policies\LembagaPolicy;
use App\Policies\MentionPolicy;
use App\Models\KategoriAspirasi;
use App\Models\Signature;
use App\Policies\AspirasiPolicy;
use App\Policies\JadwalMonevPolicy;
use App\Policies\ProgramKerjaPolicy;
use App\Policies\ProgramStudiPolicy;
use App\Policies\KategoriArsipPolicy;
use App\Policies\KategoriSuratPolicy;
use App\Policies\NotulensiMonevPolicy;
use App\Policies\PenilaianMonevPolicy;
use App\Policies\KategoriAspirasiPolicy;
use App\Policies\SignaturePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Dosen::class => DosenPolicy::class,
        ProgramStudi::class => ProgramStudiPolicy::class,
        Lembaga::class => LembagaPolicy::class,
        ProgramKerja::class => ProgramKerjaPolicy::class,
        JadwalMonev::class => JadwalMonevPolicy::class,
        NotulensiMonev::class => NotulensiMonevPolicy::class,
        PenilaianMonev::class => PenilaianMonevPolicy::class,
        Aspirasi::class => AspirasiPolicy::class,
        KategoriAspirasi::class => KategoriAspirasiPolicy::class,
        Post::class => PostPolicy::class,
        Blog::class => BlogPolicy::class,
        Surat::class => SuratPolicy::class,
        KategoriSurat::class => KategoriSuratPolicy::class,
        Laporan::class => LaporanPolicy::class,
        Arsip::class => ArsipPolicy::class,
        KategoriArsip::class => KategoriArsipPolicy::class,
        Comment::class => CommentPolicy::class,
        Mention::class => MentionPolicy::class,
        Signature::class => SignaturePolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        \Illuminate\Support\Facades\Gate::before(function (User $user, string $ability, array|string $model = []) {
            if (!$user->is_active) {
                // Return false to explicitly deny, null to let policy handle it.
                $restrictedAbilities = ['viewAny', 'create', 'delete', 'restore', 'forceDelete'];
                if (in_array($ability, $restrictedAbilities)) {
                    return false;
                }

                if (in_array($ability, ['view', 'update'])) {
                    // Cek jika model adalah model User milik sendiri, biarkan Policy aslinya yang menangani
                    // `$model` bisa string (class name) atau array objek model.
                    if (is_array($model) && isset($model[0]) && $model[0] instanceof User && $model[0]->id === $user->id) {
                        return null;
                    }
                    if ($model instanceof User && $model->id === $user->id) {
                        return null;
                    }
                    return false;
                }
            }
        });

        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Filament\Support\Facades\FilamentAsset::register([
            \Filament\Support\Assets\Js::make('heic-converter', public_path('js/heic-converter.js')),
        ]);
    }
}
