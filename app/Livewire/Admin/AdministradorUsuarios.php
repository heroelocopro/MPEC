<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdministradorUsuarios extends Component
{
    protected $listeners = ['deleteUser' => 'eliminarUsuario'];
    use WithPagination;
    public $sortField = 'name'; // Campo por defecto para ordenar
    public $sortDirection = 'asc'; // Dirección por defecto
    // pasos
    public $steps;
    // buscador
    public $paginacion;
    public $buscador;
    public $currentStep = 1;
    public $totalSteps = 1; // Total de pasos en el wizard

    // Datos del formulario
    public $usuario;
    public $name;
    public $email;
    public $password;
    public $role_id;
    // public $dateOfBirth;
    // public $phone;
    // public $address;
    // public $bio;
    // modal
    public $createModal = false;
    public $editModal = false;

// Método optimizado para limpiar el formulario
public function limpiarForm()
{
    $this->reset([
        'name',
        'email',
        'password',
        'role_id',
        'usuario'
    ]);

    // Limpiar errores de validación
    $this->resetErrorBag();

}

// Escuchar cambios en ambos modales
// public function updatedCreateModal($value)
// {
//     if ($value === true) {
//         $this->limpiarForm();
//     }
// }

public function updatedEditModal($value)
{
    if ($value === false) {
        $this->limpiarForm();
    }
}

    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validateCurrentStep();

        // Aquí procesas los datos del formulario
        // Ejemplo: User::update([...]);

        $this->crearUsuario();

        // Cerrar el modal después de guardar
        $this->dispatch('closeModal', 'create-user');
        $this->createModal = false;
    }

    protected function validateCurrentStep()
    {
        $rules = [];

        switch ($this->currentStep) {
            case 1:
                $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|string|max:255',
                    'password' => 'required|string|max:255',
                    'role_id' => 'required|integer|max:255',
                ];
                break;
            case 2:
                $rules = [
                    'email' => 'required|email|max:255',
                    'phone' => 'required|string|max:20',
                ];
                break;
            case 3:
                $rules = [
                    'address' => 'required|string|max:255',
                    'bio' => 'nullable|string|max:500',
                ];
                break;
        }

        $this->validate($rules);
    }


    public function crearUsuario()
    {
        try {
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role_id' => $this->role_id,
        ]);

        $this->dispatch('closeModal', 'create-user');
        $data = [
            'title' => 'Usuario Creado!',
            'text' => 'OK',
            'icon' => 'success'
        ];
        $this->dispatch('alerta',$data);
    } catch (\Exception $e) {
        Log::error("Error en User::create: " . $e->getMessage());
        $data = [
            'title' => 'Error!',
            'text' => 'No se pudo crear el usuario. Contacta al soporte.',
            'icon' => 'error'
        ];
        $this->dispatch('alerta',$data);
    }
    }

    public function cargarUsuarios($id)
    {
        $usuario = User::findOrFail($id);
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->password = $usuario->password;
        $this->role_id = $usuario->role_id;
        $this->editModal = true;
        $this->usuario = $usuario;
    }

    public function editarUsuario()
{
    try {
        $this->usuario->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role_id' => $this->role_id
        ]);

        // Cierra el modal estableciendo la propiedad a false
        $this->editModal = false;

        $data = [
            'title' => 'Usuario Actualizado!',
            'text' => 'Se actualizó con éxito!',
            'icon' => 'success'
        ];
        $this->dispatch('alerta', $data);
        $this->limpiarForm();
    } catch (\Exception $e) {
        Log::error("Error al actualizar usuario: " . $e->getMessage());
        $data = [
            'title' => 'Error!',
            'text' => 'No se pudo actualizar el usuario. Contacta al soporte.',
            'icon' => 'error'
        ];
        $this->dispatch('alerta', $data);
    }
}

    public function confirmarEliminarUsuario($id)
    {
        $this->dispatch('confirmarEliminarUsuario', $id);
    }
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
         $data = [
            'title' => 'Usuario Eliminado!',
            'text' => 'Se borro el usuario con exito!',
            'icon' => 'success'
        ];
        $this->dispatch('alerta',$data);
        $this->render();
    }

    public function sortBy($field)
{
    if ($this->sortField === $field) {
        // Si ya estamos ordenando por este campo, invertir la dirección
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        // Si es un campo nuevo, ordenar ascendente por defecto
        $this->sortDirection = 'asc';
    }

    $this->sortField = $field;
}



    public function mount()
    {
        $this->paginacion = 10;
        $this->buscador = '';
        $this->steps = 1;
        $this->createModal = false;
    }

    public function render()
    {
        $roles = Role::all();
        $usuarios = User::query()
        ->where('name', 'like', '%'. $this->buscador .'%')
        ->orWhere('email', 'like', '%'. $this->buscador .'%')
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->paginacion);
        return view('livewire.admin.administrador-usuarios', compact('usuarios','roles'));
    }
}
