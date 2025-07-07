<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProfilController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }


    
   
public function changeData(Request $req)
{
    $user = Auth::user();

    // Validasi input dengan custom error messages
    $validatedData = $req->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    ], [
        'nama.required' => 'Nama wajib diisi.',
        'nama.max' => 'Nama maksimal 255 karakter.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.max' => 'Email maksimal 255 karakter.',
        'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
    ]);

    // Update data user
    $user->nama = $validatedData['nama'];
    $user->email = $validatedData['email'];
    $user->save();

    Session::flash('update_success', 'Profil berhasil diubah');
    return redirect('/profile');
}

// Change Profile Picture
public function changePicture(Request $req)
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'User tidak ditemukan');
    }

    $foto = $req->file('foto');

    if ($foto) {
        $path = public_path('pictures/acounts/');

        // Hapus foto lama kalau bukan default.png
        if ($user->foto && $user->foto !== 'default.png') {
            $oldFile = $path . $user->foto;
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        // Generate nama file unik pakai timestamp
        $extension = $foto->getClientOriginalExtension();
        $filename = 'foto_' . time() . '_' . uniqid() . '.' . $extension;

      
        $foto->move($path, $filename);

        $user->update([
            'foto' => $filename
        ]);

        Session::flash('update_success', 'Foto profil berhasil diubah');
    }

    return redirect('/profile');
}



    // Change Profile Password
    public function changePassword(Request $req)
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'User tidak ditemukan');
    }

    if (Hash::check($req->old_password, $user->password)) {
        $user->password = Hash::make($req->new_password);
        $user->save();

        Session::flash('update_success', 'Password berhasil diubah');
    } else {
        Session::flash('update_error', 'Password lama tidak sesuai');
    }

    return redirect('/profile');
}

}
