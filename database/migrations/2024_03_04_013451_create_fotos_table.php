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
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->string('JudulFoto');
            $table->text('DeskripsiFoto')->nullable();
            $table->string('LokasiFile');
            $table->bigInteger('id_album')->index()->unsigned()->nullable();
            $table->bigInteger('id_user')->index()->unsigned()->nullable();

            $table->foreign('id_album')->references('id')->on('albums')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};
