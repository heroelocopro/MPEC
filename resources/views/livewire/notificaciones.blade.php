<div class="relative">
    <!-- Icono de campana -->
    <button wire:click="$toggle('open')" class="relative cursor-pointer">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                   6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C8.67 6.165
                   8 7.388 8 9v5.159c0 .538-.214 1.055-.595
                   1.436L6 17h5m0 0v1a3 3 0 006 0v-1m-6 0H9" />
        </svg>
        @if($noLeidas > 0)
            <span class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1.5 rounded-full">
                {{ $noLeidas }}
            </span>
        @endif
    </button>

    @if($open)
    <!-- Panel de notificaciones -->
    <div class="absolute z-50 mt-2 right-0 bg-white shadow-xl rounded-md w-80">
        <div class="p-3 font-bold border-b">Notificaciones</div>

        @forelse ($notificaciones as $noti)
            <div class="px-4 py-2 hover:bg-gray-100 flex justify-between items-center">
                <a  href="{{ $noti->data['url'] ?? '#' }}"
                    class="text-sm text-blue-600 hover:underline block">
                     {{ $noti->data['titulo'] ?? 'Tienes una nueva notificación.' }}
                    </a>

                @if (is_null($noti->read_at))
                    <a wire:click="marcarComoLeida('{{ $noti->id }}')" class="text-xs cursor-pointer hover:underline text-decoration-underline text-blue-500">
                        Marcar como leída
                    </a>
                @endif
            </div>
        @empty
            <div class="p-4 text-gray-500 text-sm">No hay notificaciones.</div>
        @endforelse

        @if($noLeidas > 0)
            <button wire:click="marcarTodasComoLeidas" class="w-full text-center py-2 bg-gray-100 hover:bg-gray-200 text-sm">
                Marcar todas como leídas
            </button>
        @endif
    </div>
    @endif
</div>
