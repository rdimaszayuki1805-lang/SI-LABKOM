
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_siswas', function (Blueprint $table) {

            $table->string('mata_pelajaran', 100)->nullable();

            $table->string('monitor', 10)->nullable();

            $table->string('keyboard', 10)->nullable();

            $table->string('mouse', 10)->nullable();

            $table->string('cpu', 10)->nullable();

            $table->string('jaringan', 10)->nullable();

            $table->boolean('ada_kendala')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('logbook_siswas', function (Blueprint $table) {

            $table->dropColumn([
                'mata_pelajaran',
                'monitor',
                'keyboard',
                'mouse',
                'cpu',
                'jaringan',
                'ada_kendala',
            ]);
        });
    }
};
