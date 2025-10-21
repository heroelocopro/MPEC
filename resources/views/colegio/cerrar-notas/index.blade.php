<x-layouts.app :title="env('APP_NAME')">
    <div class="flex flex-col items-center justify-center min-h-[60vh] gap-6">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Cierre de Notas del Periodo</h1>

        {{-- Mensaje de éxito o error --}}
        @if(session('mensaje'))
            <div
                class="px-4 py-2 rounded-lg text-sm font-semibold shadow-md
                {{ session('tipo') === 'ok' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' }}">
                {{ session('mensaje') }}
            </div>
        @endif

        {{-- 🔘 Botón para cierre manual de notas --}}
        @if(isset($periodo) && $periodo->estado === 'activo')
            @php
                $diasRestantes = now()->diffInDays($periodo->fecha_fin, false);
            @endphp

            @if($diasRestantes === 1)
                <div class="text-yellow-600 dark:text-yellow-400 text-sm mb-2">
                    ⚠️ El periodo <strong>{{ $periodo->nombre }}</strong> se cerrará automáticamente mañana.
                </div>
            @elseif($diasRestantes <= 0)
                <div class="text-red-600 dark:text-red-400 text-sm mb-2">
                    🔒 El periodo <strong>{{ $periodo->nombre }}</strong> ya está vencido. Puedes cerrarlo manualmente.
                </div>
            @endif

            <form action="{{ route('colegio-cerrar-notas') }}" method="POST" onsubmit="return confirmarCierre()">
                @csrf
                <input type="hidden" name="periodo_id" value="{{ $periodo->id }}">
                <button
                    type="submit"
                    class="px-6 cursor-pointer py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg text-base font-semibold shadow-md transition-all">
                    🔒 Cerrar Notas del Periodo
                </button>
            </form>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                No hay periodos activos disponibles para cerrar.
            </p>
        @endif
    </div>

    {{-- Script para confirmar el cierre --}}
    <script>
        function confirmarCierre() {
            return confirm('⚠️ ¿Seguro que deseas cerrar las notas del periodo? Esta acción no se puede deshacer.');
        }
    </script>
</x-layouts.app>
