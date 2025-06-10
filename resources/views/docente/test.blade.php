<x-layouts.app :title="__('Tests | Docente')">
    @foreach ($test as $matricula )
        {{ $matricula->estudiante->notas }} <hr>
    @endforeach
</x-layouts.app>
