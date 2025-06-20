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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('idUsu');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('estado');
            $table->boolean('admin')->default(false);
          
   
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
     
    }
};
