<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index()
    {
        return view('login');
    }

    function create()
    {
        return view('registrasi');
    }

    function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'email wajib di isi',
                'password.required' => 'Password wajib di isi'
            ]
        );

        $dataLogin = [
            'email' => $request->email,
            'password' => $request->password

        ];



        if (Auth::attempt($dataLogin)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('status', 'selamat datang admin');
            } elseif (Auth::user()->role === 'kasir') {
                session(['slug_market' => Auth::user()->market->slug]);
                $toko = Auth::user()->market->nama_toko;
                return redirect()->route('kasir.dashboard', ['slug_market' => session('slug_market')])->with('status', 'selamat datang ' . Auth::user()->nama . 'di toko' . $toko);
            } else {
                return '';
            }
        }

        return redirect()->back()->with('login_failed', 'email atau pasword anda salah');
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
                'email.unique' => 'email sudah digunakan',
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

        return redirect()->route('login')->with('create_success', 'Akun baru berhasil dibuat');
    }

    function logout(Request $request)
    {
        // Hapus slug_market dari session
        $request->session()->forget('slug_market');
        Auth::logout();
        return redirect('/');
    }
}
