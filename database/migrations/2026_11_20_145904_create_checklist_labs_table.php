
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_labs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pjlab_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('lokasi', 50);

            // Menyimpan hasil seluruh komponen sebagai JSON.
            $table->json('hasil');

            $table->enum('status', [
                'Siap',
                'Tidak Siap',
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_labs');
    }
};
