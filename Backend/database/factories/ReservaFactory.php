<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fechaReserva' => $this->fake()->date(),
            'precio' => $this->fake()->randomFloat(2, 100, 1000), 
            'descuento' => $this->fake()->randomFloat(2, 0, 100), 
            'total' => function (array $attributes) {
                return $attributes['precio'] - $attributes['descuento']; // Total calculado
            },

            'idEst' => \App\Models\Evento::factory(), // Asegúrate de tener esta factory creada
            'idAsi' => \App\Models\Asiento::factory(), // Asegúrate de tener esta factory creada
            'idUsu' => \App\Models\Usuario::factory(), // Asegúrate de tener esta factory creada
        ];
    }
}
