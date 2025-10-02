@extends('Templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            {{-- modal add dimunculkan dengan bootsrap karena tidk memerlukan data dinamis di modal --}}
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
        </div>
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <h3 class="my-3">Data Jadwal Tayang</h3>
        <table class="table table-bordered">
            <tr>
                <th>NO</th>
                <th>Bioskop</th>
                <th>Film</th>
                <th>Harga</th>
                <th>Jam Tayang</th>
                <th>Aksi</th>
            </tr>
            @foreach ($schedules as $key => $schedule)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- ambil detail relasi dari with() : $item['namarelasi'] ['data'] --}}
                    <td>{{ $schedule['cinema']['name'] }}</td>
                    <td>{{ $schedule['movie']['title'] }}</td>
                    <td>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</td>
                    <td>
                        {{-- karena hours array,akses dengan looping --}}
                        <ul>
                            @foreach ($schedule['hours'] as $hours)
                                {{-- bentukanya ['11.00', '12.00'] ga perly key untuk akses --}}
                                <li>{{ $hours }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('staff.schedules.edit', $schedule['id']) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('staff.schedules.delete', $schedule['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger ms-2">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- Modal --}}
        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">New message</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('staff.schedules.store') }}">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="cinema_id" class="col-form-label">Bioskop:</label>
                                <select name="cinema_id" id="cinema_id"
                                    class="form-select @error('cinema_id') is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Bioskop</option>
                                    {{-- loop option sesuai data $cinemas --}}
                                    @foreach ($cinemas as $cinema)
                                        {{-- yang diambil id nya (vale), yang di muncullin namenya  --}}
                                        <option value="{{ $cinema['id'] }}"> {{ $cinema['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('cinema_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="movie_id" class="col-form-label">Film:</label>
                                <select name="movie_id" id="movie_id"
                                    class="form-select @error('movie_id') is-invalid @enderror">
                                    <option disabled hidden selected>Pilih Film</option>
                                    @foreach ($movies as $movie)
                                        <option value="{{ $movie['id'] }}">{{ $movie['title'] }}</option>
                                    @endforeach
                                </select>
                                @error('movie_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="col-form-label">Harga:</label>
                                <input type="number" id="price" name="price"
                                    class="form-control @error('price') is-invalid @enderror">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="hours" class="col-form-label">Jam Tayang:</label>
                                {{-- karna hours array, err validasi diambil dengan : --}}
                                {{-- $error->has() :: jika dari err validasi ada err item hours --}}
                                @if ($errors->has('hours.*'))
                                    <br>
                                    <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                                @endif
                                <input type="time" id="hours" name="hours[]"
                                    class="form-control @if ($errors->has('hours.*')) is-invalid @endif">
                                {{-- sediakan tempat konten tambahan dari JS --}}
                                <div id="additionalInput"></div>
                                <span class="text-primary mt-2" style="cursor: pointer" onclick="addInput()">+ Tambah
                                    Jam</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @push('script')
        <script>
            function addInput() {
                let content = '<input type="time" name="hours[]" class="form-control mt-2">';
                let wrap = document.querySelector('#additionalInput');
                wrap.innerHTML += content;
            }
        </script>
        {{-- pengecekan php kalau ada error validasi apapun --}}
        @if ($errors->any())
            <script>
                let modalAdd = document.querySelector("#modalAdd");
                // Munculkan modal dengan js
                new bootstrap.Modal(modalAdd).show();
            </script>
        @endif
    @endpush
