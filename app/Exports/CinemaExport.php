<?php

namespace App\Exports;

use App\Models\Cinema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CinemaExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    /**
     * Ambil semua data Cinema
     */
    public function collection()
    {
        return Cinema::all();
    }

    /**
     * Judul kolom (TH) di Excel
     */
    public function headings(): array
    {
        return ['NO', 'NAMA BIOSKOP', 'LOKASI'];
    }

    /**
     * Isi baris (TD) di Excel
     */
    public function map($cinema): array
    {
        return [
            ++$this->key,
            $cinema->name,
            $cinema->location,
        ];
    }
}
