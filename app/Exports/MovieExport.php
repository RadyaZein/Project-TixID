<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
//class untuk menambahkan th pada table excel
use Maatwebsite\Excel\Concerns\Withheadings;
// class untuk membuat td pada table excel
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class MovieExport implements FromCollection, Withheadings, WithMapping
{
    private $key = 0;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Movie::all();
    }

    // menentukan isi th

    public function headings(): array
    {
        return ['NO', 'JUDUL', 'DURASI', 'GENRE', 'SUTRADARA', 'USIA MINIMAL',
         'POSTER', 'SINOPSIS', 'STATUS'];
    }

    // menentukan isi td

    public function map($movie): array
    {
        return [
            ++$this->key,
            $movie->title,
            //jadi ada jamnya
            //format("H") : Ambil jam dari duration
            carbon::parse($movie->duration)->format("H")." Jam ".
            carbon::parse ($movie->duration)->format("i")." Menit",
            $movie->genre,
            $movie->director,
            $movie->age_rating."+",
            // poster berupa url public : asset ()
            asset('storage') , "/" . $movie->poster,
            $movie->description,
            // jika actived == 1 munculkan aktif, jika tidak munculkan non aktif
            $movie->actived == 1 ? 'Aktif' : 'Non-Aktif'
        ];
    }
}
