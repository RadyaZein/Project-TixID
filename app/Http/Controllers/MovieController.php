<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //coba coba detail
   public function detail($id)
{
    $movie = Movie::findOrFail($id);
    return view('schedule.detail-film', compact('movie'));
}

    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index',compact('movies'));
    }

    public function home()
    {
        // where('field','value') : mencari data
        // get() : mengambil semua data dari hasil filter
        // mengurutkan -> orderBy('field','ASC/DESC') : ASC (a-z,0-9,terlama-terbaru), DESC : (z-a,9-0,terbaru-terlama)
        // limmit (angka) -> mengambil sejumlah yang ditentukan
        $movies = Movie::where('actived',1)->orderBy('created_at','DESC')->limit(4)->get();
        return view('home',compact('movies'));
    }

    public function homeAllMovies()
    {
        $movies = Movie::where('actived',1)->orderBy('created_at','DESC')->get();
        return view('home_movies',compact('movies'));
    }

    public function movieSchedule($movie_id)
    {
        // ambil data film beserta schedule dan bioskop pada schedule
        // 'schedule.cinema' -> karna relasi cinema adanya di schedule bukan di movie
        // first() -> amnbil satu data movie
        $movie = Movie::where('id', $movie_id)->with(['schedules','schedules.cinema'])->first();
        return view('schedule.movie-schedule', compact('movie'));

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
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil Membuat Data Film');
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
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        // hapus poster dari storage
        $filePath = storage_path('app/public/' . $movie->poster);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // hapus data film
        $movie->delete();

        return redirect()->route('admin.movies.index')->with('success', 'Berhasil menghapus data film!');
    }

    public function nonAktif($id)
{
    $movie = Movie::findOrFail($id);

    $movie->update([
        'actived' => 0
    ]);

    return redirect()->route('admin.movies.index')->with('success', 'Film berhasil dinonaktifkan!');
}

    public function export()
    {
        // file yang akan di unduh
        $fileName = 'data-film.xlsx';
        //proses unduh
        return Excel::download(new MovieExport, $fileName);
    }


}
