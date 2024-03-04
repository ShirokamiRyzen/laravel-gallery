<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function index()
    {
        // Mengambil semua album yang dimiliki oleh pengguna yang sedang login dengan paginasi
        $albums = Album::where('id_user', Auth::id())->paginate(10);
        return view('album.index', compact('albums'));
    }

    public function create()
    {
        return view('album.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaAlbum' => 'required',
            'Deskripsi' => 'required',
        ]);

        $album = new Album();
        $album->NamaAlbum = $request->NamaAlbum;
        $album->Deskripsi = $request->Deskripsi;
        $album->id_user = Auth::id();
        $album->save();

        return redirect()->route('album')->with('success', 'Album created successfully');
    }

    public function edit($id)
    {
        $album = Album::findOrFail($id);
        return view('album.edit', compact('album'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaAlbum' => 'required',
            'Deskripsi' => 'required',
        ]);

        $album = Album::findOrFail($id);
        $album->NamaAlbum = $request->NamaAlbum;
        $album->Deskripsi = $request->Deskripsi;
        $album->save();

        return redirect()->route('album')->with('success', 'Album updated successfully');
    }

    public function destroy($id)
    {
        // Cari album berdasarkan ID
        $album = Album::findOrFail($id);
        $albumName = $album->NamaAlbum; // Simpan nama album sebelum dihapus
        $album->delete();
    
        return redirect()->route('album')->with('success', "Album $albumName Deleted Successfully");
    }
}
