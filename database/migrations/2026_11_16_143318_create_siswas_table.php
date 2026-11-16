
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {

            $table->id();

            $table->string('nama', 150);

            // Simpan sebagai string agar angka nol
            // di depan NISN tidak hilang.
            $table->string('nisn', 10)->unique();

            $table->string('kelas', 2)->index();

            // Password tidak disimpan sebagai teks asli.
            $table->string('password');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
