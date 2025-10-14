<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserManager extends Component {
    public $user;
    public $userRoles = [];
    public $userPermissions = [];
    public $roles;
    public $permissions;
    public $activity = [];

    public function mount(User $user) {
        $this->user = $user;
        $this->roles = Role::all();
        $this->permissions = Permission::all();

        $this->userRoles = $user->roles->pluck('name')->toArray();
        $this->userPermissions = $user->permissions->pluck('name')->toArray();
        //$this->activity = $user->activities()->latest()->take(5)->get() ?? [];
    }

    public function save() {
        $this->user->save();
        $this->user->syncRoles($this->userRoles);
        $this->user->syncPermissions($this->userPermissions);
        session()->flash('success', 'User updated successfully!');
    }

    public function resetForm() {
        $this->mount($this->user);
    }

    public function render() {
        return view('livewire.user-manager');
    }
}
