<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\models\Movie;
use Illuminate\Http\Request;

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
}
