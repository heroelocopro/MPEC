<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Notas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 30px;
            color: #333;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 28px;
            color: #1e3a8a;
            margin-bottom: 0;
        }

        .student-info {
            margin-bottom: 30px;
            font-size: 14px;
        }

        .student-info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background-color: #1e3a8a;
            color: #fff;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .aprobado {
            color: green;
            font-weight: bold;
        }

        .reprobado {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1> {{ $plataforma }} - Reporte de Notas</h1>
    </header>

    <div class="student-info">
        <p><strong>Estudiante:</strong> {{ $estudiante->nombre_completo }}</p>
        <p><strong>Documento:</strong> {{ $estudiante->documento }}</p>
        <p><strong>Grupo:</strong> {{ $grupo->nombre }}</p>
        <p><strong>Colegio:</strong> {{ $colegio->nombre }}</p>
        <p><strong>Período:</strong> {{ $periodo->nombre }} ({{ $periodo->fecha_inicio->format('d/m/Y') }} - {{ $periodo->fecha_fin->format('d/m/Y') }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Asignatura</th>
                <th>Nota Final</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notas as $index => $asignatura)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $asignatura->nombre }}</td>
                    <td>{{ number_format($asignatura->nota_final, 2) }}</td>
                    <td>
                        @if ($asignatura->nota_final >= $notaMinima)
                            <span class="aprobado">Aprobado</span>
                        @else
                            <span class="reprobado">Reprobado</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    documento creado el {{ $fecha }}
</body>
</html>
