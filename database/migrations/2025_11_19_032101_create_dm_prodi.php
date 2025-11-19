<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dm_prodi', function (Blueprint $table) {
            $table->id('prodi_id');
            $table->timestamps();
            $table->string("alias_prodi");
            $table->string("alias_jurusan");
            $table->string("nama_prodi");
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_prodi');
    }
};
