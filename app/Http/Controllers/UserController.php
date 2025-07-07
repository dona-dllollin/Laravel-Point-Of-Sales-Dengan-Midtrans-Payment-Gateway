<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function viewAccount(Request $request)
    {
        $filter = $request->query('filter', 'nama');
        $users = User::orderBy($filter)->get();
        return view('manage_account.account', compact('users'));
    }

    public function updateAccount(Request $request)
{
    $user = User::findOrFail($request->id);

    // Validasi input
    $rules = [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'no_karyawan' => 'required|string|max:255|unique:users,no_karyawan,' . $user->id,
        'role' => 'required|in:admin,kasir',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ];

    $messages = [
        'nama.required' => 'nama user harus diisi',
        'email.required' => 'email harus diisi',
        'email.email' => 'format email tidak sesuai',
        'no_karyawan.required' => 'nomor karyawan harus diisi',
        'no_karyawan.unique' => 'nomor karyawan sudah digunakan',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        $filteredData = $request->except('foto');
        session()->flash('modal_data', $filteredData);

        return redirect()->back()->withErrors($validator);
    }


    // Cek apakah user upload foto baru
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');
        $filename = time() . '_' . $foto->getClientOriginalName();
    

        if ($user->foto && $user->foto !== 'default.png') {
            $oldPath = public_path('pictures/acounts/' . $user->foto);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    
        $foto->move(public_path('pictures/acounts'), $filename);
    }
    elseif ($request->input('nama_foto') === 'default.png') {
        if ($user->foto && $user->foto !== 'default.png') {
            $oldPath = public_path('pictures/acounts/' . $user->foto);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    
        $filename = 'default.png';
    }
    else {
        $filename = $user->foto;
    }
    
    $karyawan = Karyawan::where('no_karyawan', $user->no_karyawan)->first();
    if ($karyawan) {
        $karyawan->update([
            'no_karyawan' => $request->no_karyawan,
        ]);
    }

    $user->update([
        'nama' => $request->nama,
        'email' => $request->email,
        'no_karyawan' => $request->no_karyawan,
        'role' => $request->role,
        'foto' => $filename
    ]);

    return redirect()->back()->with('update_success', 'Akun berhasil diperbarui');
}

public function addAccount(){
    return view('manage_account.new_acount');
}

function register(Request $request)
    {

        $request->validate(
            [
                'nama' => 'required',
                'email' => 'required|unique:users|email',
                'no_karyawan' => 'required|unique:users,no_karyawan',
                'password' => 'required|min:8'
            ],
            [
                'no_karyawan.unique' => 'nomor karyawan sudah digunakan'
            ]
        );

        if ($request->hasFile('foto')) {
            $request->validate(
                [
                    'foto' => 'mimes:jpeg,jpg,png|image|file'
                ],
                [
                    'foto.mimes' => 'ekstensi gambar harus jpeg, jpg, png',
                    'foto.image' => 'Format gambar salah',
                    'foto.file' => 'format gambar bukan file'
                ]
            );

            $gambar_file = $request->file('foto');
            $ekstensi_gambar = $gambar_file->extension();
            $nama_gambar = date('ymdhis') . "." . $ekstensi_gambar;
            $gambar_file->move(public_path('pictures/acounts') . $nama_gambar);
        } else {
            $nama_gambar = "default.png";
        }

        $checkKaryawan = Karyawan::where('no_karyawan', $request->input('no_karyawan'))->first();

        if (!$checkKaryawan) {
            // Langkah 2: Jika karyawan tidak ditemukan
            return back()->with('error', 'karyawan dengan nomor ' . $request->input('no_karyawan') . 'tidak ditemukan')->withInput();
        }

        $dataRegister = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_karyawan' => $request->no_karyawan,
            'foto' => $nama_gambar,
            'password' => $request->password,
            'role' => 'kasir',
            'market_id' => $checkKaryawan->market_id
        ];

        User::create($dataRegister);

        return redirect()->route('admin.account')->with('create_success', 'Akun baru berhasil dibuat');
    }

    public function deleteAccount($id){
        $account = User::find($id);
        $account->delete();
        return redirect()->back()->with("delete_success", "Akun Berhasil Dihapus");
    }

}
