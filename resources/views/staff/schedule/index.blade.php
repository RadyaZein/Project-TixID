@extends('Templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.schedules.trash') }}" class="btn btn-secondary me-2">Data Sampah</a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Data</button>
        </div>

        @if (Session::get('success'))
            <div class="alert alert-success mt-3">{{ Session::get('success') }}</div>
        @endif

        <h3 class="my-3">Data Jadwal Tayang</h3>
        <table id="scheduleTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bioskop</th>
                    <th>Film</th>
                    <th>Harga</th>
                    <th>Jam Tayang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Jadwal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('staff.schedules.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cinema_id" class="col-form-label">Bioskop:</label>
                            <select name="cinema_id" id="cinema_id" class="form-select @error('cinema_id') is-invalid @enderror">
                                <option disabled hidden selected>Pilih Bioskop</option>
                                @foreach ($cinemas as $cinema)
                                    <option value="{{ $cinema->id }}">{{ $cinema->name }}</option>
                                @endforeach
                            </select>
                            @error('cinema_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="movie_id" class="col-form-label">Film:</label>
                            <select name="movie_id" id="movie_id" class="form-select @error('movie_id') is-invalid @enderror">
                                <option disabled hidden selected>Pilih Film</option>
                                @foreach ($movies as $movie)
                                    <option value="{{ $movie->id }}">{{ $movie->title }}</option>
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
                            @if ($errors->has('hours.*'))
                                <br>
                                <small class="text-danger">{{ $errors->first('hours.*') }}</small>
                            @endif
                            <input type="time" id="hours" name="hours[]" class="form-control @if ($errors->has('hours.*')) is-invalid @endif">
                            <div id="additionalInput"></div>
                            <span class="text-primary mt-2" style="cursor: pointer" onclick="addInput()">+ Tambah Jam</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Datatables --}}
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('staff.schedules.datatables') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'cinema', name: 'cinema' },
                    { data: 'movie', name: 'movie' },
                    { data: 'price', name: 'price' },
                    { data: 'hours', name: 'hours' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function addInput() {
            let content = '<input type="time" name="hours[]" class="form-control mt-2">';
            let wrap = document.querySelector('#additionalInput');
            wrap.innerHTML += content;
        }

        @if ($errors->any())
            let modalAdd = document.querySelector("#modalAdd");
            new bootstrap.Modal(modalAdd).show();
        @endif
    </script>
@endpush
