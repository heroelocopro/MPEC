<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'colegio_id' => null, // Se asigna en el seeder
            'sede_id' => null,    // Se asigna en el seeder
            'nombre_completo' => $this->faker->name,
            'documento' => $this->faker->unique()->numerify('##########'),
            'tipo_documento' => $this->faker->randomElement(['TI', 'CE']),
            'fecha_nacimiento' => $this->faker->date('Y-m-d', '-12 years', '-17 years'),
            'genero' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'grupo_sanguineo' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'eps' => $this->faker->company,
            'sisben' => $this->faker->randomElement(['A1','A2','A3','A4','A5','B1','B2','B3','B4','B5','C1','C2','C3','C4','C5','D1','D2','D3','D4','D5']),
            'poblacion_vulnerable' => $this->faker->randomElement([
                'Pobreza Extrema',
                'Pobreza Moderada',
                'Desplazados por la Violencia',
                'Niños, Niñas y Adolescentes',
                'Personas con Discapacidad',
                'Comunidades Indígenas',
                'Afrocolombianos',
                'Víctimas del Conflicto Armado',
                'Personas LGTBI',
                'Víctimas de Desastres Naturales',
                'Mujeres Víctimas de Violencia de Género',
                'Adultos Mayores',
                'Otros',
                'No reporta población vulnerable',
            ]),
            'discapacidad' => $this->faker->randomElement(['Ninguna', 'Visual', 'Auditiva','Física / Motora', 'Intelectual', 'Psicosocial','Múltiple']),
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'correo' => $this->faker->unique()->safeEmail,
        ];
    }
}
