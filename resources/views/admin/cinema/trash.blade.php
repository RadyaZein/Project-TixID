@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success w-100 mt-3">{{ Session::get('success')}}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.cinemas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h5>Sampah Data Bioskop</h5>
        <table class="table my-3 table-bordered">
            <tr>
                <th>Nama Bioskop</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            @foreach ($cinemaTrash as $key => $cinema)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $cinema->name }}</td>
                    <td>{{ $cinema->location }}</td>
                    <td class="d-flex justify-content-center gap-2">
                        <form action="{{ route('admin.cinemas.restore', $cinema->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success">Restore</button>
                        </form>
                        <form action="{{ route('admin.cinemas.delete_permanent', $cinema->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
