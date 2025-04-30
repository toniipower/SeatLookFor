<?php
namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Usa la fábrica para crear 10 usuarios
        Usuario::factory(10)->create();
    }
}
