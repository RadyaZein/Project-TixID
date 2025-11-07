{{-- memanggil file content --}}
@extends('Templates.app')

{{-- mengisi yeild --}}
@section('content')
    @if (Session::get('sucsess'))
        <div class="alert alert-success w-100">{{ Session::get('sucsess') }}<b>
                Selamat Datang {{ Auth::user()->name }}</b></div>
    @endif

    @if (Session::get('logout'))
        <div class="alert alert-warning w-100">{{ Session::get('logout') }}</div>
    @endif

    <div class="bg-balck">
        {{-- Dropdown lokasi --}}
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle w-100 d-flex align-items-center" type="button"
                id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                <i class="fa-solid me-2 text-info">Location</i>
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">Bogor</a></li>
                <li><a class="dropdown-item" href="#">Tanggerang</a></li>
                <li><a class="dropdown-item" href="#">Banten</a></li>
            </ul>
        </div>

        {{-- Carousel --}}
        <div id="carouselExampleIndicators" class="carousel slide" data-mdb-ride="carousel" data-mdb-carousel-init>
            <div class="carousel-indicators">
                <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-mdb-target="#carouselExampleIndicators" data-mdb-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://i.pinimg.com/1200x/15/9b/e0/159be0923b9ba6325b73ef5d4e13497c.jpg"
                        class="d-block w-100" style="height: 620px; object-fit: cover" alt="Wild Landscape" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/2e/57/ba/2e57baffa9dab1cf8ccd199aa7686202.jpg"
                        class="d-block w-100" style="height: 620px; object-fit: cover" alt="Camera" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/0b/77/25/0b7725a59caa1daacebfe1fc31b6deec.jpg" class="d-block w-100"
                        style="height: 620px; object-fit: cover" alt="Exotic Fruits" />
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleIndicators"
                data-mdb-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleIndicators"
                data-mdb-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        {{-- Judul dan tombol navigasi --}}
        <div class="d-flex justify-content-between container mt-4">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-clapperboard text-light"></i>
                <h5 class="mt-2 text-light">Sedang Tayang</h5>
            </div>

            <div>
                <a href="{{ route('home.movies') }}" class="btn btn-info rounded-pill">Semua
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <div class="d-flex-end gap-2 container">
            <button type="button" class="btn btn-outline-primary rounded-pill">Semua Film</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill">GUIDO CINEMASX</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill">Cinemapolis</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill">XXI</button>
        </div>

        {{-- Daftar film --}}
        <div class="mt-4 d-flex flex-wrap justify-content-center gap-4">
            @foreach ($movies as $movie)
                <div class="card shadow-lg border-0 rounded-3 movie-card bg-black text-light position-relative overflow-hidden"
                    style="width: 15rem;">
                    <div class="image-wrapper position-relative overflow-hidden">
                        <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top rounded-top movie-img"
                            alt="{{ $movie->title }}"
                            style="height: 350px; object-fit: cover; transition: transform 0.5s ease;">

                        <div class="overlay">
                            <h5 class="overlay-text">TIXID</h5>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <p class="card-text text-center py-2 m-0 bg-dark fw-bold">
                            <a href="{{ route('schedules.detail', $movie->id) }}"
                                class="text-light fw-semibold text-decoration-none">
                                Beli Tiket
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- STYLE --}}
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

            .movie-card:hover .movie-img {
                transform: scale(1.07);
            }

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
                color:#1151b2;
                /* biru utama */
                letter-spacing: 2px;
                text-shadow: 0 0 10px rgba(0, 123, 255, 0.7),
                    0 0 20px rgba(103, 103, 103, 0.8);
                /* efek glow biru gelap */
                transition: transform 0.3s ease;
                transform: scale(1.1);
            }




            .movie-card:hover .overlay {
                opacity: 1;
            }

            .movie-card:hover .overlay-text {
                transform: scale(1);
            }
        </style>

        {{-- Footer --}}
        <footer class="bg-dark text-light text-lg-start mt-4">
            <div class="text-center p-3">
                © 2050 Copyright:
                <a class="text-primary" href="https://www.instagram.com/dyaazein/">tixid.com</a>
            </div>
        </footer>
    </div>
@endsection
