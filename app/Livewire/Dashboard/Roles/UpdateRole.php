<?php

namespace App\Livewire\Dashboard\Roles;

use Livewire\Component;
use App\Models\Role;

class UpdateRole extends Component
{
    public $role = [
        'en' => '',
        'ar' => '',
    ];

    public $permession = [];

    public $role_id;

    protected $listeners = ['editItem'];

    protected $rules = [
        'role.en' => 'required|string|max:255',
        'role.ar' => 'required|string|max:255',
        'permession' => 'required|array|min:1',
    ];

    public function editItem($id)
    {
        $role = Role::find($id);

        if (!$role) {
            $this->dispatch('cannotUpdateRole');
            return;
        }

        $this->role_id = $role->id;
        $this->role = $role->getTranslations('role');
        $this->permession = json_decode($role->permession, true) ?? [];

        $this->dispatch('updateModalToggle');
    }


    public function submit()
    {
        $role = Role::find($this->role_id);

        if (!$role) {
            $this->dispatch('cannotUpdateRole');
            return;
        }

        $this->validate();

        $role->update([
            'role' => [
                'en' => $this->role['en'],
                'ar' => $this->role['ar'],
            ],
            'permession' => json_encode($this->permession),
        ]);

        $this->dispatch('success', __('dashboard.item_updated_successfully'));
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData');

        $this->role = ['en' => '', 'ar' => ''];
        $this->permession = [];
        $this->role_id = null;
    }


    public function render()
    {
        return view('dashboard.roles.update-role');
    }
}
