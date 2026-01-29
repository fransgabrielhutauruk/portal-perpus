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
        Schema::create('req_bebas_pustaka', function (Blueprint $table) {
            $table->id('reqbebaspustaka_id');
            $table->string('nama_mahasiswa');
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->string('nim');
            $table->string('email_mahasiswa');
            $table->string('link_kp_repository')->nullable();
            $table->string('link_pa_repository')->nullable();
            $table->boolean('is_syarat_terpenuhi')->default(false);
            $table->integer('status_req')->default(0); 
            $table->text('catatan_admin')->nullable();
            $table->string('file_hasil_bebas_pustaka')->nullable();
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
        Schema::dropIfExists('req_bebas_pustaka');
    }
};
