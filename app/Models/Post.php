<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'user',
    // ];
    protected $casts = [
        'photos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function (Post $model) {
            if ($model->isDirty('tanggal_upload')) {
                $targetDate = \DateTime::createFromFormat('Y-m-d', $model->tanggal_upload);
                $daysRemaining = $targetDate->diff(now())->days;
                if ($model->tanggal_upload < now()->format('Y-m-d')) return;
                if ($model->tanggal_upload == now()->format('Y-m-d')) {
                    Notification::make()
                        ->title('Pengingat Feeds Instagram')
                        ->body("Hari ini adalah jadwal Anda untuk memproses feeds {$model->title}.")
                        ->warning()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Proses Feeds')
                                ->url(route('filament.admin.resources.feeds-instagram.index')),
                        ])
                        ->sendToDatabase(User::find($model->id_user));
                } elseif ($daysRemaining <= 2 && $daysRemaining >= 0) {
                    $daysRemaining++;
                    Notification::make()
                        ->title('Pengingat Feeds Instagram')
                        ->body("{$daysRemaining} hari lagi menuju jadwal proses feeds {$model->title}.")
                        ->warning()
                        ->sendToDatabase(User::find($model->id_user));
                    $daysRemaining--;
                }
            }
        });
    }
}
