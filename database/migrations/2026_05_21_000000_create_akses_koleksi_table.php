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
        Schema::create('akses_koleksi', function (Blueprint $table) {
            $table->increments('akses_koleksi_id');
            $table->string('nama_akses_koleksi');
            $table->text('deskripsi')->nullable();
            $table->string('url');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('akses_koleksi');
    }
};
