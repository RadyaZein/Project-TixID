@extends('Templates.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold">Daftar Promo</h2>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-end mb-3 flex-wrap gap-2">
        <a href="{{ route('staff.promos.trash') }}" class="btn btn-danger">Data Sampah</a>
        <a href="{{ route('staff.promos.export') }}" class="btn btn-secondary">Export (.xls)</a>
        <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Promo</a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabel Promo --}}
    <table id="promoTable" class="table table-bordered text-center align-middle">
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
                                onclick="return confirm('Yakin ingin menghapus promo ini?')">
                                Hapus
                            </button>
                        </form>

                        {{-- Tombol Nonaktif (hanya jika aktif) --}}
                        @if ($promo->actived)
                            <form action="{{ route('staff.promos.toggle', $promo->id) }}" method="POST" class="d-inline">
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

@push('script')
<script>
    $(document).ready(function() {
        $('#promoTable').DataTable({
            responsive: true,
            language: {
                search: "Cari Promo:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ promo",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            }
        });
    });
</script>
@endpush
