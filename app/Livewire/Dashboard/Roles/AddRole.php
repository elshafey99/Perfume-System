<?php

namespace App\Livewire\Dashboard\Roles;

use Livewire\Component;
use App\Models\Role;

class AddRole extends Component
{
    public $role = [
        'en' => '',
        'ar' => '',
    ];

    public $permession = [];

    public function submit()
    {
        $this->validate([
            'role.en' => 'required|string|max:150',
            'role.ar' => 'required|string|max:150',
            'permession' => 'required|array|min:1',
        ]);

        Role::create([
            'role' => $this->role,
            'permession' => json_encode($this->permession),
        ]);

        $this->dispatch('createModalToggle');
        $this->dispatch('success', __('dashboard.item_created_successfully'));
        $this->dispatch('refreshData')->to(Roles::class);

        // Reset inputs
        $this->role = ['en' => '', 'ar' => ''];
        $this->permession = [];
    }

    public function render()
    {
        return view('dashboard.roles.add-role');
    }
}
