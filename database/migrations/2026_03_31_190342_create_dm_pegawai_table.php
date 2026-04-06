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
        Schema::create('dm_pegawai', function (Blueprint $table) {
            $table->increments('pegawai_id');
            $table->string('nip', 50)->unique();
            $table->string('nama', 255);
            $table->string('inisial', 3);
            $table->string('homebase', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_pegawai');
    }
};
