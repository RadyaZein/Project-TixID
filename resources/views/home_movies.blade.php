@extends('Templates.app')

@section('content')
    <div class="bg-black min-vh-100 py-5">
        <div class="container">

            {{-- Form Pencarian + Tombol Kembali --}}
            <form action="#" method="GET" class="mb-5">
                <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 20px;">
                    {{-- Tombol Kembali ke Halaman Utama (di kiri) --}}
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
                        Kembali
                    </a>

                    {{-- Input pencarian --}}
                    <input type="text" name="search_movie" value="{{ request('search_movie') }}"
                        placeholder="Cari film..." class="form-control w-50 rounded-pill px-4 py-2 border-0 shadow-sm"
                        style="max-width: 500px;">

                    {{-- Tombol submit --}}
                    <button type="submit" class="btn btn-info    rounded-pill px-4 fw-semibold">
                        Cari
                    </button>
                </div>
            </form>

            <div class="d-flex flex-wrap justify-content-center gap-4">
                @foreach ($movies as $movie)
                    <div class="card shadow-lg border-0 rounded-3 movie-card bg-black text-light position-relative overflow-hidden"
                        style="width: 15rem;">

                        {{-- Wrapper gambar + overlay --}}
                        <div class="image-wrapper position-relative overflow-hidden">
                            <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top rounded-top movie-img"
                                alt="{{ $movie->title }}"
                                style="height: 350px; object-fit: cover; transition: transform 0.5s ease;">

                            {{-- Overlay muncul saat hover --}}
                            <div class="overlay">
                                <h5 class="overlay-text">TIXID</h5>
                            </div>
                        </div>

                        {{-- Tombol Beli Tiket --}}
                        <div class="card-body p-0">
                            <p class="card-text text-center bg-dark py-2 m-0">
                                <a href="{{ route('schedules.detail', $movie->id) }}"
                                    class="text-light fw-semibold text-decoration-none">
                                    Beli Tiket
                                </a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <footer class="bg-dark text-light">
        <div class="text-center p-3">
            © 2050 Copyright:
            <a class="text-primary" href="https://www.instagram.com/dyaazein/">tixid.com</a>
        </div>
    </footer>

    {{-- CSS --}}
    <style>
        body {
            background-color: #000 !important;
        }

        .movie-card {
            transition: box-shadow 0.4s ease;
        }

        .movie-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
        }

        .image-wrapper {
            position: relative;
            overflow: hidden;
        }

        /* Efek zoom halus pada gambar */
        .movie-card:hover .movie-img {
            transform: scale(1.07);
        }

        /* Overlay hitam semi transparan */
        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .overlay-text {
            font-size: 2rem;
            font-weight: 800;
            color: #E50914;
            /* merah Netflix */
            letter-spacing: 2px;
            text-shadow:
                0 0 10px rgba(229, 9, 20, 0.7),
                /* glow merah */
                0 0 20px rgba(80, 0, 0, 0.8);
            /* glow gelap merah */
            transition: transform 0.3s ease;
            transform: scale(1.1);
        }




        /* Saat hover munculkan overlay dan teks */
        .movie-card:hover .overlay {
            opacity: 1;
        }

        .movie-card:hover .overlay-text {
            transform: scale(1);
        }
    </style>
@endsection
