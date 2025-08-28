<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class UserList extends Component
{
    use WithPagination;

    // --- PROPIEDADES PARA FILTROS Y BÚSQUEDA ---
    public string $search = '';
    public string $role = '';

    // --- PROPIEDADES PARA ELIMINACIÓN ---
    public bool $confirmingUserDeletion = false;
    public ?User $userToDelete = null;

    // --- PROPIEDADES PARA EDICIÓN ---
    public bool $isEditing = false;
    public ?User $userToEdit = null;
    public array $editingState = []; // Usamos un array para el estado del formulario de edición

    /**
     * Resetea la paginación cada vez que se modifica un filtro.
     */
    public function updatingSearch() { $this->resetPage(); }
    public function updatingRole() { $this->resetPage(); }

    /**
     * Prepara y muestra el modal de confirmación para eliminar un usuario.
     */
    public function confirmDelete(int $userId)
    {
        $this->userToDelete = User::findOrFail($userId);
        $this->confirmingUserDeletion = true;
    }
    
    /**
     * Cierra el modal de eliminación y resetea el estado.
     */
    public function cancelDelete()
    {
        $this->userToDelete = null;
        $this->confirmingUserDeletion = false;
    }

    /**
     * Elimina el usuario seleccionado.
     */
    public function delete()
    {
        if ($this->userToDelete) {
            if ($this->userToDelete->id === Auth::id() || $this->userToDelete->hasRole('SuperAdmin')) {
                $this->cancelDelete();
                return;
            }

            $userName = $this->userToDelete->name;
            $this->userToDelete->delete();
            
            $this->cancelDelete();
            
            $this->dispatch('show-toast', ['message' => "Usuario '{$userName}' eliminado con éxito."]);
        }
    }

    /**
     * Prepara el modal de edición con los datos del usuario seleccionado.
     */
    public function edit(User $user)
    {
        $this->isEditing = true;
        $this->userToEdit = $user;
        $this->editingState = [
            'name' => $user->name,
            'email' => $user->email,
            'dni' => $user->dni,
            'role' => $user->roles->first()->name ?? '', // Asume que los usuarios tienen un solo rol
        ];
    }

    /**
     * Valida y guarda los cambios realizados en el formulario de edición.
     */
    public function save()
    {
        // Reglas de validación para el formulario de edición
        $validatedData = $this->validate([
            'editingState.name' => ['required', 'string', 'max:255'],
            'editingState.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userToEdit->id)],
            'editingState.dni' => ['required', 'string', 'digits:8', Rule::unique('users', 'dni')->ignore($this->userToEdit->id)],
            'editingState.role' => ['required', 'exists:roles,name'],
        ]);

        // Actualiza los datos del usuario
        $this->userToEdit->update([
            'name' => $validatedData['editingState']['name'],
            'email' => $validatedData['editingState']['email'],
            'dni' => $validatedData['editingState']['dni'],
        ]);

        // Sincroniza el rol del usuario
        $this->userToEdit->syncRoles($validatedData['editingState']['role']);

        // Cierra el modal, resetea el estado y envía una notificación de éxito
        $this->cancelEdit();
        $this->dispatch('show-toast', ['message' => 'Usuario actualizado con éxito.']);
    }

    /**
     * Cierra el modal de edición y resetea el estado y los errores de validación.
     */
    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->userToEdit = null;
        $this->editingState = [];
        $this->resetErrorBag(); // Limpia los mensajes de error de validación previos
    }

    /**
     * Renderiza el componente con los datos necesarios para la vista.
     */
    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->role);
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('livewire.admin.users.user-list', [
            'users' => $users,
            'roles' => Role::pluck('name')->sort() // Pasa todos los roles disponibles a la vista
        ]);
    }
}