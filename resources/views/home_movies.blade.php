@extends('Templates.app')

@section('content')
    <div class="bg-dark min-vh-100 py-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center gap-4">
                @foreach ($movies as $movie)
                    <div class="card shadow-lg border-0 rounded-3 movie-card bg-black text-light" style="width: 15rem;">
                        <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top rounded-top"
                            alt="{{ $movie->title }}" style="height: 350px; object-fit: cover;" />

                        <div class="card-body p-0">
                            <p class="card-text text-center bg-primary py-2 m-0">
                                <a href="{{ route('schedules.detail', $movie->id) }}"
                                    class="text-warning fw-semibold">
                                    Beli Tiket
                                </a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #000 !important;
            /* bikin seluruh halaman hitam */
        }

        .movie-card {
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection
