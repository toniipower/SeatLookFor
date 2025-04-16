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
        Schema::create('comentario', function (Blueprint $table) {

            $table->text('comentario');
            $table->decimal('valoracion');
            $table->string('foto');


            $table->unsignedBigInteger('idUsu');
            $table->unsignedBigInteger('idAsi');

            $table->primary(['idUsu', 'idAsi']);

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
