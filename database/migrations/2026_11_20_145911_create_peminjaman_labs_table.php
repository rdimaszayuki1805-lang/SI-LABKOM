
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_labs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('guru_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('kelas', 2);
            $table->string('mata_pelajaran', 100);

            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->text('tujuan');

            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
            ])->default('menunggu');

            $table->text('catatan_pjlab')->nullable();

            $table->enum('status_email', [
                'belum_dikirim',
                'terkirim',
                'gagal',
            ])->default('belum_dikirim');

            $table->timestamp('email_terkirim_at')->nullable();

            $table->timestamps();

            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_labs');
    }
};
