@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-danger me-2">Data Sampah</a>
            <a href="{{ route('admin.cinemas.export') }}" class="btn btn-secondary me-2">Export (.xls)</a>
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Bioskop</h5>
        <table class="table table-bordered" id="cinemaTable">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Bioskop</th>
                    <th>Lokasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
<script>
    $(function() {
        $('#cinemaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.cinemas.datatables') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'name', name: 'name' },
                { data: 'location', name: 'location' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
