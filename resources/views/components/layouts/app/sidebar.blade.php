<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <img src="{{ asset('images/logo.png') }}" alt="">
            </a>


            <flux:navlist variant="outline">
                {{-- Administrador --}}
                @if (Auth::user()->role_id == 1)
                <flux:navlist.group :heading="__('Administrador')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('administrador-principal')" :current="request()->routeIs('administrador-principal')" wire:navigate>{{ __('Panel Principal') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('administrador-usuarios')" :current="request()->routeIs('administrador-usuarios')" wire:navigate>{{ __('Usuarios') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-library" :href="route('administrador-colegios')" :current="request()->routeIs('administrador-colegios')" wire:navigate>{{ __('Colegios') }}</flux:navlist.item>
                    <flux:navlist.item icon="briefcase" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Docentes') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Estudiantes') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Acudientes') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
                {{-- Colegio --}}
                @if (Auth::user()->role_id == 2)
                    {{-- Panel Principal --}}
                    <flux:navlist.group :heading="__('Panel Principal')" class="grid">
                        <flux:navlist.item icon="home" :href="route('colegio-inicio')" :current="request()->routeIs('colegio-inicio')" wire:navigate>{{ __('Inicio') }}</flux:navlist.item>
                    </flux:navlist.group>

                    {{-- Gestión Académica --}}
                    <flux:navlist.group :heading="__('Gestión Académica')" class="grid">
                        <flux:navlist.item icon="user-group" :href="route('colegio-estudiantes')" :current="request()->routeIs('colegio-estudiantes')" wire:navigate>{{ __('Estudiantes') }}</flux:navlist.item>
                        <flux:navlist.item icon="briefcase" :href="route('colegio-docentes')" :current="request()->routeIs('colegio-docentes')" wire:navigate>{{ __('Docentes') }}</flux:navlist.item>
                        <flux:navlist.item icon="numbered-list" :href="route('colegio-grados')" :current="request()->routeIs('colegio-grados')" wire:navigate>{{ __('Grados') }}</flux:navlist.item>
                        <flux:navlist.item icon="pencil-square" :href="route('colegio-notas')" :current="request()->routeIs('colegio-notas')" wire:navigate>{{ __('Notas') }}</flux:navlist.item>
                        <flux:navlist.item icon="document-text" :href="route('colegio-asignaturas')" :current="request()->routeIs('colegio-asignaturas')" wire:navigate>{{ __('Asignaturas') }}</flux:navlist.item>
                        <flux:navlist.item icon="book-open" :href="route('colegio-matriculas')" :current="request()->routeIs('colegio-matriculas')" wire:navigate>{{ __('Matrículas') }}</flux:navlist.item>
                        <flux:navlist.item icon="book-open" :href="route('colegio-grupos')" :current="request()->routeIs('colegio-grupos')" wire:navigate>{{ __('Grupos') }}</flux:navlist.item>
                    </flux:navlist.group>

                    {{-- Asignaciones y Distribuciones --}}
                    <flux:navlist.group :heading="__('Asignaciones')" class="grid">
                        <flux:navlist.item icon="user-circle" :href="route('colegio-docentes-asignaturas')" :current="request()->routeIs('colegio-docentes-asignaturas')" wire:navigate>{{ __('Docentes - Asignaturas') }}</flux:navlist.item>
                        <flux:navlist.item icon="folder-open" :href="route('colegio-estudiantes-grupos')" :current="request()->routeIs('colegio-estudiantes-grupos')" wire:navigate>{{ __('Estudiantes - Grupos') }}</flux:navlist.item>
                        <flux:navlist.item icon="numbered-list" :href="route('colegio-asignaturas-grados')" :current="request()->routeIs('colegio-asignaturas-grados')" wire:navigate>{{ __('Asignaturas - Grados') }}</flux:navlist.item>
                    </flux:navlist.group>

                    {{-- Información y Comunicaciones --}}
                    <flux:navlist.group :heading="__('Información')" class="grid">
                        <flux:navlist.item icon="bell" :href="route('colegio-anuncios')" :current="request()->routeIs('colegio-anuncios')" wire:navigate>{{ __('Anuncios') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="route('colegio-horarios')" :current="request()->routeIs('colegio-horarios')" wire:navigate>{{ __('Horarios') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar-date-range" :href="route('colegio-periodos')" :current="request()->routeIs('colegio-periodos')" wire:navigate>{{ __('Periodos') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif

                {{-- Docente --}}
                @if (Auth::user()->role_id == 3)
                {{-- panel principal --}}
                <flux:navlist.group :heading="__('Panel Principal')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('docente-inicio')" :current="request()->routeIs('docente-inicio')" wire:navigate>{{ __('Panel Principal') }}</flux:navlist.item>
                </flux:navlist.group>
                {{-- Estudiantil --}}
                <flux:navlist.group :heading="__('Estudiantil')" class="grid">
                    <flux:navlist.item icon="pencil-square" :href="route('docente-notas')" :current="request()->routeIs('docente-notas')" wire:navigate>{{ __('Notas') }}</flux:navlist.item>
                    <flux:navlist.item icon="calendar-days" :href="route('docente-asistencias')" :current="request()->routeIs('docente-asistencias')" wire:navigate>{{ __('Asistencias') }}</flux:navlist.item>
                    <flux:navlist.item icon="folder" :href="route('docente-actividades')" :current="request()->routeIs('docente-actividades')" wire:navigate>{{ __('Actividades') }}</flux:navlist.item>
                    <flux:navlist.item icon="folder" :href="route('docente-evaluaciones')" :current="request()->routeIs('docente-evaluaciones')" wire:navigate>{{ __('Evaluaciones') }}</flux:navlist.item>
                </flux:navlist.group>
                {{-- informativo --}}
                <flux:navlist.group :heading="__('Informativo')" class="grid">
                    <flux:navlist.item icon="information-circle" :href="route('docente-anuncios')" :current="request()->routeIs('docente-anuncios')" wire:navigate>{{ __('Anuncios') }}</flux:navlist.item>
                    <flux:navlist.item icon="calendar-days" :href="route('docente-horarios')" :current="request()->routeIs('docente-horarios')" wire:navigate>{{ __('Horarios') }}</flux:navlist.item>
                    <flux:navlist.item icon="identification" :href="route('docente-notas-periodo')" :current="request()->routeIs('docente-notas-periodo')" wire:navigate>{{ __('Notas Periodo') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
                {{-- Estudiante --}}
                @if (Auth::user()->role_id == 4)
                {{-- inicio --}}
                <flux:navlist.group :heading="__('Panel Principal')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('estudiante-inicio')" :current="request()->routeIs('estudiante-inicio')" wire:navigate>{{ __('Inicio') }}</flux:navlist.item>
                </flux:navlist.group>
                {{-- cosas estudiantiles --}}
                <flux:navlist.group :heading="__('Estudiantil')" class="grid">
                    <flux:navlist.item icon="folder" :href="route('estudiante-actividades')" :current="request()->routeIs('estudiante-actividades')" wire:navigate>{{ __('Mis Actividades') }}</flux:navlist.item>
                    <flux:navlist.item icon="envelope" :href="route('estudiante-examenes')" :current="request()->routeIs('estudiante-examenes')" wire:navigate>{{ __('Examenes') }}</flux:navlist.item>
                    <flux:navlist.item icon="chat-bubble-bottom-center-text" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Foros') }}</flux:navlist.item>
                </flux:navlist.group>
                {{-- Informacitivo del estudiante --}}
                <flux:navlist.group :heading="__('Informativo')" class="grid">
                    <flux:navlist.item icon="building-library" :href="route('estudiante-asignaturas')" :current="request()->routeIs('estudiante-asignaturas')" wire:navigate>{{ __('Asignaturas') }}</flux:navlist.item>
                    <flux:navlist.item icon="information-circle" :href="route('estudiante-anuncios')" :current="request()->routeIs('estudiante-anuncios')" wire:navigate>{{ __('Anuncios') }}</flux:navlist.item>
                    <flux:navlist.item icon="briefcase" :href="route('estudiante-docentes')" :current="request()->routeIs('estudiante-docentes')" wire:navigate>{{ __('Docentes') }}</flux:navlist.item>
                    <flux:navlist.item icon="calendar-days" :href="route('estudiante-horarios')" :current="request()->routeIs('estudiante-horarios')" wire:navigate>{{ __('Horario') }}</flux:navlist.item>
                    <flux:navlist.item icon="pencil-square" :href="route('estudiante-notas')" :current="request()->routeIs('estudiante-notas')" wire:navigate>{{ __('Mis Notas') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
                {{-- Acudiente --}}
                @if (Auth::user()->role_id == 5)
                <flux:navlist.group :heading="__('Acudiente')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            {{-- <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist> --}}




            <!-- Desktop User Menu -->
            <flux:dropdown class="cursor-pointer" position="bottom" align="start">
                <flux:profile class="cursor-pointer"
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px] ">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Ajustes') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('Cerrar Sesion') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- scripts de otros lados --}}
        @stack('js')
    </body>
</html>
