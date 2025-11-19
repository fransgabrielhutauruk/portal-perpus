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
           // 1. Primary Key (Custom Name)
            $table->id('reqbuku_id');

            // 2. Foreign Keys
            // Note: I used unsignedBigInteger because standard Laravel IDs are BigInts.
            // If your parent tables use standard Integers, change this to ->integer() or ->unsignedInteger()
            $table->unsignedBigInteger('periode_id');
            $table->unsignedBigInteger('prodi_id');

            // 3. Requester Info
            $table->string('nama_req');
            $table->integer('nim')->nullable(); // Made nullable (User might be a Lecturer)
            $table->integer('nip')->nullable(); // Made nullable (User might be a Student)
            $table->string('email_req');

            // 4. Book Details
            $table->string('judul_buku');
            $table->string('penulis_buku');
            $table->integer('tahun_terbit');
            $table->string('penerbit_buku');
            $table->string('jenis_buku');
            $table->string('bahasa_buku');

            // Note: For currency, 'decimal' is usually better, but I kept 'integer' as requested
            $table->integer('estimasi_harga');
            $table->string('link_pembelian')->nullable(); // Nullable in case link is missing
            $table->text('alasan_usulan');

            // 5. Status & Admin
            $table->integer('status_req')->default(0); // Added default (e.g., 0 = pending)
            $table->text('catatan_admin')->nullable(); // Nullable (Empty on create)

            // 6. Audit Trail (Manual strings as requested)
            $table->string('created_by', 10)->nullable();
            $table->string('updated_by', 10)->nullable();
            $table->string('deleted_by', 10)->nullable();

            // 7. Standard Timestamps (created_at, updated_at)
            $table->timestamps();

            // 8. Soft Deletes (deleted_at)
            $table->softDeletes();

            // OPTIONAL: Foreign Key Constraints (Uncomment if tables exist)
            // $table->foreign('mstperiode_id')->references('id')->on('mst_periode');
            // $table->foreign('prodi_id')->references('id')->on('dm_prodi');
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
