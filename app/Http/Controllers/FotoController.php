<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    public function index()
    {
        // Ambil semua foto dan albumnya dengan paginasi
        $userId = Auth::id();
        $fotos = Foto::with('album')->where('id_user', $userId)->paginate(10);

        // Albil album yang dimiliki oleh pengguna yang sedang login
        $albums = Album::where('id_user', $userId)->get();

        // Kembalikan ke view index
        return view('foto.index', compact('fotos', 'albums'));
    }

    public function show($id)
    {
        // Cari album berdasarkan ID
        $album = Album::findOrFail($id);

        // Paginasikan semua foto pada album
        $fotos = $album->fotos()->paginate(12);

        // Kembalikan ke view show
        return view('album.show', compact('album', 'fotos'));
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'id_album');
    }

    public function create()
    {
        // Ambil album yang dimiliki oleh pengguna yang sedang login
        $userId = Auth::id();
        $albums = Album::where('id_user', $userId)->get();

        return view('foto.create', compact('albums'));
    }

    public function store(Request $request)
    {
        try {
            // validasi request
            $request->validate([
                'judul_foto' => 'required',
                'deskripsi_foto' => 'required',
                'album_id' => 'required|exists:albums,id',
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            // Ambil ID pengguna yang sedang login lalu ambil username
            $userId = Auth::id();
            $username = Auth::user()->Username;

            // Ambil filename dari gambar
            $originalFilename = $request->file('foto')->getClientOriginalName();

            // Tambahkan filename dengan username
            $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_' . $username . '.' . $request->file('foto')->getClientOriginalExtension();

            // Simpan gambar ke storage
            $photoPath = $request->file('foto')->storeAs('user_photos/' . $userId, $newFilename, 'public');

            // Buat record baru
            Foto::create([
                'JudulFoto' => $request->judul_foto,
                'DeskripsiFoto' => $request->deskripsi_foto,
                'LokasiFile' => $photoPath,
                'id_user' => $userId,
                'id_album' => $request->album_id, // Simpan ID album
            ]);

            // Redirect ke halaman index dengan pesan swal
            return redirect()->route('foto.index')->with('success', 'Photo created successfully');
        } catch (\Exception $e) {
            // Return error message dengan swal
            return redirect()->back()->with('error', 'Not a valid image file');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                'judul_foto' => 'required',
                'deskripsi_foto' => 'required',
                'album_id' => 'required|exists:albums,id',
                'foto' => 'image|mimes:jpeg,png,jpg,gif',
            ]);

            // Cari foto berdasarkan ID
            $foto = Foto::findOrFail($id);

            // Update the photo data
            $foto->JudulFoto = $request->judul_foto;
            $foto->DeskripsiFoto = $request->deskripsi_foto;
            $foto->id_album = $request->album_id;

            // Cek apakah ada file gambar yang diunggah
            if ($request->hasFile('foto')) {
                // Validasi request
                $request->validate([
                    'foto' => 'image|mimes:jpeg,png,jpg,gif',
                ]);

                $originalFilename = $request->file('foto')->getClientOriginalName();

                $newFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_' . Auth::user()->Username . '.' . $request->file('foto')->getClientOriginalExtension();

                $photoPath = $request->file('foto')->storeAs('user_photos/' . $foto->id_user, $newFilename, 'public');

                // Hapus file gambar lama
                Storage::delete($foto->LokasiFile);

                // Update lokasi file
                $foto->LokasiFile = $photoPath;
            }

            // Simpan perubahan
            $foto->save();

            // Redirect ke halaman index dengan pesan swal
            return redirect()->route('foto.index')->with('success', 'Photo updated successfully');
        } catch (\Exception $e) {

            // Return error message dengan swal
            return redirect()->back()->with('error', 'Not a valid image file');
        }
    }

    public function destroy($id)
    {
        // Cari foto berdasarkan ID
        $foto = Foto::findOrFail($id);

        // Ambil ID album
        $albumId = $foto->id_album;

        // Hapus file gambar
        Storage::delete($foto->LokasiFile);

        // Hapus record
        $foto->delete();

        // Cek referer
        $referrer = url()->previous();
        $redirectRoute = $referrer == route('album.show', $albumId) ? route('album.show', $albumId) : route('foto.index');

        return redirect($redirectRoute)->with('success', 'Photo deleted successfully');
    }

    public function edit($id)
    {
        // Cari foto berdasarkan ID
        $foto = Foto::findOrFail($id);

        // Ambil album yang dimiliki oleh pengguna yang sedang login
        $userId = Auth::id();
        $albums = Album::where('id_user', $userId)->get();

        // Return edit view
        return view('foto.edit', compact('foto', 'albums'));
    }

    // Filter foto by album
    public function filterByAlbum(Request $request)
    {
        // Validasi request
        $request->validate([
            'album_id' => 'nullable|exists:albums,id',
        ]);

        // Ambil album yang dimiliki oleh pengguna yang sedang login
        $userId = Auth::id();
        $albums = Album::where('id_user', $userId)->get();

        // Ambil fotos berdasarkan ID album dengan paginasi
        $query = Foto::with('album')->where('id_user', $userId);

        // Jika ditemukan ID album maka filter
        if ($request->filled('album_id')) {
            $query->where('id_album', $request->album_id);
        }

        $fotos = $query->paginate(10); // Paginasi

        // Return view index
        return view('foto.index', compact('fotos', 'albums'));
    }
}