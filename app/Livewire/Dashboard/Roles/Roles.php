<?php

namespace App\Livewire\Dashboard\Roles;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Roles extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['itemAdded' => '$refresh', 'refreshData' => '$refresh', 'delete'];

    public $search = '';

    public function delete($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return;
        }

        // Check if role has admins
        if ($role->admins()->count() > 0) {
            $this->dispatch('cannotDelete');
            return;
        }

        $role->delete();

        $this->dispatch('success', __('dashboard.item_deleted_successfully'));
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $locale = app()->getLocale();

        $data = Role::whereRaw(
            "JSON_UNQUOTE(JSON_EXTRACT(role, '$.{$locale}')) LIKE ?",
            ["%{$this->search}%"]
        )->latest()->paginate(10);

        return view('dashboard.roles.roles', compact('data'));
    }
}
