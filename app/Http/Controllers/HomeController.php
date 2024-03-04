<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foto;
use App\Models\KomentarFoto;
use App\Models\LikeFoto;
use App\Models\Album;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('query');
        $albumQuery = $request->input('album_query');

        // Ambil semua foto
        $fotos = Foto::query();

        // Muat semua komentar dan hitung totalnya
        $fotos->withCount('komentar');

        if ($searchQuery) {
            $fotos->where(function ($query) use ($searchQuery) {
                $query->where('JudulFoto', 'LIKE', "%$searchQuery%")
                    ->orWhere('DeskripsiFoto', 'LIKE', "%$searchQuery%")
                    ->orWhereHas('user', function ($query) use ($searchQuery) {
                        $query->where('Username', 'LIKE', "%$searchQuery%");
                    });
            });
        }

        // Filter dengan album yang dipilih
        if ($albumQuery) {
            $fotos->whereHas('album', function ($query) use ($albumQuery) {
                $query->where('NamaAlbum', $albumQuery);
            });
        }

        // Paginasi hasil
        $fotos = $fotos->paginate(12);

        // Ambil list album
        $albumNames = Album::pluck('NamaAlbum')->toArray();

        return view('home.index', compact('fotos', 'searchQuery', 'albumNames'));
    }

    public function show($id)
    {
        // Ambil data foto berdasarkan ID
        $foto = Foto::findOrFail($id);

        // Tampilkan halaman detail gambar
        return view('home.show', compact('foto'));
    }

    public function addKomentar(Request $request, $id)
    {
        $request->validate([
            'IsiKomentar' => 'required|string',
        ]);

        $foto = Foto::findOrFail($id);

        $komentar = new KomentarFoto();
        $komentar->id_foto = $foto->id;
        $komentar->id_user = auth()->id();
        $komentar->IsiKomentar = $request->input('IsiKomentar');
        $komentar->save();

        if ($request->ajax()) {
            // Jika permintaan datang dari AJAX, kembalikan respons dalam format JSON
            return response()->json(['success' => true, 'comment' => $komentar]);
        }

        return back()->with('success', 'Comment successfully added');
    }

    public function deleteComment($id)
    {
        // Temukan komentar berdasarkan ID
        $komentar = KomentarFoto::findOrFail($id);

        // Pastikan hanya pemilik komentar yang dapat menghapusnya
        if ($komentar->id_user == auth()->id()) {
            $komentar->delete();
            return back()->with('success', 'Comment successfully deleted');
        }

        return back()->with('error', 'You are not authorized to delete this comment');
    }


    public function likeFoto($id)
    {
        $foto = Foto::findOrFail($id);

        $like = new LikeFoto();
        $like->id_foto = $foto->id;
        $like->id_user = auth()->id();
        $like->save();

        $totalLikes = $foto->likes()->count();

        return response()->json(['total_likes' => $totalLikes]);
    }

    public function unlikeFoto($id)
    {
        $foto = Foto::findOrFail($id);

        // Temukan like berdasarkan foto dan id pengguna
        $like = LikeFoto::where('id_foto', $foto->id)->where('id_user', auth()->id())->first();

        if ($like) {
            $like->delete(); // Hapus like jika ditemukan
        }

        $totalLikes = $foto->likes()->count();

        return response()->json(['total_likes' => $totalLikes]);
    }
}
