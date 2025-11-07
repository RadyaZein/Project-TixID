@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Sampah Pengguna</h3>
            <div>
                <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary me-2">Kembali</a>
                @if (count($staffsTrash) > 0)
                    <form action="{{ route('admin.staffs.delete_all_permanent') }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus semua data staff secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus Semua</button>
                    </form>
                @endif
            </div>
        </div>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>#</th>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            @foreach ($staffsTrash as $key => $staffs)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $staffs['name'] }}</td>
                    <td>{{ $staffs['email'] }}</td>
                    <td>{{ $staffs['role'] }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <form action="{{ route('admin.staffs.restore', $staffs['id']) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success mt-2">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.staffs.delete_permanent', $staffs['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
