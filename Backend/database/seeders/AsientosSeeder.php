<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsientosSeeder extends Seeder
{   
    public function run()
    {
        $asientos = [];

        for ($i = 0; $i < 20; $i++) {
            $asientos[] = [
                'estado' => 'libre',
                'zona' => 'a',
                'ejeX' => $i % 5, // 5 columnas
                'ejeY' => intdiv($i, 5), // 4 filas
                'precio' => 0.00,
                'idEst' => 13,
            ];
        }

        DB::table('asiento')->insert($asientos);
    }
}
