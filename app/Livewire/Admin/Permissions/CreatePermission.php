<?php

namespace App\Livewire\Admin\Permissions;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreatePermission extends Component
{
    

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function createPermission(): void
    {
        $this->authorize('create permissions');

        $this->validate();

        Permission::create([
            'name' => $this->name,
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => __('permissions.permission_created')]);

        $this->redirect(route('admin.permissions.index'), true);

    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.permissions.create-permission');
    }
}
