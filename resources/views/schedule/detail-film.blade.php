@extends('Templates.app')

@section('content')
    <div class="container mt-5">
        <div class="w-75 d-block m-auto">
            {{-- Poster + Detail Film --}}
            <div class="d-flex">
                <div style="width: 150px; height: 200px">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="w-100 rounded">
                </div>
                <div class="ms-5 mt-4">
                    <h5 class="fw-bold">{{ $movie->title }}</h5>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><b class="text-secondary">Genre</b></td>
                            <td class="ps-3">{{ $movie->genre }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Durasi</b></td>
                            <td class="ps-3">{{ $movie->duration }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Sutradara</b></td>
                            <td class="ps-3">{{ $movie->director }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Rating Usia</b></td>
                            <td class="ps-3"><span class="badge bg-danger">{{ $movie->age_rating }}+</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center flex-column align-items-center">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#sinopsis-tab-pane"
                        type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Sinopsis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#jadwal-tab-pane"
                        type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Jadwal</button>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="sinopsis-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                    tabindex="0">
                    <div class="mt-3 w-75 d-block mx-auto" style="text-align: justify">
                        {{ $movie['description'] }}
                    </div>
                </div>
                <div class="tab-pane fade" id="jadwal-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                    tabindex="0">
                    {{-- Ambil schedule dari relasi movie --}}
                    @foreach ($movie['schedules'] as $schedule)
                        <div class=" p-2">
                            <div class="d-flex">
                                {{-- ambil nama cinema dari relasi schedule --}}
                                <i class="fa-solid fa-building"></i> <b>{{ $schedule['cinema']['name'] }}</b>
                            </div>
                            <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                            <br>
                            <div class="d-flex flex-wrap">
                                {{-- looping hours dari schedule --}}
                                @foreach ($schedule['hours'] as $index => $hours)
                                    {{-- this : mengirimkan element html ke js untk di manipulasi --}}
                                    <button class="btn btn-outline-secondary me-2"
                                        onclick="selectedHour('{{ $schedule->id }}', '{{ $index }}', this)">{{ $hours }}</button>
                                @endforeach
                            </div>
                            <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 fixed-bottom bg-light text-center py-2" id="wrapBtn">
        {{-- javascript:void(0) : nonaktoifkan href --}}
        <a href="javascript:void(0)" id="BtnTiket">Beli tiket</a>
    </div>
@endsection

@push('script')
    <script>
        let btnBefore = null;

        function selectedHour(scheduleId, hourId, element) {
            // ada btnBefore (sebelumnya pernah klik btn lain)
            if (btnBefore) {
                // Ubah warna btn yang di klik sebelumnya ke abu abu lagi
                btnBefore.style.background = '';

                btnBefore.style.color = '';

                btnBefore.style.borderColor = '';
            }

            // Ubah warna btn yang di baru diklik sekarang ke biru
            element.style.background = '#112646';

            element.style.color = 'white';

            element.style.borderColor = '#112646';

            // update btnBefore ke element baru

            btnBefore = element;


            let wrapBtn = document.querySelector('#wrapBtn');
            let btnTiket = document.querySelector('#BtnTiket');
            // warna biru di tulisan beli tiket
            wrapBtn.style.background = '#112646';
            // hapus class bg-light
            wrapBtn.classList.remove('bg-light');
            // teks warna putih
            btnTiket.style.color = 'white';

            // mengarahkan ke route web.php
            let url = "{{ route('schedules.seats', ['scheduleId' => ':scheduleId', 'hourId' => ':hourId']) }}". replace (':scheduleId', scheduleId). replace (':hourId', hourId);
            // replace -> mengganti data asli jadi variable js (parameter fungsi). mengisi path dinamis web.php
            // simpa url di href
            btnTiket.href = url;

        }
    </script>
@endpush
