<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleSwitcher extends Component
{
    public function switchRole($roleName)
    {
        $user = auth()->user();
        
        // Remove all current roles
        $user->syncRoles([]);
        
        // Assign the new role
        $user->assignRole($roleName);
        
        // Flash a message
        session()->flash('message', "Role switched to {$roleName}");
        
        // Redirect to dashboard to see changes
        return redirect()->route('dashboard');
    }

    public function render()
    {
        $roles = Role::all();
        $currentRole = auth()->user()->roles->first()?->name ?? 'none';
        
        return view('livewire.role-switcher', compact('roles', 'currentRole'));
    }
}

