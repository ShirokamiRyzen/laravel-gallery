<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function show()
    {
        // Ambil data pengguna dari tabel users
        $user = Auth::user();

        return view('profile.show', [
            'user' => $user
        ]);
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'NamaLengkap' => $request->input('NamaLengkap'),
            'Username' => $request->input('Username'),
            'Email' => $request->input('Email'),
            'Alamat' => $request->input('Alamat')
        ]);

        // Upload foto profil jika ada
        if ($request->hasFile('Avatar')) {
            $avatar = $request->file('Avatar');
            $avatarName = time().'.'.$avatar->getClientOriginalExtension();
            $avatar->storeAs('storage/user_profile', $avatarName);
            $user->profile_picture = $avatarName;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
    public function deleteProfilePicture()
    {
        $user = Auth::user();
    
        if ($user->profile_picture) {
            // Hapus file profile_picture
            Storage::delete('public/user_profile/' . $user->profile_picture);
            
            // Set profile_picture ke null
            $user->profile_picture = null;
            $user->save();
        
            return redirect()->back()->with('success', 'Profile picture deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'No profile picture to delete.');
    }
}
