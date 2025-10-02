<?php
    namespace App\Http\Controllers;

    use App\Models\Cinema;
    use Illuminate\Http\Request;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\CinemaExport;

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
            $deleteData = Cinema::where('id', $id)->delete();
            if($deleteData) {
                return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil Menghapus Data Bioskop');
            } else {
                return redirect()->back()->with('failed', 'Gagal mengahapus data bioskop');
        }
        }

         public function export()
    {
        // file yang akan di unduh
        $fileName = 'data-Cinema.xlsx';
        //proses unduh
        return Excel::download(new CinemaExport, $fileName);
    }

}
