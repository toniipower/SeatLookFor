<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('zona', function (Blueprint $table) {
            $table->integer('idZona')->primary();
            $table->string('nombre');
        });
    }

    public function down(): void {
        Schema::dropIfExists('zona');
    }
};
