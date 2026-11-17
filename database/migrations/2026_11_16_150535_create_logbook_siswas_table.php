
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_siswas', function (Blueprint $table) {

            $table->id();

            // Relasi dengan akun siswa.
            $table->foreignId('siswa_id')
                ->constrained('siswas')
                ->cascadeOnDelete();

            // Informasi kegiatan.
            $table->string('nomor_komputer', 50);

            $table->text('kegiatan');

            $table->string('kondisi_perangkat', 30);

            $table->text('kendala')->nullable();

            // Jam login yang dicatat dari server.
            $table->timestamp('waktu_login');

            // created_at: waktu logbook disimpan.
            // updated_at: waktu perubahan data.
            $table->timestamps();

            $table->index([
                'siswa_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_siswas');
    }
};
