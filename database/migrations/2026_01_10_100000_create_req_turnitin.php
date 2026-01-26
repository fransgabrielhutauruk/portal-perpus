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
        Schema::create('req_turnitin', function (Blueprint $table) {
            $table->id('reqturnitin_id');

            $table->unsignedBigInteger('prodi_id');

            $table->string('nama_dosen');
            $table->string('inisial_dosen');
            $table->string('nip');
            $table->string('email_dosen');

            $table->string('jenis_dokumen');
            $table->string('judul_dokumen');
            $table->string('file_dokumen');
            $table->text('keterangan');

            $table->string('status_req')->default(0);
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
        Schema::dropIfExists('req_turnitin');
    }
};
