<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index',compact('movies'));
    }

    /**
     * Show the form for creating a new resource. tampilan formulir
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage. tabahkan data
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'req uired',
            'age_rating' => 'required',
            //mimes -> bentik file yang diizinkan untuk upload
            'poster' => 'required|mimes:jpg,jpeg,png,webp,svg',
            'description' => 'required|min:10',
        ], [
            'title.required' => 'Judul Film Harus Diisi',
            'duration.required' => 'Durasi Film Harus Diisi',
            'genre.required' => 'Genre Film Harus Diisi',
            'director.required' => 'Sutradara Film Harus Diisi',
            'age_rating.required' => 'Rating Usia Minimal Harus Diisi',
            'poster.required' => 'Poster Harus Diisi',
            'poster.mimes' => 'Format File Poster Harus Diisi Dengan JPG/JPEG/PNG/WEBp/SVG',
            'description.required' => 'Sinopsis Film Harus Diisi',
            'description.min' => 'Sinopsis Film Harus Diisi Minimal 10 Karakter'
        ]);
        // Ambil file yang diupload = $request->file('name_input')
        $gambar = $request->file('poster');
        // Buat Nama baru di filmnya,agar menghindari nama file yang sama
        // GetgetClientOriginalExtension() -> untuk mengambil ekstensi file
        $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
        // simpan file ke storage,nama file gunakan nama file baru
        // storeAs('fnama folder',$namafile,'public') : format menyimpan file
        $path = $gambar->storeAs('poster', $namaGambar, 'public');

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path, //$path beriskan lokasi file yang disimpan
            'description' => $request->description,
            'actived' => 1,
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil Membuat Dara Film');
        } else {
            return redirect()->back()->with('error', 'Gagal Silahkan Coba Lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'req uired',
            'age_rating' => 'required',
            //mimes -> bentik file yang diizinkan untuk upload
            'poster' => 'mimes:jpg,jpeg,png,webp,svg',
            'description' => 'required|min:10',
        ], [
            'title.required' => 'Judul Film Harus Diisi',
            'duration.required' => 'Durasi Film Harus Diisi',
            'genre.required' => 'Genre Film Harus Diisi',
            'director.required' => 'Sutradara Film Harus Diisi',
            'age_rating.required' => 'Rating Usia Minimal Harus Diisi',
            'poster.mimes' => 'Format File Poster Harus Diisi Dengan JPG/JPEG/PNG/WEBp/SVG',
            'description.required' => 'Sinopsis Film Harus Diisi',
            'description.min' => 'Sinopsis Film Harus Diisi Minimal 10 Karakter'
        ]);
        // data sebelumnya
        $movie = Movie::find($id);
        if($request->file('poster')){
            // storage_path : cek apakah file ada di folder storage/app/public
            $fileSebelumnya = storage_path('app/public/'.$movie->poster);
            if(file_exists($fileSebelumnya)){
                //hapus file sebelumnya
                unlink($fileSebelumnya);
            }

                // Ambil file yang diupload = $request->file('name_input')
            $gambar = $request->file('poster');
            // Buat Nama baru di filmnya,agar menghindari nama file yang sama
            // GetgetClientOriginalExtension() -> untuk mengambil ekstensi file
            $namaGambar = Str::random(5) . "-poster." . $gambar->getClientOriginalExtension();
            // simpan file ke storage,nama file gunakan nama file baru
            // storeAs('fnama folder',$namafile,'public') : format menyimpan file
            $path = $gambar->storeAs('poster', $namaGambar, 'public');

        }

                $updateData = Movie::where('id',$id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'poster' => $path ?? $movie['poster'],
            'description' => $request->description,
            'actived' => 1,
                ]);

                if ($updateData) {
                    return redirect()->route('admin.movies.index')->with('success', 'Berhasil Update Data Film');
                } else {
                    return redirect()->back()->with('error', 'Gagal Silahkan Coba Lagi');
                }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
