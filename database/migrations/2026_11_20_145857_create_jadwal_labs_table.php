
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_labs', function (Blueprint $table) {
            $table->id();

            $table->string('hari', 10);
            $table->string('kelas', 2);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('mata_pelajaran', 100);
            $table->string('guru', 150);
            $table->string('lokasi', 50);

            $table->enum('status', [
                'Belum Dimulai',
                'Berlangsung',
                'Selesai',
            ])->default('Belum Dimulai');

            $table->timestamps();

            $table->index(['hari', 'lokasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_labs');
    }
};
