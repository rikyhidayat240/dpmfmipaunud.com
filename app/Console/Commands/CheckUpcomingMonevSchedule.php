<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\JadwalMonev;
use Illuminate\Console\Command;
use Filament\Notifications\Notification;

class CheckUpcomingMonevSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-upcoming-monev-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and notify users about upcoming monitoring and evaluation schedules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        JadwalMonev::query()
            ->where('tanggal', '>=', now()->format('Y-m-d'))
            ->where('tanggal', '<=', now()->addDays(6)->format('Y-m-d'))
            ->each(function (JadwalMonev $jadwal) {
                $daysRemaining = now()->startOfDay()->diffInDays($jadwal->tanggal);
                $timMonev = $jadwal->timMonev()->pluck('id')->toArray();
                foreach ($timMonev as $id) {
                    if ($jadwal->tanggal == now()->format('Y-m-d')) {
                        Notification::make()
                            ->title('Pengingat Jadwal Monev')
                            ->body("Hari ini adalah jadwal monev Anda pada {$jadwal->name} {$jadwal->programKerja()->first()->name}.")
                            ->warning()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->label('Mulai Monev')
                                    ->url(route('filament.admin.resources.notulensi-monev.create')),
                            ])
                            ->sendToDatabase(User::find($id));
                        $this->info("Sent today's notification to user ID: {$id} for {$jadwal->name}");
                    } elseif (($daysRemaining == 6 || $daysRemaining <= 2) && $daysRemaining >= 0) {
                        Notification::make()
                            ->title('Pengingat Jadwal Monev')
                            ->body(($daysRemaining) . " hari lagi menuju {$jadwal->name} {$jadwal->programKerja()->first()->name}.")
                            ->warning()
                            ->sendToDatabase(User::find($id));
                        $this->info("Sent {$daysRemaining}-day reminder to user ID: {$id} for {$jadwal->name}");
                    }
                }
            });
        return Command::SUCCESS;
    }
}
