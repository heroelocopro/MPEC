<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profesor>
 */
class ProfesorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'colegio_id' => null, // Lo asignas en el seeder
            'sede_id' => null,    // También en el seeder, si aplica
            'nombre_completo' => $this->faker->name,
            'documento' => $this->faker->unique()->numerify('##########'),
            'tipo_documento' => $this->faker->randomElement(['CC', 'TI', 'CE']),
            'correo' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'titulo_academico' => $this->faker->randomElement([
                'Licenciado en Educación',
                'Magíster en Matemáticas',
                'Especialista en Pedagogía'
            ]),
        ];
    }
}
