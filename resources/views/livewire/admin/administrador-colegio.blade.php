<div class="space-y-6">
    {{-- Migas de pan --}}
    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Información</flux:breadcrumbs.item>
            @isset($colegio)
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
            @endisset
        </flux:breadcrumbs>
    </div>

    {{-- Título --}}
    <h1 class="text-3xl text-center font-bold text-gray-800 dark:text-gray-100">
        Estadísticas de {{ $colegio->nombre }}
    </h1>

    {{-- Resumen general --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-stat-card label="Total Estudiantes" :value="$totalEstudiantes" icon="students" />
        <x-stat-card label="Total Matriculados" :value="$totalMatriculados" icon="students" />
        <x-stat-card label="Profesores" :value="$totalProfesores" icon="teachers" />
        <x-stat-card label="Materias" :value="$totalMaterias" icon="subjects" />
        <x-stat-card label="Grupos" :value="$totalGrupos" icon="groups" />
        <x-stat-card label="Grados" :value="$totalGrados" icon="grades" />
        <x-stat-card label="Asistencias" :value="$totalAsistencias" icon="attendance" />
    </div>

    {{-- Promedios de Notas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <x-stat-card label="Total Estudiantes Primaria" :value="$totalEstudiantesPrimaria" icon="students" />
        <x-stat-card label="Promedio Notas (Grados < 6°)" :value="$promedioInferiores" icon="average"/>
        <x-stat-card label="Total Estudiantes Secundaria" :value="$totalEstudiantesSecundaria" icon="students" />
        <x-stat-card label="Promedio Notas (6° a 11°)" :value="$promedioSuperiores" icon="average"/>
    </div>
</div>
