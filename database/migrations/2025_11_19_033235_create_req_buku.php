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
        Schema::create('req_buku', function (Blueprint $table) {
            $table->id('reqbuku_id');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('prodi_id');
            $table->string('nama_req');
            $table->string('nim')->nullable();
            $table->string('nip')->nullable();
            $table->string('email_req');
            $table->string('judul_buku');
            $table->string('penulis_buku');
            $table->integer('tahun_terbit');
            $table->text('penerbit_buku');
            $table->string('jenis_buku');
            $table->string('bahasa_buku');
            $table->integer('estimasi_harga')->nullable();
            $table->string('link_pembelian');
            $table->text('alasan_usulan');
            $table->integer('status_req')->default(0);
            $table->text('catatan_admin')->nullable();
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
        Schema::dropIfExists('req_buku');
    }
};
