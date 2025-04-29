<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'estado' => $this->fake()->randomElement(['activo', 'inactivo', 'pendiente']),
            'valoracion' => $this->fake()->randomFloat(1, 1, 5), 
            'ubicacion' => $this->fake()->address(),
            'tipo' => $this->fake()->randomElement(['obra de teatro', 'musical', 'concierto']),
            'titulo' => $this->fake()->sentence(4),
            'descripcion' => $this->fake()->paragraph(),
            'duracion' => $this->fake()->time('H:i:s'),
            'fecha' => $this->fake()->date(),
            'categoria' => $this->fake()->randomElement(['cultural', 'deportivo', 'educativo', 'musical']),
            'idEst' => \App\Models\Establecimiento::factory(), 
        ];
    }
}
