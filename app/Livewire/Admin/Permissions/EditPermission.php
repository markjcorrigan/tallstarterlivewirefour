<?php

namespace App\Livewire\Admin\Permissions;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class EditPermission extends Component
{
    

    public Permission $permission;

    #[Validate('required|string|max:255')]
    public string $name = '';

    public function mount(Permission $permission): void
    {
        $this->authorize('update permissions');

        $this->permission = $permission;
        $this->name = $permission->name;
    }

    public function savePermission(): void
    {
        $this->authorize('update permissions');

        $this->validate();

        $this->permission->update([
            'name' => $this->name,
        ]);

        session()->flash('alert', ['type' => 'success', 'message' => __('permissions.permission_updated')]);

        $this->redirect(route('admin.permissions.index'), true);

    }

    #[Layout('components.layouts.admin')]
    public function render(): View
    {
        return view('livewire.admin.permissions.edit-permission');
    }
}
