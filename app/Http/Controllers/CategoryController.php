<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::all();

        return view('satuan&kategori.kategori', compact('categories'));
    }

    // Fungsi untuk menampilkan modal tambah kategori
    public function create(Request $req)
    {
        $roles = [
            'name' => 'required|max:255'
        ];

        $messages = [
            'name.required' => 'nama satuan tidak boleh kosong'
        ];

        $validator = Validator::make($req->all(), $roles, $messages);

        if ($validator->fails()) {
            session()->flash('modal_mode', $req->id !== null ? 'edit' : 'add');
            session()->flash('modal_data', $req->except('gambar'));

            return redirect()->back()->withErrors($validator);
        }

        if ($req->hasFile('gambar')) {
            $req->validate(
                [
                    'gambar' => 'mimes:jpeg,jpg,png|image|file'
                ],
                [
                    'gambar.mimes' => 'ekstensi gambar harus jpeg, jpg, png',
                    'gambar.image' => 'Format gambar salah',
                    'gambar.file' => 'format gambar bukan file'
                ]
            );

            $gambar_file = $req->file('gambar');
            $ekstensi_gambar = $gambar_file->extension();
            $nama_gambar = date('ymdhis') . "." . $ekstensi_gambar;
            $gambar_file->move(public_path('pictures/'), $nama_gambar);
        } else {
            $nama_gambar = "default.jpg";
        }

        Categories::create([
            'name' => $req->name,
            'gambar' => $nama_gambar
        ]);

        return back()->with('success', 'data kategori berhasil dibuat');
    }

    // Fungsi untuk mengupdate kategori
    public function edit(Request $req)
    {
        $roles = [
            'name' => 'required|max:255'
        ];

        $messages = [
            'name.required' => 'nama satuan tidak boleh kosong'
        ];

        $validator = Validator::make($req->all(), $roles, $messages);

        if ($validator->fails()) {
            $filteredData = $req->except('gambar');
            session()->flash('modal_mode', $req->id !== null ? 'edit' : 'add');
            session()->flash('modal_data', $filteredData);

            return redirect()->back()->withErrors($validator);
        }


        $kategori = Categories::find($req->id);
        if (!$kategori) {
            Categories::flash('error', 'data Kategori tidak tersedia');
            return;
        }

        if ($req->hasFile('gambar')) {

            $req->validate(
                [
                    'gambar' => 'mimes:jpeg,jpg,png|image|file'
                ],
                [
                    'gambar.mimes' => 'ekstensi gambar harus jpeg, jpg, png',
                    'gambar.image' => 'Format gambar salah',
                    'gambar.file' => 'format gambar bukan file'
                ]
            );

            if ($kategori->gambar !== 'default.jpg') {
                $oldImage = public_path('pictures/' . $kategori->gambar);
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }

            $image = $req->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('pictures/'), $imageName);
        } else {
            $imageName = $kategori->gambar;
        }

        $kategori->update([
            'name' => $req->name,
            'gambar' => $imageName
        ]);

        return back()->with('success', 'data kategori berhasil diubah');
    }



    // Fungsi untuk menghapus kategori
    public function delete($id)
    {
      
        $kategori = Categories::find($id);

       
        if (!$kategori) {
            Session::flash('error', 'Data kategori tidak ditemukan');
            return redirect()->back();
        }

    
        if ($kategori->gambar !== 'default.jpg') {
            $gambarPath = public_path('pictures/' . $kategori->gambar);

     
            if (file_exists($gambarPath)) {
                unlink($gambarPath); 
            }
        }

   
        $kategori->products()->detach();

        $kategori->delete();

    
        Session::flash('success', 'Data kategori berhasil dihapus');

        return redirect()->back();
    }
}
