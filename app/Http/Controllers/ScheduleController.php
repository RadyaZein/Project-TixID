<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\models\Movie;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // data untuk select
        $cinemas = Cinema::all();
        $movies = Movie::all();

        // with() : mengambil fungsi dari model, untuk mengaakses detai relasi ngga cuma primary aja
        $schedules = Schedule::with('cinema', 'movie')->get();


        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
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
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required|numeric',
            // karena hours array,jadi yang di validasi itemnya -> 'hours.*'
            'hours.*' => 'required'
        ], [
            'cinema_id.required' => 'Bioskop Harus Dipilih',
            'movie_id.required' => 'Film Harus Dipilih',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka' ,
            'hours.*.required' => 'Jam harus diisi minimal satu data jam'
        ]);

        // ambil dataa jika sudah ada bedasarkan bioskop dan film yang sama
        $schedule = Schedule::where ('cinema_id', $request->cinema_id)->where('movie_id', $request->movie_id)->first();

        // jika ada data yang bioskopnya dan filmnya sama
        if ($schedule) {
            // ambil data jam yang sebelmnya
            $hours = $schedule['hours'];
        } else {
            // kalau belum ada data, hours dibuat kosong dulu
            $hours = [];
        }
        // gabungkan hours sebelemunya dengan hours baru dari input ($request->hours)
        $mergeHours = array_merge($hours, $request->hours);
        // jika ada jam yang sama, hilangkan duplikasi data
        // gunakan data jam ini untuk database
        $newHours = array_unique($mergeHours);

        // dd($request->all());
        $createData = Schedule::updateOrCreate([
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ],[
            'price' => $request->price,
            'hours' => $newHours
        ]);
        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success','Berhasil menambahkan data!');
        } else {
            return redirect()->back()->with('error', 'Gagal coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    $request->validate([
        'price' => 'required|numeric',
        'hours.*' => 'required|date_format:H:i'
    ], [
        'price.required' => 'Harga harus diisi',
        'price.numeric' => 'Harga harus dengan angka',
        'hours.*.required' => 'Jam tayang harus diisi',
        'hours.*.date_format' => 'Format jam harus dengan format jam:menit',
    ]);

    $updateData = Schedule::where('id', $id)->update([
        'price' => $request->price,
        'hours' => $request->hours
    ]);

    if ($updateData) {
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data!');
    } else {
        return redirect()->back()->with('error', 'Gagal coba lagi');
    }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menghapus data!');

    }

    public function trash()
    {
        // ORM yang digunakan terkait softdeletes
        // OnlyTrashed() -> filter data yang sudah dihapus, delete_at BUKAN NULL
        // restore() ->megembalikan data yang sudah dihapus (mengahapus nilai tanggal pada deleted_at
        // forceDelete() -> menghapus data secara permanent, data dihilangkan bahkan dari dbnya
        $scheduleTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('scheduleTrash'));
    }

    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
    }

    public function datatables()
{
    $data = Schedule::with(['cinema', 'movie'])->get();

    return datatables()->of($data)
        ->addIndexColumn()
        ->addColumn('cinema', function ($row) {
            return $row->cinema->name ?? '-';
        })
        ->addColumn('movie', function ($row) {
            return $row->movie->title ?? '-';
        })
        ->addColumn('price', function ($row) {
            return 'Rp. ' . number_format($row->price, 0, ',', '.');
        })
        ->addColumn('hours', function ($row) {
            $list = '<ul>';
            foreach ($row->hours as $h) {
                $list .= "<li>$h</li>";
            }
            $list .= '</ul>';
            return $list;
        })
        ->addColumn('action', function ($row) {
            $edit = '<a href="' . route('staff.schedules.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';
            $delete = '
                <form action="' . route('staff.schedules.delete', $row->id) . '" method="POST" style="display:inline-block;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm ms-2" onclick="return confirm(\'Yakin?\')">Hapus</button>
                </form>';
            return $edit . $delete;
        })
        ->rawColumns(['hours', 'action'])
        ->make(true);
}

}
