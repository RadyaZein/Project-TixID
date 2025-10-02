<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use carbon\Carbon;

class StaffExport implements FromCollection, WithHeadings, WithMapping
{
    private $key = 0;

    public function collection()
    {
        // urutkan berdasarkan role
        return User::orderBy('role', 'asc')->get();
    }

    public function headings(): array
    {
        return ['NO', 'Nama Pengguna', 'Email', 'Role', 'Tanggal Bergabung'];
    }

    public function map($staff): array
    {
        return [
            ++$this->key,
            $staff->name,
            $staff->email,
            ucfirst($staff->role),
            carbon::parse($staff->created_at)->format('d-m-Y'),
         ];
    }
}
