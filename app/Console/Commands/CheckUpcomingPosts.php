<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Filament\Notifications\Notification;

class CheckUpcomingPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-upcoming-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and notify users about upcoming Instagram feed posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Post::query()
            ->where('tanggal_upload', '>', now())
            ->where('tanggal_upload', '<=', now()->addDays(2))
            ->each(function (Post $post) {
                $daysRemaining = now()->startOfDay()->diffInDays($post->tanggal_upload);
                if ($post->tanggal_upload == now()->format('Y-m-d')) {
                    Notification::make()
                        ->title('Pengingat Feeds Instagram')
                        ->body("Hari ini adalah jadwal Anda untuk memproses feeds {$post->title}.")
                        ->warning()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Proses Feeds')
                                ->url(route('filament.admin.resources.feeds-instagram.index')),
                        ])
                        ->sendToDatabase(User::find($post->id_user));
                } elseif ($daysRemaining <= 2 && $daysRemaining >= 0) {
                    $daysRemaining++;
                    Notification::make()
                        ->title('Pengingat Feeds Instagram')
                        ->body("{$daysRemaining} hari lagi menuju jadwal proses feeds {$post->title}.")
                        ->warning()
                        ->sendToDatabase(User::find($post->id_user));
                    $daysRemaining--;
                }
                $this->info("Sent notification for post: {$post->title}");
            });
        return Command::SUCCESS;
    }
}
