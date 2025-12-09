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
            // 1. Primary Key dengan nama khusus sesuai gambar
            $table->id('reqbebaspustaka_id');

            // 2. Data Mahasiswa
            $table->string('nama_mahasiswa');
            
            // Foreign Key ke Prodi (nullable jika data prodi bisa kosong atau belum berelasi ketat)
            $table->unsignedBigInteger('prodi_id')->nullable();
            
            $table->string('nim');
            $table->string('email_mahasiswa');

            // 3. Status & Syarat
            // Default false (0) untuk boolean
            $table->boolean('is_syarat_terpenuhi')->default(false);
            
            // Default status 'Pending' (atau sesuaikan dengan kebutuhan bisnis)
            $table->string('status')->default('Pending'); 

            // 4. Admin & File
            $table->text('catatan_admin')->nullable();
            $table->string('file_hasil_bebas_pustaka')->nullable(); // Menyimpan path file

            // 5. Audit Trail (Log User)
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            // 6. Timestamps (created_at, updated_at)
            $table->timestamps();

            // 7. Soft Deletes (deleted_at)
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
