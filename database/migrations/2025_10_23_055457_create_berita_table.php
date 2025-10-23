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
        Schema::create('berita', function (Blueprint $table) {
            $table->increments('berita_id');
            $table->string('judul_berita');
            $table->text('isi_berita');
            $table->dateTime('tanggal_berita')->nullable();
            $table->unsignedInteger('user_id_author')->nullable();
            $table->string('status_berita');
            $table->string('meta_desc_berita')->nullable();
            $table->string('meta_keyword_berita')->nullable();
            $table->string('slug_berita')->nullable();
            $table->text('filename_berita')->nullable();
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
        Schema::dropIfExists('berita');
    }
};
