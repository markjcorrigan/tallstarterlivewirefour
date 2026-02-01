<?php

namespace App\Livewire\Admin\Roles;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    

    #[Validate('required|string|max:255')]
    public string $name = '';

    /** @var array<mixed> */
    #[Validate('array|min:1')]
    public array $selectedPermissions = [];

    public function mount(): void
    {
        $this->authorize('create roles');
    }

    public function createRole(): void
    {
        $this->authorize('create roles');

        $this->validate();

        $role = Role::create([
            'name' => $this->name,
        ]);

        $permissions = collect($this->selectedPermissions)->map(fn ($permission): int =>
            // convert string to int
        (int) $permission)->toArray();

        $role->syncPermissions($permissions);

        session()->flash('alert', ['type' => 'success', 'message' => __('roles.role_created')]);

        $this->redirect(route('admin.roles.index'), true);

    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.roles.create-role', [
            'permissions' => Permission::all(),
        ]);
    }
}
