@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }} </div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Film</h5>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Aksi</t h>
            </tr>
            @foreach ($movies as $key => $movie)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $movie['poster']) }}" width="120">
                    </td>
                    <td>{{ $movie['title'] }}</td>
                    <td class="d-flex justify-content-center">
                        {{-- oneclick : Menjalankan fungsi JavaScript ketika komponen di klik --}}
                        <button class="btn btn-secondary me-2" onclick="showModal({{ $movie }})">Detail</button>
                        <a href="{{route('admin.movies.edit', $movie['id']) }}" class="btn btn-primary">Edit</a>
                        <form>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        {{-- jika actived true, maka memunculkan aksi untuk non aktif film --}}
                        @if ($movie['actived'])
                            <a href="" class="btn btn-warning">Non Aktif</a>
                        @endif
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
    </script>
@endpush
