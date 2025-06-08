<div class="">
    {{-- Migas de pan --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="#">
                    <a href="{{ route('docente-inicio') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                    </a>
                </flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('docente-evaluaciones') }}">Exámenes</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="#">Ver</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        {{-- Puedes añadir un botón aquí si deseas --}}
        <a href="{{ route('docente-ver-evaluaciones') }}"
        class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition">
            Ver Evaluaciones
        </a>

    </div>

    {{-- Contenido principal --}}
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Crear Nuevo Examen</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">Complete los detalles del examen y añada las preguntas</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <form wire:submit.prevent="saveExam">
                {{-- Sección de información básica --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Información Básica
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Título --}}
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título del Examen</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="text" wire:model="titulo" id="titulo" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: Examen Parcial de Matemáticas">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('titulo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Asignatura --}}
                        <div>
                            <label for="asignatura_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asignatura</label>
                            <div class="relative">
                                <select wire:model.live="asignatura_id" id="asignatura_id" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white dark:bg-gray-700 dark:text-white">
                                    <option value="">Seleccione una asignatura</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('asignatura_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Grupo --}}
                        <div>
                            <label for="grupo_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grupo</label>
                            <div class="relative">
                                <select wire:model="grupo_id" id="grupo_id" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white dark:bg-gray-700 dark:text-white">
                                    <option value="">Seleccione un grupo</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('grupo_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Fecha Límite --}}
                        <div>
                            <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha Límite</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="datetime-local" wire:model="fecha_vencimiento" id="fecha_vencimiento" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            @error('fecha_vencimiento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Configuración del examen --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Tiempo Límite --}}
                        <div>
                            <label for="tiempo_limite" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiempo Límite (minutos)</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" wire:model="tiempo_limite" id="tiempo_limite" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: 60">
                                <div class="absolute inset-y-0 right-5 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('tiempo_limite') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Puntaje Total --}}
                        <div>
                            <label for="puntaje_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Puntaje Total</label>
                            <div class="relative rounded-md shadow-sm">
                                <input disabled type="number" wire:model="puntaje_total" id="puntaje_total" class="block disabled cursor-not-allowed bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Ej: 100">
                                <div class="absolute inset-y-0 right-5 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('puntaje_total') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Preguntas --}}
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white flex items-center">
                            <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Preguntas
                        </h2>
                        <button type="button" wire:click="addQuestion" class="inline-flex cursor-pointer items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Añadir Pregunta
                        </button>
                    </div>

                    <div class="space-y-6">
                        @foreach($questions as $index => $question)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Pregunta #{{ $index + 1 }}</h3>
                                    <button type="button" wire:click="removeQuestion({{ $index }})" class="text-red-500 cursor-pointer hover:text-red-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-6 space-y-4 bg-white dark:bg-gray-800">
                                    {{-- Enunciado --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Enunciado</label>
                                        <textarea wire:model="questions.{{ $index }}.text" rows="3" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Escriba la pregunta aquí..."></textarea>
                                        @error('questions.'.$index.'.text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Tipo y Puntos --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Pregunta</label>
                                            <select wire:model.live="questions.{{ $index }}.type" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                                <option value="multiple_choice">Selección múltiple</option>
                                                <option value="true_false">Verdadero/Falso</option>
                                                {{-- <option value="short_answer">Respuesta corta</option>
                                                <option value="essay">Ensayo</option> --}}
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Puntos</label>
                                            <input type="number" wire:model="questions.{{ $index }}.points" min="1" class="block bg-white text-black w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                            @error('questions.'.$index.'.points') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    {{-- Opciones según tipo --}}
                                    @if($question['type'] === 'multiple_choice')
                                        <div class="mt-4">
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opciones</label>
                                                <button type="button" wire:click="addOption({{ $index }})" class="text-sm cursor-pointer text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium flex items-center">
                                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Añadir Opción
                                                </button>
                                            </div>

                                            <div class="space-y-3">
                                                @foreach($question['options'] as $optIndex => $option)
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex items-center h-5 mt-2">
                                                            <input type="radio" wire:model="questions.{{ $index }}.correct_option" value="{{ $optIndex }}" class="h-4 bg-white text-black w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                                        </div>
                                                        <div class="flex-1">
                                                            <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}" class="block bg-white text-black w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" placeholder="Texto de la opción">
                                                        </div>
                                                        <button type="button" wire:click="removeOption({{ $index }}, {{ $optIndex }})" class="text-red-500 cursor-pointer hover:text-red-700 mt-2">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($question['type'] === 'true_false')
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Respuesta correcta</label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <label class="inline-flex items-center px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <input type="radio" wire:model="questions.{{ $index }}.correct_answer" value="true" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-3 text-gray-700 dark:text-gray-300">Verdadero</span>
                                                </label>
                                                <label class="inline-flex items-center px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <input type="radio" wire:model="questions.{{ $index }}.correct_answer" value="false" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="ml-3 text-gray-700 dark:text-gray-300">Falso</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="bg-gray-50 dark:bg-gray-700 cursor-pointer px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex cursor-pointer justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Guardar Examen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
