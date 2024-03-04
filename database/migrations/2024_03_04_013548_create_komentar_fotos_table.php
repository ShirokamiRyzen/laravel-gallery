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
        Schema::create('komentarfotos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_foto')->index()->unsigned()->nullable();
            $table->bigInteger('id_user')->index()->unsigned()->nullable();
            $table->text('IsiKomentar');
            $table->timestamps();

            $table->foreign('id_foto')->references('id')->on('fotos')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentarfotos');
    }
};
