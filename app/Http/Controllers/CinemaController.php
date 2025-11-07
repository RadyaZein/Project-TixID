<?php
    namespace App\Http\Controllers;

    use App\Models\Cinema;
    use App\Models\Schedule;
    use Illuminate\Http\Request;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\CinemaExport;
    use Yajra\DataTables\Facades\DataTables;

    class CinemaController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $cinema = Cinema::all();
            return view('admin.cinema.index', compact('cinema'));
            //compact -> argumen pada fungsi akan sama dengan nama variabel yang akan
            //  dikirim ke blade
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            //validasi
            $request->validate([
                'name' => 'required|min:3',
                'location' => 'required|min:10',
            ],[
                'name.required' => 'Nama Bioskop Wajib Diisi',
                'name.min' => 'Nama Wajib diisi minimal 3 HUruf',
                'location.required' => 'Lokasi Bioskop Wajib Diisi',
                'location.min' => 'Lokasi Wajib diisi minimal 10 HUruf',
          ]);

                //kirim data
                $createCinema = Cinema::create([
                    'name' => $request->name,
                    'location' => $request->location,
            ]);
                //redirect / perpindahan halaman
                if($createCinema){
                return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil Mebuat Data Bioskop');
                } else {
                return redirect()->back()->with('failed', 'Data Gagal Ditambahkan');
                }
        }

        /**
         * Display the specified resource.
         */
        public function show(Cinema $cinema)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit($id)
        {
            $cinema = Cinema::find($id);
            return view('admin.cinema.edit', compact('cinema'));
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request,$id)
        {
            $updateCinema = Cinema::where('id', $id)->update([
                'name' => $request->name,
                'location' => $request->location,
            ]);

            if($updateCinema) {
                return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil Mengubah Data Bioskop');
            } else {
                return redirect()->back()->with('failed', 'Data Gagal Diubah');
            }
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy($id)
        {
            $schedules = Schedule::where('cinema_id', $id)->count();
            if ($schedules) {
                return redirect()->route('admin.cinemas.index')->with('failed','Tidak dapat mengahapus data bioskop! Data sudah tertaut dengan jadwal tayang');
            }
            $deleteData = Cinema::where('id', $id)->delete();
            if($deleteData) {
                return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil Menghapus Data Bioskop');
            } else {
                return redirect()->back()->with('failed', 'Gagal mengahapus data bioskop!');
        }
        }

         public function export()
    {
        // file yang akan di unduh
        $fileName = 'data-Cinema.xlsx';
        //proses unduh
        return Excel::download(new CinemaExport, $fileName);
    }

     public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $cinemaTrash = cinema::onlyTrashed()->get();
        return view('admin.cinema.trash', compact('cinemaTrash'));
    }

    public function restore($id)
    {
        $cinema = cinema::onlyTrashed()->find($id);
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $cinema = cinema::onlyTrashed()->find($id);
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

  public function datatables()
{
    $cinemas = Cinema::query();

    return DataTables::of($cinemas)
        ->addIndexColumn()
        ->addColumn('action', function($cinema){
            $btnEdit = '<a href="' . route('admin.cinemas.edit', $cinema->id) . '" class="btn btn-primary btn-sm me-2">Edit</a>';
            $btnDelete = '
                <form action="' . route('admin.cinemas.delete', $cinema->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus data ini?\')">Hapus</button>
                </form>';
            return '<div class="d-flex justify-content-center align-items-center gap-2">'
                    . $btnEdit . $btnDelete .
                   '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function cinemaList()
    {
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

     public function cinemaSchedules($cinema_id)
    {
        // whereHas('namaRelasi', function($q) {.....} : argumen 1 (nama relasi) wajib, argumen 2 (func untuk filter pada relasi) opsional)
        // whereHas('namaRelasi') -> Movie::whereHas('schedules') mengambil data film hanya yang memiliki relasi (memiliki data) schedules
        $schedules = Schedule::where('cinema_id', $cinema_id)->with('movie')->whereHas('movie', function ($q) {
            $q->where('activated', 1);
        })->get();
        return view('schedule.cinema-schedules', compact('schedules'));
    }
}
