<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnection extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test database connection';

    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('Conexión exitosa a la base de datos!');
            $this->info('Versión de la base de datos: ' . DB::connection()->getServerVersion());
        } catch (\Exception $e) {
            $this->error('No se pudo conectar a la base de datos: ' . $e->getMessage());
        }
    }
} 