<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|in:M,F',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,|max:2048',
        ];

        if (in_array($user->type, ['member', 'board'])) {
            $rules = array_merge($rules, [
                'nif' => 'nullable|string|max:20',
                'default_delivery_address' => 'nullable|string|max:255',
                'default_payment_type' => 'nullable|in:Visa,PayPal,MB WAY',
                'default_payment_reference' => 'nullable|string|max:100',
            ]);
        }

        $data = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password atual incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password atualizada com sucesso!');
    }

    public function removePhoto(Request $request)
    {
        $user = auth()->user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile photo removed successfully.');
    }
}
