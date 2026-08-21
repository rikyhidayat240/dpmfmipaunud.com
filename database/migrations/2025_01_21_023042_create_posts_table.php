<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('caption');
            $table->json('photos')->nullable();
            $table->text('link_drive')->nullable();
            $table->date('tanggal_upload');
            $table->enum('category', ['Program Kerja', 'Hari Raya', 'Hotline', 'Opening Bulan', 'Tim Monev', 'Repost', 'Ucapan', 'Laporan', 'Lainnya']);
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('diproses')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
