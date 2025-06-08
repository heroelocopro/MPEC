<div>
    {{-- migajas de pan --}}


<nav class="flex" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
    <li class="inline-flex items-center">
      <a href="{{ route('administrador-principal') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
        </svg>
        Administrador
      </a>
    </li>
    <li>
      <div class="flex items-center">
        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
        <a href="{{ route('administrador-usuarios') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Usuarios</a>
      </div>
    </li>
  </ol>
</nav>

    {{-- fin de migajas de pan --}}
    <br>
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl bg-white dark:bg-gray-800">
    {{-- filtros --}}
    <div class="flex items-center gap-2 w-full">
        <!-- Select (10%) - Versión corregida -->
        <select wire:model.live="paginacion"
                class="w-[10%] h-12 px-3 rounded-lg border border-gray-300 bg-white text-gray-700
                       dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300
                       focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>

        <!-- Input (80%) - Versión corregida -->
        <input wire:model.live.debounce.250ms="buscador"
               type="text"
               placeholder="Nombre del usuario"
               class="w-[80%] h-12 px-4 rounded-lg border border-gray-300 bg-white text-gray-700
                      dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400
                      focus:outline-none focus:ring-2 focus:ring-blue-500">

        <!-- Botón (10%) -->
        <flux:modal.trigger wire:click="$set('createModal',true)" name="create-user">
            <button class="w-[10%] h-12 bg-blue-600 text-white rounded-lg text-sm
                          hover:bg-blue-700 transition cursor-pointer
                          dark:bg-blue-700 dark:hover:bg-blue-800">
                Crear usuario
            </button>
        </flux:modal.trigger>
    </div>

    {{-- tabla --}}
    <div class="overflow-x-auto rounded-lg shadow-md dark:shadow-none">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
    <tr>
        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer"
            wire:click="sortBy('id')">
            ID
            @if($sortField === 'id')
                @if($sortDirection === 'asc')
                    ↑
                @else
                    ↓
                @endif
            @endif
        </th>
        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer"
            wire:click="sortBy('name')">
            Nombre
            @if($sortField === 'name')
                @if($sortDirection === 'asc')
                    ↑
                @else
                    ↓
                @endif
            @endif
        </th>
        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer"
            wire:click="sortBy('email')">
            Correo electrónico
            @if($sortField === 'email')
                @if($sortDirection === 'asc')
                    ↑
                @else
                    ↓
                @endif
            @endif
        </th>
        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 cursor-pointer"
            wire:click="sortBy('role_id')">
            Role
            @if($sortField === 'role_id')
                @if($sortDirection === 'asc')
                    ↑
                @else
                    ↓
                @endif
            @endif
        </th>
        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Acciones</th>
    </tr>
</thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @if (!empty($usuarios) && isset($usuarios))
                    @foreach ($usuarios as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{$user->id}}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $user->role->nombre }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <button wire:click="cargarUsuarios({{ $user->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs
                                         dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer">
                                Editar
                            </button>
                            <button wire:click="confirmarEliminarUsuario({{ $user->id }})"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs
                                           dark:bg-red-600 dark:hover:bg-red-700 cursor-pointer">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
    {{-- paginacion --}}
    @if ($usuarios->hasPages())
        {{ $usuarios->links() }}
    @endif

{{-- modal inicio Create --}}

<flux:modal  wire:model.self="createModal" name="create-user" class="md:w-96">
    <div class="space-y-6">
        <!-- Progress indicator -->
        <div class="flex justify-between items-center mb-6">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex-1 flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            {{ $i < $currentStep ? 'bg-green-500 text-white' :
                               ($i == $currentStep ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                            {{ $i }}
                        </div>
                        @if($i < $totalSteps)
                            <div class="h-1 w-8 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>

        <!-- Step 1: Información básica -->
        @if($currentStep == 1)
            <div>
                <flux:heading size="lg">Datos Basicos</flux:heading>
                {{-- <flux:text class="mt-2">Verifica.</flux:text> --}}
            </div>
                {{-- Nombre --}}
            <flux:input wire:model="name" label="Nombre" placeholder="Nombre" />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            {{-- Email --}}
            <flux:input wire:model="email" label="Email" placeholder="Email" />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            {{-- Contrasena --}}
            <flux:input wire:model="password" label="Contrasena" placeholder="Contrasena" />
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            {{-- Role --}}
            <flux:select wire:model="role_id" label="Role">
                @if (!empty($roles))
                <flux:select.option>
                    Selecciona un Rol
                </flux:select.option>
                @foreach ($roles as $role )
                <flux:select.option value="{{ $role->id }}">
                    {{ $role->nombre }}
                </flux:select.option>
                @endforeach
                @endif
            </flux:select>
            @error('role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            {{-- <flux:input wire:model="dateOfBirth" label="Date of birth" type="date" />
            @error('dateOfBirth') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
        @endif

        <!-- Step 2: Contacto -->
        @if($currentStep == 2)
            <div>
                <flux:heading size="lg">Contact Information</flux:heading>
                <flux:text class="mt-2">How can we reach you?</flux:text>
            </div>

            <flux:input wire:model="email" label="Email" type="email" placeholder="your@email.com" />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <flux:input wire:model="phone" label="Phone Number" placeholder="+1 (555) 123-4567" />
            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        @endif

        <!-- Step 3: Información adicional -->
        @if($currentStep == 3)
            <div>
                <flux:heading size="lg">Additional Information</flux:heading>
                <flux:text class="mt-2">Tell us more about you.</flux:text>
            </div>

            <flux:input wire:model="address" label="Address" placeholder="Your address" />
            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <flux:textarea wire:model="bio" label="Bio" placeholder="A short description about yourself" rows="3" />
            @error('bio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        @endif

        <!-- Navigation buttons -->
        <div class="flex justify-between pt-4">
            @if($currentStep > 1)
                <flux:button wire:click="prevStep" >Previous</flux:button>
            @else
                <div></div> <!-- Espacio vacío para mantener el justify-between -->
            @endif

            @if($currentStep < $totalSteps)
                <flux:button wire:click="nextStep" >Next</flux:button>
            @else
                <flux:button class="cursor-pointer" wire:click="submit" >Guardar</flux:button>
            @endif
        </div>
    </div>
</flux:modal>

{{-- modal inicio editar --}}
{{-- Modal de edición - Versión corregida --}}
<flux:modal name="edit-user" wire:model="editModal" class="md:w-96">
    <div class="space-y-6">
        <!-- Progress indicator -->
        <div class="flex justify-between items-center mb-6">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex-1 flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                            {{ $i < $currentStep ? 'bg-green-500 text-white' :
                               ($i == $currentStep ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600') }}">
                            {{ $i }}
                        </div>
                        @if($i < $totalSteps)
                            <div class="h-1 w-8 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>

        <!-- Contenido del formulario de edición -->
        @if($currentStep == 1)
            <div>
                <flux:heading size="lg">Datos Básicos</flux:heading>
            </div>

            <flux:input wire:model="name" label="Nombre" placeholder="Nombre" />
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <flux:input wire:model="email" label="Email" placeholder="Email" />
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <flux:input wire:model="password" label="Contraseña" placeholder="Dejar vacío para no cambiar" type="password" />
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <flux:select wire:model="role_id" label="Rol">
                @foreach ($roles as $role)
                <flux:select.option value="{{ $role->id }}">
                    {{ $role->nombre }}
                </flux:select.option>
                @endforeach
            </flux:select>
            @error('role_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        @endif

        <!-- Botón de acción -->
        <div class="flex justify-end pt-4">
            <button wire:click="editarUsuario"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Guardar Cambios
            </button>
        </div>
    </div>
</flux:modal>
<!-- Script para manejar la confirmación -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('confirmarEliminarUsuario', (id) => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esto no se puede deshacer!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, elimínalo!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteUser',id);
                    }
                });
            });
        });
    </script>


{{-- script para escuchar alertas --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('alerta', (data) => {
            data = data[0];
            Swal.fire({
                title: data['title'],
                text: data['text'],
                icon: data['icon'],
            })
        });
    });
</script>
</div>
