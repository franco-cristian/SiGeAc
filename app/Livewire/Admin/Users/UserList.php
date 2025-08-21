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
    public ?User $userToDelete = null;

    public bool $confirmingUserDeletion = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingRole() { $this->resetPage(); }

    public function confirmDelete(int $userId)
    {
        // Buscamos al usuario explícitamente. findOrFail lanzará un error si no lo encuentra.
        $this->userToDelete = User::findOrFail($userId);
        $this->confirmingUserDeletion = true;
    }
    
    public function cancelDelete()
    {
        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
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
            
            $this->cancelDelete();
            
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