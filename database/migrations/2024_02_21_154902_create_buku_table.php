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
        Schema::create('buku', function (Blueprint $table) {
            $table->id('id_buku');
            $table->string('judul')->nullable();
            $table->string('subjudul')->nullable();
            $table->text('abstrak')->nullable();
            $table->string('cover')->nullable();
            $table->string('file')->nullable();
            $table->string('seri')->nullable();
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id_users')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['Penyerahan', 'Sedang Direview', 'Revisi', 'Ditolak', 'Diterima'])->nullable();
            $table->string('loa')->nullable();
            $table->timestamp('publish')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
