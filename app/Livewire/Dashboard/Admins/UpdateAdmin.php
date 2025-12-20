<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\Admin;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FileHelper;

class UpdateAdmin extends Component
{
    use WithFileUploads;

    public $admin_id;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $status = 1;
    public $image;
    public $old_image;

    protected $listeners = ['editItem'];

    public function editItem($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            $this->dispatch('cannotUpdateAdmin');
            return;
        }

        $this->admin_id = $admin->id;
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->role_id = $admin->role_id;
        $this->status = $admin->status;
        $this->old_image = $admin->image;

        $this->dispatch('updateModalToggle');
    }

    public function submit()
    {
        $admin = Admin::find($this->admin_id);

        if (!$admin) {
            $this->dispatch('cannotUpdateAdmin');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $this->admin_id,
            'password' => 'nullable|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'status' => $this->status,
        ];

        // Update password if provided
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        // Handle image upload using FileHelper
        if ($this->image) {
            try {
                $data['image'] = FileHelper::updateFile($this->image, $this->old_image, 'uploads/admins');
            } catch (\Exception $e) {
                $this->dispatch('somethingFailed');
                return;
            }
        }

        $admin->update($data);

        $this->dispatch('success', __('dashboard.item_updated_successfully'));
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData');

        $this->reset(['admin_id', 'name', 'email', 'password', 'password_confirmation', 'role_id', 'status', 'image', 'old_image']);
    }

    public function render()
    {
        $roles = Role::all();
        return view('dashboard.admins.update-admin', compact('roles'));
    }
}
