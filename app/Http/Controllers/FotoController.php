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
        // 1) Validasi
        $request->validate([
            'judul_foto' => 'required|string',
            'deskripsi_foto' => 'required|string',
            'album_id' => 'required|exists:albums,id',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2) Ambil file
        $file = $request->file('foto');
        if (!$file || !$file->isValid()) {
            return back()->with('error', 'Gagal upload foto.');
        }

        // 3) Buat nama baru
        $userId = Auth::id();
        $username = Auth::user()->Username;
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $newName = "{$baseName}_{$username}.{$ext}";

        // 4) Tentukan folder tujuan di storage/app/public
        $dirAbsolute = storage_path("app/public/user_photos/{$userId}");
        if (!file_exists($dirAbsolute)) {
            mkdir($dirAbsolute, 0755, true);
        }

        // 5) Pindahkan file dari tmp ke folder tujuan
        $file->move($dirAbsolute, $newName);

        // 6) Simpan path relatif untuk DB
        $photoPath = "user_photos/{$userId}/{$newName}";

        // 7) Buat record
        Foto::create([
            'JudulFoto' => $request->judul_foto,
            'DeskripsiFoto' => $request->deskripsi_foto,
            'LokasiFile' => $photoPath,
            'id_user' => $userId,
            'id_album' => $request->album_id,
        ]);

        return redirect()->route('foto.index')
            ->with('success', 'Photo created successfully');
    }

    public function update(Request $request, $id)
    {
        // 1) Validasi dasar
        $request->validate([
            'judul_foto' => 'required|string',
            'deskripsi_foto' => 'required|string',
            'album_id' => 'required|exists:albums,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // 2) Ambil record
        $foto = Foto::findOrFail($id);
        $foto->JudulFoto = $request->judul_foto;
        $foto->DeskripsiFoto = $request->deskripsi_foto;
        $foto->id_album = $request->album_id;

        // 3) Jika ada file baru, pindahkan manual
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if (!$file->isValid()) {
                return back()->with('error', 'File upload gagal.');
            }

            // nama baru
            $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = $file->getClientOriginalExtension();
            $user = Auth::user()->Username;
            $new = "{$base}_{$user}.{$ext}";
            $uid = $foto->id_user;

            // folder tujuan
            $destDir = storage_path("app/public/user_photos/{$uid}");
            if (!file_exists($destDir)) {
                mkdir($destDir, 0755, true);
            }

            // move tmp → public storage
            $file->move($destDir, $new);

            // hapus file lama di disk public
            Storage::disk('public')->delete($foto->LokasiFile);

            // update path di DB
            $foto->LokasiFile = "user_photos/{$uid}/{$new}";
        }

        $foto->save();

        return redirect()->route('foto.index')
            ->with('success', 'Photo updated successfully');
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