<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::all();

        return view('market.index', compact('markets'));
    }

    // public function create(Request $req)
    // {
      
    //     $rules = [
    //         'nama_toko' => 'required|string|max:100|unique:markets,nama_toko',
    //         'no_telp' => 'required|max:15',
    //         'alamat' => 'required',
    //     ];

      
    //     $messages = [
    //         'nama_toko.required' => 'Nama toko wajib diisi',
    //         'nama_toko.unique' => 'Nama toko sudah digunakan',
    //         'no_telp.required' => 'Nomor telepon wajib diisi',
    //         'alamat.required' => 'Alamat wajib diisi',
    //     ];

    //     $validator = Validator::make($req->all(), $rules, $messages);

    //     if ($validator->fails()) {
    //         // Mode edit atau tambah berdasarkan apakah ada 'id'
    //         session()->flash('modal_mode', $req->id !== null ? 'edit' : 'add');
    //         session()->flash('modal_data', $req->all());

    //         return redirect()->back()->withErrors($validator);
    //     }



    //     Market::create([
    //         'nama_toko' => $req->nama_toko,
    //         'slug' => Str::slug($req->nama_toko),
    //         'kas' => $req->kas,
    //         'no_telp' => $req->no_telp,
    //         'alamat' => $req->alamat
    //     ]);

    //     return back()->with('success', 'data Toko baru berhasil ditambahkan');
    // }

    public function update(Request $req)
    {
        // Definisikan aturan validasi
        $rules = [
            'nama_toko' => 'required|string|max:100|unique:markets,nama_toko,' . $req->id,
            'no_telp' => 'required|max:15',
            'alamat' => 'required',
        ];

        // Definisikan pesan error custom
        $messages = [
            'nama_toko.required' => 'Nama toko wajib diisi',
            'nama_toko.unique' => 'Nama toko sudah digunakan',
            'no_telp.required' => 'Nomor telepon wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $validator = Validator::make($req->all(), $rules, $messages);

        if ($validator->fails()) {
            // Mode edit atau tambah berdasarkan apakah ada 'id'
            session()->flash('modal_mode', $req->has('id') ? 'edit' : 'add');
            session()->flash('modal_data', $req->all());

            return redirect()->back()->withErrors($validator);
        }


        $market = Market::find($req->id);

        if (!$market) {
            Session::flash('error', 'data toko tidak ditemukan');
            return;
        }

        $market->update([
            'nama_toko' => $req->nama_toko,
            'slug' => Str::slug($req->nama_toko),
            'no_telp' => $req->no_telp,
            'alamat' => $req->alamat
        ]);

        return back()->with('success', 'data toko berhasil diubah');
    }

    // public function delete($id)
    // {
    //     $market = Market::find($id);

    //     if (!$market) {
    //         Session::flash('error', 'data toko tidak ditemukan');
    //     }

    //     $market->delete();
    //     Session::flash('success', 'Data Toko berhasil dihapus');

    //     return redirect()->back();
    // }
}
