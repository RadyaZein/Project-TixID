@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }} </div>
        @endif
        @if (Session::get('failed'))
            <div class="alert alert-danger"> {{ Session::get('failed') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.trash') }}" class="btn btn-danger me-2">Data Sampah</a>
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary me-2">Export (.xls)</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Film</h5>
        <table class="table table-bordered" id="movieTable">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th class="text-center">Poster</th>
                    <th class="text-center">Judul Film</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            @foreach ($movies as $key => $movie)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $movie['poster']) }}" width="120">
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>
                        @if ($movie['actived'])
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Non Aktif</span>
                        @endif
                    </td>
                    <td class="d-flex justify-content-center">
                        <button class="btn btn-secondary me-3" onclick="showModal({{ $movie }})">Detail</button>
                        <a href="{{ route('admin.movies.edit', $movie['id']) }}" class="btn btn-primary me-2">Edit</a>

                        {{-- tombol nonaktif --}}
                        @if ($movie['actived'])
                            <form action="{{ route('admin.movies.nonaktif', $movie['id']) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-warning me-2">Non Aktif</button>
                            </form>
                        @endif

                        {{-- tombol hapus --}}
                        <form action="{{ route('admin.movies.delete', $movie['id']) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <!-- Modal -->
        <div class="modal fade" id="modaDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Film</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- mengisi stack dari templte --}}
@push('script')
    <script>
        function showModal(movie) {
            // console.log(movie);
            let image = "{{ asset('storage/') }}" + "/" + movie.poster;
            //backtip ('') : membuat string yang bisa di enter
            let content = `
            <div class="d-block mx-auto my-2">
                <img src="${image}" width="120">
            </div>
            <ol>
                <li>Judul : ${movie.title}</li>
                <li>Durasi : ${movie.duration} Menit</li>
                <li>Genre : ${movie.genre}</li>
                <li>Sutradara : ${movie.director}</li>
                <li>Usia Minimal : <span class="badge badge-danger">${movie.age_rating}+</span></li>
                <li>Sinopsis : ${movie.description}</li>
                </ol>
                `;

            // memanggil variabel pada tanda '' pake ${}
            // memanggil element HTML yang akan disimpan konten diatas -> document.querySelector
            // innerHtml -> mengisi konten HTML
            document.querySelector('#modalDetailBody').innerHTML = content;
            // memunculkan modal
            new bootstrap.Modal(document.querySelector('#modaDetail')).show();
        }

        $(function() {
            $('#movieTable').DataTable({
                processing: true,
                //data untuk datatable di proses secara serverside (controller)
                serverSide: true,
                //routing menuju fungsi yang memproses data untuk datatable
                ajax: "{{ route('admin.movies.datatables') }}",
                // urutan kolom (td) pada datatable dari rawColumns,
                // atau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'poster_img',
                        name: 'poster_img',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'actived_badge',
                        name: 'actived_badge',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
