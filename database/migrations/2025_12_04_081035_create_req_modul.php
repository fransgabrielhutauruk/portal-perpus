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
        Schema::create('req_modul', function (Blueprint $table) {
            $table->id('reqmodul_id');

            $table->unsignedBigInteger('periode_id')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();

            $table->string('nama_dosen')->nullable();
            $table->string('inisial_dosen')->nullable();
            $table->string('nip')->nullable();
            $table->string('email_dosen')->nullable();

            $table->string('judul_modul');
            $table->string('penulis_modul')->nullable();
            $table->integer('tahun_modul')->nullable();
            $table->string('nama_mata_kuliah');

            $table->boolean('praktikum')->default(false); 

            $table->integer('jumlah_dibutuhkan')->default(0);

            $table->string('file')->nullable();
            $table->text('deskripsi_kebutuhan')->nullable();

            $table->string('status')->default('Pending');
            $table->text('catatan_admin')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
            

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('req_modul');
    }
};
