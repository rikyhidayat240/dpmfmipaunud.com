<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalMonev extends Model
{
    /** @use HasFactory<\Database\Factories\JadwalMonevFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    // protected $with = [
    //     'timMonev',
    //     'programKerja',
    // ];

    public function timMonev()
    {
        return $this->belongsToMany(User::class, 'jadwal_tim_monevs', 'id_jadwal', 'id_user')
            ->withPivot('hadir')
            ->withTimestamps();
    }
    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'id_proker');
    }
    public function notulensi()
    {
        return $this->hasOne(NotulensiMonev::class, 'id_jadwal');
    }

    protected static function boot()
    {
        parent::boot();
        static::updated(function (JadwalMonev $model) {
            if ($model->isDirty('tanggal')) {
                $targetDate = \DateTime::createFromFormat('Y-m-d', $model->tanggal);
                $daysRemaining = $targetDate->diff(now())->days;
                $timMonev = $model->timMonev()->pluck('id')->toArray();
                foreach ($timMonev as $id) {
                    if ($model->tanggal <= now()->format('Y-m-d')) {
                        Notification::make()
                            ->title('Pengingat Jadwal Monev')
                            ->body("Hari ini adalah jadwal monev Anda pada {$model->name} {$model->programKerja()->first()->name}.")
                            ->warning()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->label('Mulai Monev')
                                    ->url(route('filament.admin.resources.notulensi-monev.create')),
                            ])
                            ->sendToDatabase(User::find($id));
                    } elseif (($daysRemaining == 6 || $daysRemaining <= 2) && $daysRemaining >= 0) {
                        $daysRemaining++;
                        Notification::make()
                            ->title('Pengingat Jadwal Monev')
                            ->body("{$daysRemaining} hari lagi menuju {$model->name} {$model->programKerja()->first()->name}.")
                            ->warning()
                            ->sendToDatabase(User::find($id));
                        $daysRemaining--;
                    }
                }
            }
        });
    }
}
