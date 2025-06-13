<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Acudiente;
use App\Models\asignatura;
use App\Models\Colegio;
use App\Models\Estudiante;
use App\Models\EstudianteGrupo;
use App\Models\Examen;
use App\Models\Foro;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\matricula;
use App\Models\PeriodoAcademico;
use App\Models\Pregunta_Examen;
use App\Models\Profesor;
use App\Models\respuesta_actividad;
use App\Models\Respuesta_Examen;
use App\Models\Respuesta_Foro;
use App\Models\sedes_colegio;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
                // Creacion de roles
        $roles = [
            ['nombre' => 'admin', 'descripcion' => 'Administrador del sistema'],
            ['nombre' => 'colegio', 'descripcion' => 'Colegio del sistema'],
            ['nombre' => 'docente', 'descripcion' => 'Docente del sistema'],
            ['nombre' => 'estudiante', 'descripcion' => 'Estudiante del sistema'],
            ['nombre' => 'acudiente', 'descripcion' => 'Acudiente del sistema'],
        ];
        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }

                $usuarios = [
            ['name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role_id' => 1],
            ['name' => 'colegio',
            'email' => 'colegio@gmail.com',
            'password' => bcrypt('colegio'),
            'role_id' => 2],
            ['name' => 'docente',
            'email' => 'docente@gmail.com',
            'password' => bcrypt('docente'),
            'role_id' => 3],
            ['name' => 'estudiante',
            'email' => 'estudiante@gmail.com',
            'password' => bcrypt('estudiante'),
            'role_id' => 4],
        ];

        foreach ($usuarios as $usuario)
        {
            \App\Models\User::create($usuario);
        }
        // 1️⃣ Crear colegios
        $colegios = [
            ['nombre' => 'Colegio Nacional', 'codigo_dane' => '110001', 'direccion' => 'Calle 123 #45-67', 'telefono' => '3001234567', 'correo' => 'info@colegionacional.edu.co','departamento' => 'cundinamarca','municipio' => 'girardot','estado' => 'ANTIGUO-ACTIVO','calendario' => 'A'],
            ['nombre' => 'Instituto Moderno', 'codigo_dane' => '110002', 'direccion' => 'Carrera 10 #20-30', 'telefono' => '3012345678', 'correo' => 'contacto@modernoinst.edu.co','departamento' => 'cundinamarca','municipio' => 'girardot','estado' => 'ANTIGUO-ACTIVO','calendario' => 'A'],
            ['nombre' => 'INSTITUCIÓN EDUCATIVA MANUEL ELKIN PATARROYO', 'codigo_dane' => '125307001737', 'direccion' => 'IND KILOMETRO 3 BARRIO EL DIAMANTE', 'telefono' => '8884916 - 8357954- 3122029656', 'correo' => '','departamento' => 'cundinamarca','municipio' => 'girardot','estado' => 'ANTIGUO-ACTIVO','calendario' => 'A'],
        ];

        foreach ($colegios as $data) {
            Colegio::create($data);
        }

        // crear Sedes de colegios
        $sedesColegios = [
            ['nombre' => 'SEDE EL DIAMANTE', 'codigo_dane' => '125307001192', 'direccion' => 'IND MZ 22 Y 23', 'telefono' => '8357954', 'correo' => '','departamento' => 'cundinamarca','municipio' => 'girardot','estado' => 'ANTIGUO-ACTIVO','calendario' => 'A','colegio_id' => 3],
            ['nombre' => 'SEDE MANUELA BELTRAN	', 'codigo_dane' => '125307000285', 'direccion' => 'KR 7 33 51', 'telefono' => '8312201', 'correo' => '','departamento' => 'cundinamarca','municipio' => 'girardot','estado' => 'ANTIGUO-ACTIVO','calendario' => 'A','colegio_id' => 3],

        ];
        foreach($sedesColegios as $data )
        {
            sedes_colegio::create($data);
        }

        // 2️⃣ Crear profesores
        // $profesores = [
        //     ['colegio_id' => 1,'sede_id' => null, 'nombre_completo' => 'Laura Martínez', 'documento' => '123456789', 'tipo_documento' => 'CC', 'correo' => 'laura@colegionacional.edu.co', 'telefono' => '3000000001', 'titulo_academico' => 'Licenciada en Matemáticas'],
        //     ['colegio_id' => 2,'sede_id' => null, 'nombre_completo' => 'Carlos Pérez', 'documento' => '987654321', 'tipo_documento' => 'CC', 'correo' => 'carlos@modernoinst.edu.co', 'telefono' => '3000000002', 'titulo_academico' => 'Magíster en Lengua Española'],
        //     ['colegio_id' => 3,'sede_id' => null, 'nombre_completo' => 'William Alfredo Gazabon Marengo', 'documento' => '92506012', 'tipo_documento' => 'CC', 'correo' => 'maggjunior@gmail.com', 'telefono' => '3227185856', 'titulo_academico' => 'Administrador de Empresas'],
        // ];

        Profesor::factory(10)->create([
            'colegio_id' => 1
        ]);
        Profesor::factory(10)->create([
            'colegio_id' => 2
        ]);
        Profesor::factory(10)->create([
            'colegio_id' => 3
        ]);

        // foreach ($profesores as $data) {
        //     Profesor::create($data);
        // }

        // 3️⃣ Crear estudiantes
        // $estudiantes = [
        //     [
        //         'colegio_id' => 3,
        //         'sede_id' => 1,
        //         'nombre_completo' => 'Ana Gómez',
        //         'documento' => '111111111',
        //         'tipo_documento' => 'TI',
        //         'fecha_nacimiento' => '2010-05-12',
        //         'genero' => 'Femenino',
        //         'grupo_sanguineo' => 'O+',
        //         'eps' => 'Sura',
        //         'sisben' => 'B2',
        //         'poblacion_vulnerable' => true,
        //         'discapacidad' => null,
        //         'direccion' => 'Calle 100 #20-50',
        //         'telefono' => '3100000001',
        //         'correo' => null,
        //     ],
        //     [
        //         'colegio_id' => 3,
        //         'sede_id' => 2,
        //         'nombre_completo' => 'Juan López',
        //         'documento' => '222222222',
        //         'tipo_documento' => 'TI',
        //         'fecha_nacimiento' => '2009-11-23',
        //         'genero' => 'Masculino',
        //         'grupo_sanguineo' => 'A+',
        //         'eps' => 'Coomeva',
        //         'sisben' => 'A1',
        //         'poblacion_vulnerable' => false,
        //         'discapacidad' => 'Visual',
        //         'direccion' => 'Carrera 15 #25-60',
        //         'telefono' => '3100000002',
        //         'correo' => null,
        //     ]
        // ];

        $e1 = Estudiante::factory(60)->create([
            'colegio_id' => 1
        ]);

        $e2 = Estudiante::factory(60)->create([
            'colegio_id' => 2
        ]);
        $e3 = Estudiante::factory(60)->create([
            'colegio_id' => 3
        ]);

        // foreach ($estudiantes as $data) {
        //     Estudiante::create($data);
        // }

        // crear periodos academicos



        $periodosAcademicos = [
            [
                'colegio_id'   => 3,
                'nombre'       => 'Primer Periodo',
                'fecha_inicio' => '2025-02-15',
                'fecha_fin'    => '2025-04-15',
                'estado'       => 'activo', // o 'inactivo'
                'ano'          => 2025,
            ],
            [
                'colegio_id'   => 3,
                'nombre'       => 'Segundo Periodo',
                'fecha_inicio' => '2025-04-16',
                'fecha_fin'    => '2025-06-15',
                'estado'       => 'inactivo',
                'ano'          => 2025,
            ],
            [
                'colegio_id'   => 3,
                'nombre'       => 'Tercer Periodo',
                'fecha_inicio' => '2025-07-15',
                'fecha_fin'    => '2025-09-15',
                'estado'       => 'inactivo',
                'ano'          => 2025,
            ],
            [
                'colegio_id'   => 3,
                'nombre'       => 'Cuarto Periodo',
                'fecha_inicio' => '2025-09-16',
                'fecha_fin'    => '2025-11-30',
                'estado'       => 'inactivo',
                'ano'          => 2025,
            ],
        ];

        foreach($periodosAcademicos as $data)
        {
            PeriodoAcademico::create($data);
        }


        // 4️⃣ Crear acudientes
        $acudientes = [
            ['estudiante_id' => 1,'colegio_id' =>3,'sede_id'=>1, 'nombre_completo' => 'Marta Gómez', 'documento' => '999999999', 'tipo_documento' => 'CC', 'parentesco' => 'Madre', 'telefono' => '3200000001', 'correo' => 'marta.gomez@mail.com'],
            ['estudiante_id' => 2,'colegio_id' =>3,'sede_id'=>2, 'nombre_completo' => 'Luis López', 'documento' => '888888888', 'tipo_documento' => 'CC', 'parentesco' => 'Padre', 'telefono' => '3200000002', 'correo' => 'luis.lopez@mail.com'],
        ];

        foreach ($acudientes as $data) {
            Acudiente::create($data);
        }



        // 🔟 Crear foros
        $foros = [
            ['colegio_id' => 1, 'titulo' => 'Discusión sobre reciclaje', 'contenido' => 'Compartan ideas sobre cómo mejorar el reciclaje en el colegio.', 'autor_id' => 1, 'tipo_autor' => 'profesor'],
        ];

        foreach ($foros as $data) {
            Foro::create($data);
        }

        // 🔁 Crear respuestas del foro
        $respuestas_foro = [
            ['foro_id' => 1, 'autor_id' => 1, 'tipo_autor' => 'estudiante', 'mensaje' => 'Podríamos tener más canecas de reciclaje en los pasillos.']
        ];

        foreach ($respuestas_foro as $data) {
            Respuesta_Foro::create($data);
        }
        $asignaturas = [
            [
                'nombre' => 'Matemáticas',
                'codigo' => 'MAT101',
                'area' => 'Matemáticas',
                'descripcion' => 'Desarrollo de habilidades numéricas, resolución de problemas y razonamiento lógico.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 5,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#FF5733',
            ],
            [
                'nombre' => 'Lengua Castellana',
                'codigo' => 'LEN101',
                'area' => 'Lenguaje',
                'descripcion' => 'Desarrollo de competencias comunicativas en lengua materna.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 5,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#3366FF',
            ],
            [
                'nombre' => 'Ciencias Naturales',
                'codigo' => 'CNA101',
                'area' => 'Ciencias',
                'descripcion' => 'Estudio de fenómenos naturales, biología, química y física básica.',
                'colegio_id' => 3,
                'grado_minimo' => 3,
                'grado_maximo' => 9,
                'carga_horaria' => 4,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#33CC66',
            ],
            [
                'nombre' => 'Ciencias Sociales',
                'codigo' => 'CSO101',
                'area' => 'Ciencias Sociales',
                'descripcion' => 'Estudio de historia, geografía, y procesos sociales.',
                'colegio_id' => 3,
                'grado_minimo' => 3,
                'grado_maximo' => 11,
                'carga_horaria' => 3,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#FF9966',
            ],
            [
                'nombre' => 'Constitución y Democracia',
                'codigo' => 'CON101',
                'area' => 'Ciencias Sociales',
                'descripcion' => 'Formación ciudadana y conocimiento de la Constitución Política.',
                'colegio_id' => 3,
                'grado_minimo' => 9,
                'grado_maximo' => 11,
                'carga_horaria' => 1,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#AA6600',
            ],
            [
                'nombre' => 'Cátedra de la Paz',
                'codigo' => 'PAZ101',
                'area' => 'Formación en Valores',
                'descripcion' => 'Promoción de la paz, la convivencia y la resolución de conflictos.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 1,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#A0D6B4',
            ],
            [
                'nombre' => 'Educación Ética y en Valores Humanos',
                'codigo' => 'ETV101',
                'area' => 'Formación en Valores',
                'descripcion' => 'Reflexión sobre la moral, la ética y el comportamiento en sociedad.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 1,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#DD4477',
            ],
            [
                'nombre' => 'Educación Religiosa',
                'codigo' => 'REL101',
                'area' => 'Religión',
                'descripcion' => 'Exploración del sentido espiritual, la fe y los valores religiosos.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 1,
                'tipo' => 'optativa',
                'estado' => true,
                'color' => '#FFD700',
            ],
            [
                'nombre' => 'Inglés',
                'codigo' => 'ING101',
                'area' => 'Idiomas',
                'descripcion' => 'Aprendizaje y perfeccionamiento de la lengua inglesa.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 3,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#66CCCC',
            ],
            [
                'nombre' => 'Tecnología e Informática',
                'codigo' => 'TEC101',
                'area' => 'Tecnología',
                'descripcion' => 'Uso responsable de herramientas digitales y pensamiento computacional.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 2,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#8888FF',
            ],
            [
                'nombre' => 'Filosofía',
                'codigo' => 'FIL101',
                'area' => 'Ciencias Sociales',
                'descripcion' => 'Introducción al pensamiento filosófico y al análisis crítico.',
                'colegio_id' => 3,
                'grado_minimo' => 10,
                'grado_maximo' => 11,
                'carga_horaria' => 2,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#9966FF',
            ],
            [
                'nombre' => 'Educación Física',
                'codigo' => 'EDF101',
                'area' => 'Educación Física',
                'descripcion' => 'Desarrollo físico, deportes y vida saludable.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 11,
                'carga_horaria' => 2,
                'tipo' => 'obligatoria',
                'estado' => true,
                'color' => '#FFCC33',
            ],
            [
                'nombre' => 'Educación Artística',
                'codigo' => 'ART101',
                'area' => 'Arte',
                'descripcion' => 'Exploración de la expresión artística y cultural.',
                'colegio_id' => 3,
                'grado_minimo' => 1,
                'grado_maximo' => 9,
                'carga_horaria' => 2,
                'tipo' => 'optativa',
                'estado' => true,
                'color' => '#FF99CC',
            ],
        ];

        foreach ($asignaturas as $data) {
            asignatura::create($data);
        }


        $grados = [
            // coleigo 1
            [
                'nombre' => 'Jardín',
                'colegio_id' => 1,
                'nivel' => 'preescolar',
                'descripcion' =>'Exploración y socialización.',
                'edad_referencia' => '4-5 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Primero',
                'colegio_id' => 1,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Segundo',
                'colegio_id' => 1,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Tercero',
                'colegio_id' => 1,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Cuarto',
                'colegio_id' => 1,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Quinto',
                'colegio_id' => 1,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Sexto',
                'colegio_id' => 1,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Septimo',
                'colegio_id' => 1,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Octavo',
                'colegio_id' => 1,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '12-14 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Noveno',
                'colegio_id' => 1,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '13-15 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Decimo',
                'colegio_id' => 1,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de fisica.',
                'edad_referencia' => '14-16 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Undécimo',
                'colegio_id' => 1,
                'nivel' => 'media',
                'descripcion' =>'Inicio de Desarrollo crítico y lógico.',
                'edad_referencia' => '15-17 años',
                'estado' => true,
            ],
            // colegio 2
            [
                'nombre' => 'Jardín',
                'colegio_id' => 2,
                'nivel' => 'preescolar',
                'descripcion' =>'Exploración y socialización.',
                'edad_referencia' => '4-5 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Primero',
                'colegio_id' => 2,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Segundo',
                'colegio_id' => 2,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Tercero',
                'colegio_id' => 2,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Cuarto',
                'colegio_id' => 2,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Quinto',
                'colegio_id' => 2,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Sexto',
                'colegio_id' => 2,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Septimo',
                'colegio_id' => 2,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Octavo',
                'colegio_id' => 2,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '12-14 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Noveno',
                'colegio_id' => 2,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '13-15 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Decimo',
                'colegio_id' => 2,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de fisica.',
                'edad_referencia' => '14-16 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Undécimo',
                'colegio_id' => 2,
                'nivel' => 'media',
                'descripcion' =>'Inicio de Desarrollo crítico y lógico.',
                'edad_referencia' => '15-17 años',
                'estado' => true,
            ],
            // colegio 3
            [
                'nombre' => 'Jardín',
                'colegio_id' => 3,
                'nivel' => 'preescolar',
                'descripcion' =>'Exploración y socialización.',
                'edad_referencia' => '4-5 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Primero',
                'colegio_id' => 3,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Segundo',
                'colegio_id' => 3,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Tercero',
                'colegio_id' => 3,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Cuarto',
                'colegio_id' => 3,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Quinto',
                'colegio_id' => 3,
                'nivel' => 'primaria',
                'descripcion' =>'Inicio de lectura y escritura.',
                'edad_referencia' => '6-7 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Sexto',
                'colegio_id' => 3,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Septimo',
                'colegio_id' => 3,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '11-12 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Octavo',
                'colegio_id' => 3,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '12-14 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Noveno',
                'colegio_id' => 3,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de educación secundaria.',
                'edad_referencia' => '13-15 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Decimo',
                'colegio_id' => 3,
                'nivel' => 'secundaria',
                'descripcion' =>'Inicio de fisica.',
                'edad_referencia' => '14-16 años',
                'estado' => true,
            ],
            [
                'nombre' => 'Undécimo',
                'colegio_id' => 3,
                'nivel' => 'media',
                'descripcion' =>'Inicio de Desarrollo crítico y lógico.',
                'edad_referencia' => '15-17 años',
                'estado' => true,
            ],
    ];

    foreach($grados as $data)
    {
        Grado::create($data);
    }

    // colegio 1
    $gradoId = 7;
    $gradoId = Grado::where('nombre', 'Sexto')->where('colegio_id', 1)->first()->id;
    for($i = 0; $i < count($e1); $i++)
    {

        matricula::create([
            'estudiante_id' => $e1[$i]->id,
            'colegio_id' => $e1[$i]->colegio_id,
            'sede_id' => null,
            'grado_id' => $gradoId,
            'tipo_matricula' => 'nueva',
            'estado' => 'activo',
            'fecha_matricula' => now(),
        ]);
        if($i == 10 || $i == 20 || $i == 30 || $i == 40 || $i == 50 || $i == 60 )
        {
            $gradoId++;
        }
    }
    // colegio 2
    $gradoId = 13;
    $gradoId = Grado::where('nombre', 'Sexto')->where('colegio_id', 2)->first()->id;
    for($i = 0; $i < count($e2); $i++)
    {

        matricula::create([
            'estudiante_id' => $e2[$i]->id,
            'colegio_id' => $e2[$i]->colegio_id,
            'sede_id' => null,
            'grado_id' => $gradoId,
            'tipo_matricula' => 'nueva',
            'estado' => 'activo',
            'fecha_matricula' => now(),
        ]);
        if($i == 10 || $i == 20 || $i == 30 || $i == 40 || $i == 50 || $i == 60 )
        {
            $gradoId++;
        }
    }
    // colegio 3
    $gradoId = 19;
    $gradoId = Grado::where('nombre', 'Sexto')->where('colegio_id', 3)->first()->id;
    for($i = 0; $i < count($e3); $i++)
    {

        matricula::create([
            'estudiante_id' => $e3[$i]->id,
            'colegio_id' => $e3[$i]->colegio_id,
            'sede_id' => null,
            'grado_id' => $gradoId,
            'tipo_matricula' => 'nueva',
            'estado' => 'activo',
            'fecha_matricula' => now(),
        ]);
        if($i == 10 || $i == 20 || $i == 30 || $i == 40 || $i == 50 || $i == 60 )
        {
            $gradoId++;
        }
    }







    $grupos = [
        ['nombre' => 'Sexto A', 'colegio_id' => 1, 'grado_id' => 7],
        ['nombre' => 'Septimo A', 'colegio_id' => 1, 'grado_id' => 8],
        ['nombre' => 'Octavo A', 'colegio_id' => 1, 'grado_id' => 9],
        ['nombre' => 'Noveno A', 'colegio_id' => 1, 'grado_id' => 10],
        ['nombre' => 'Decimo A', 'colegio_id' => 1, 'grado_id' => 11],
        ['nombre' => 'Once A', 'colegio_id' => 1, 'grado_id' => 12],
        ['nombre' => 'Sexto A', 'colegio_id' => 2, 'grado_id' => 19],
        ['nombre' => 'Septimo A', 'colegio_id' => 2, 'grado_id' => 20],
        ['nombre' => 'Octavo A', 'colegio_id' => 2, 'grado_id' => 21],
        ['nombre' => 'Noveno A', 'colegio_id' => 2, 'grado_id' => 22],
        ['nombre' => 'Decimo A', 'colegio_id' => 2, 'grado_id' => 23],
        ['nombre' => 'Once A', 'colegio_id' => 2, 'grado_id' => 24],
        ['nombre' => 'Sexto A', 'colegio_id' => 3, 'grado_id' => 31],
        ['nombre' => 'Septimo A', 'colegio_id' => 3, 'grado_id' => 32],
        ['nombre' => 'Octavo A', 'colegio_id' => 3, 'grado_id' => 33],
        ['nombre' => 'Noveno A', 'colegio_id' => 3, 'grado_id' => 34],
        ['nombre' => 'Decimo A', 'colegio_id' => 3, 'grado_id' => 35],
        ['nombre' => 'Once A', 'colegio_id' => 3, 'grado_id' => 36],
    ];

    foreach($grupos as $data)
    {
        Grupo::create($data);
    }

    // asignando eestudiantes a grupos
    // recorro 3 ccolegios,
    // ahora consigo todos los eestudiantes y grupos de eese colegio
    // y los agrego
    // for($i = 1;$i<3;$i++)
    // {
    //     $colegio = Colegio::findOrFail($i);
    //     $estudiantes = $colegio->estudiantes;
    //     $grupos = Grupo::where('colegio_id',$colegio->id)->get();
    //     $contador = 1;
    //     $contadorGrupo = 0;
    //     foreach($estudiantes as $estudiante)
    //     {
    //         if($contador == 10)
    //         {
    //             $contador = 1;
    //             $contadorGrupo ++;
    //         }
    //         EstudianteGrupo::create([
    //             'estudiante_id' => $estudiante->id,
    //             'grupo_id' => $grupos[$contadorGrupo]->id,
    //             'colegio_id' => $colegio->id
    //         ]);
    //         $contador++;
    //     }
    // }
    for ($i = 1; $i <= 3; $i++) {
        $colegio = Colegio::findOrFail($i);
        $estudiantes = $colegio->estudiantes;
        $grupos = Grupo::where('colegio_id', $colegio->id)->get()->values(); // reindexar los grupos

        $contador = 0;
        $contadorGrupo = 0;

        foreach ($estudiantes as $estudiante) {
            // Asegurarse de no sobrepasar el número de grupos
            if ($contador == 10) {
                $contador = 0;
                $contadorGrupo++;
            }

            // Verificar que el grupo exista
            if (!isset($grupos[$contadorGrupo])) {
                break; // No hay más grupos, salir del loop
            }

            EstudianteGrupo::create([
                'estudiante_id' => $estudiante->id,
                'grupo_id' => $grupos[$contadorGrupo]->id,
                'colegio_id' => $colegio->id,
            ]);

            $contador++;
        }
    }




    }
}
