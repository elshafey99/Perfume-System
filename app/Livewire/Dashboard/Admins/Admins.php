<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Helpers\FileHelper;

class Admins extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['itemAdded' => '$refresh', 'refreshData' => '$refresh', 'delete'];

    public $search = '';

    public function delete($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return;
        }

        // Prevent deleting the main admin (id = 1)
        if ($admin->id == 1) {
            $this->dispatch('cannotDeleteMainAdmin');
            return;
        }

        // Delete image using FileHelper
        if ($admin->image) {
            FileHelper::delete($admin->image);
        }

        $admin->delete();

        $this->dispatch('success', __('dashboard.item_deleted_successfully'));
    }

    public function toggleStatus($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            $admin->status = !$admin->status;
            $admin->save();

            $this->dispatch('success', __('dashboard.status_updated_successfully'));
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = Admin::with('role')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('dashboard.admins.admins', compact('data'));
    }
}
