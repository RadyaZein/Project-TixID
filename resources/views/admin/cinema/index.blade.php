@extends ('Templates.app')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show alert-top-right" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <div class="d-flex justify-content-end mb-3 mt-4">
            @if (Session::get('failed'))
                <div class="alert alert-danger"> {{ Session::get('failed') }}</div>
            @endif
            <a href="{{ route('admin.cinemas.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5>Data Bioskop</h5>
        <table class="table my-3 table-bordered">
            <tr>
                <th class="text-center">NO.</th>
                <th class="text-center">Nama Bioskop</th>
                <th class="text-center">Lokasi</th>
                <th class="text-center">Aksi</th>
    </div>
    @foreach ($cinema as $key => $cinema)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $cinema->name }}</td>
            <td>{{ $cinema->location }}</td>
            <td class="d-flex justify-content-center gap-2">
                <a href="{{ route('admin.cinemas.edit', $cinema->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('admin.cinemas.delete', $cinema->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@endsection
