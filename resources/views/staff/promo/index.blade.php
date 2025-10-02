@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        <h2>Daftar Promo</h2>
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary me-3 mb-3">Export (.xls)</a>
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success mb-3">Tambah Promo</a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Promo</th>
                    <th>Diskon</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promos as $promo)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $promo->promo_code }}</td>
                        <td>{{ $promo->discount }}</td>
                        <td>{{ $promo->type }}</td>
                        <td>
                            @if ($promo->actived)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Edit --}}
                            <a href="{{ route('staff.promos.edit', $promo->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('staff.promos.destroy', $promo->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus promo?')">
                                    Hapus
                                </button>
                            </form>

                            {{-- Tombol Nonaktif (hanya muncul kalau promo masih aktif) --}}
                            @if ($promo->actived)
                                <form action="{{ route('staff.promos.toggle', $promo->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-warning btn-sm">Nonaktif</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
