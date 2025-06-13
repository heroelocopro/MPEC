<div>
    {{-- Parte Superior --}}
    <div class="flex items-center justify-between mb-6">
        {{-- Migas de pan --}}
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">Panel Principal</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="">Examen</flux:breadcrumbs.item>
                @isset($colegio)
                <flux:breadcrumbs.item>{{ $colegio->nombre }}</flux:breadcrumbs.item>
                @endisset
                @isset($grupo)
                <flux:breadcrumbs.item>{{ $grupo->nombre }}</flux:breadcrumbs.item>
                @endisset
            </flux:breadcrumbs>
        </div>
    </div>

    <div class="min-h-screen py-8 px-4 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Encabezado -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-blue-800 dark:text-blue-300">📝 Examen: {{ $examen->titulo }}</h1>
                    @php
                        use Carbon\CarbonInterval;

                        $minutos = CarbonInterval::createFromFormat('H:i:s', $examen->tiempo_limite)->totalMinutes;
                    @endphp
                    <p class="text-sm text-gray-600 dark:text-gray-400">Duración: {{ $minutos }} minutos</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow px-4 py-2 rounded-lg border border-blue-300 dark:border-blue-600">
                    ⏱️ <span class="font-bold text-lg text-red-600" wire:poll.1000ms="actualizarContador">{{ $contador }}</span>
                </div>
            </div>

            <!-- Advertencias -->
            <div class="bg-yellow-100 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 p-4 rounded-lg">
                <p><strong>⚠️ Importante:</strong> No recargues la página ni cierres el navegador mientras estás en el examen.</p>
            </div>

            <!-- Preguntas -->
            <div class="space-y-6">
                @foreach ($preguntas as $index => $pregunta)
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">
                        <h2 class="font-semibold text-lg text-blue-700 dark:text-blue-300">Pregunta {{ $index + 1 }}:</h2>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $pregunta->pregunta }}</p>

                        <div class="mt-4 space-y-2">
                            @foreach ($pregunta->opciones as $opcion)
                                <label class="flex items-center space-x-2">
                                    <input type="radio" wire:model="respuestas.{{ $pregunta->id }}" value="{{ $opcion }}"
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-800 dark:text-gray-200">{{ $opcion }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error("respuestas.{$pregunta->id}")
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </div>

            <!-- Botón de envío -->
            <div class="text-center mt-8">
                <button wire:click="enviarRespuestas"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition cursor-pointer">
                    Enviar Examen
                </button>
            </div>
        </div>
    </div>


    {{-- js --}}
@push('js')
    <script>
        Livewire.on('alerta', (data) => {
            data = data[0];
            Swal.fire({
                title: data['title'],
                text: data['text'],
                icon: data['icon'],
                toast: data['toast'],
                position: data['position'],
            });
        });
    </script>
@endpush

</div>
