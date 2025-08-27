<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class UserList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $role = '';
    public bool $confirmingUserDeletion = false; // Controla el renderizado del modal
    public ?User $userToDelete = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRole() { $this->resetPage(); }

    public function confirmDelete(int $userId)
    {
        $this->userToDelete = User::findOrFail($userId);
        $this->confirmingUserDeletion = true; // Renderiza el modal
    }

    public function cancelDelete()
    {
        $this->userToDelete = null;
        $this->confirmingUserDeletion = false; // Remueve el modal del DOM
    }

    public function delete()
    {
        if ($this->userToDelete) {
            if ($this->userToDelete->id === Auth::id() || $this->userToDelete->hasRole('SuperAdmin')) {
                $this->cancelDelete();
                return;
            }

            $userName = $this->userToDelete->name;
            $this->userToDelete->delete();
            $this->userToDelete = null;
            $this->confirmingUserDeletion = false; // Cierra después de eliminar
            
            $this->dispatch('show-toast', ['message' => "Usuario '{$userName}' eliminado con éxito."]);
        }
    }

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
            'roles' => Role::pluck('name')->sort()
        ]);
    }
}