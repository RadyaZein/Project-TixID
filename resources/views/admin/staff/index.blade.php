@extends ('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.staffs.trash')}}" class="btn btn-danger me-2">Data Sampah</a>
            <a href="{{ route('admin.staffs.export') }}" class="btn btn-secondary me-2">Export (.xls)</a>
            <a href="{{ route('admin.staffs.create') }}" class="btn btn-success">Tambah Data</a>
        </div>

        <h5>Data Petugas</h5>
        <table class="table table-bordered" id="staffTable">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
<script>
$(function() {
    $('#staffTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.staffs.datatables') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role_badge', name: 'role_badge', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
