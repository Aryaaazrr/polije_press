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
        Schema::create('history_buku', function (Blueprint $table) {
            $table->unsignedBigInteger('id_buku');
            $table->foreign('id_buku')->references('id_buku')->on('buku');
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id_users')->on('users');
            $table->string('file_revisi')->nullable();
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_buku');
    }
};