<div>
    {{-- nav --}}
    <div class="flex content-between justify-between ">
        {{-- Breadcrumbs --}}
        <flux:breadcrumbs class="">
            <flux:breadcrumbs.item class="hover:underline" href="{{ route('administrador-principal') }}">Panel Principal</flux:breadcrumbs.item>
            <flux:breadcrumbs.item class="hover:underline" href="{{ route('administrador-auditoria') }}">Auditoria</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        {{-- title --}}
        <div>
            <h1 class="text-center  lg:mt-2 sm:mt-3">Auditorias del sistema</h1>
        </div>
        {{-- filters --}}
        <div class="flex ">
            {{-- models --}}
            <div class="">
                <select wire:model.live="modeloSeleccionado" class=" h-12 bg-blue-600 text-white rounded-lg text-sm
                          hover:bg-blue-700 transition cursor-pointer
                          dark:bg-blue-700 dark:hover:bg-blue-800" name="" id="">
                    <option value="">Todos los modelos</option> class="hover:underline"
                </select>
            </div>
        </div>
    </div>
</div>
