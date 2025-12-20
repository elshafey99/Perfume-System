<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use App\Utils\ImageManger;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{

    protected $imageManger;
    public function __construct(ImageManger $imageManger)
    {
        $this->imageManger = $imageManger;
    }



    public function profile()
    {
        return view('dashboard.profile.index');
    } // End profile method


    public function profileUpdate(Request $request)
    {
        $request->validate([
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:admins,email,' . auth('admin')->user()->id,
        ]);
        $admin = Admin::findOrFail(auth('admin')->user()->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->hasFile('image')) {
            if ($admin->image) {
                $this->imageManger->deleteImage($admin->image);
            }
            $newImage = FileHelper::uploadImage($request->image, 'uploads/members', 'public');
            $admin->image = $newImage;
        }
        $admin->save();
        flash()->success(__('validation.successfully'));
        return redirect()->back();
    } // End profileUpdate method


    public function security()
    {
        return view('dashboard.profile.security');
    } // End security method



    public function profileUpdatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                flash()->error($error);
            }
            return redirect()->back()->withInput();
        }

        /** @var \App\Models\Admin $user */
        $user = auth('admin')->user();
        if (!Hash::check($request->current_password, $user->password)) {
            flash()->error(__('validation.something-valid'));
            return redirect()->back();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        flash()->success(__('validation.successfully'));
        return redirect()->back();
    }
}
