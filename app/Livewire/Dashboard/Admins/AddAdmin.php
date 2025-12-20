<?php

namespace App\Livewire\Dashboard\Admins;

use App\Models\Admin;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use App\Helpers\FileHelper;

class AddAdmin extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role_id = '';
    public $status = 1;
    public $image;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email|max:255',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => $this->role_id,
            'status' => $this->status,
        ];

        // Handle image upload using FileHelper
        if ($this->image) {
            try {
                $data['image'] = FileHelper::uploadImage($this->image, 'uploads/admins');
            } catch (\Exception $e) {
                $this->dispatch('somethingFailed');
                return;
            }
        }

        Admin::create($data);

        $this->dispatch('createModalToggle');
        $this->dispatch('success', __('dashboard.item_created_successfully'));
        $this->dispatch('refreshData')->to(Admins::class);

        // Reset inputs
        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role_id', 'status', 'image']);
    }

    public function render()
    {
        $roles = Role::all();
        return view('dashboard.admins.add-admin', compact('roles'));
    }
}
