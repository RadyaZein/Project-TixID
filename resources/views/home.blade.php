{{-- memanggil file content --}}
@extends('Templates.app')

{{-- mengisi yeild --}}
@section('content')
    @if (Session::get('sucsess'))
        {{-- Auth::User() : mengambil data yang login --}}
        {{-- format : Auth::user->coulmn_di_fillable  --}}
        <div class="alert alert-success w-100">{{ Session::get('sucsess') }}<b>
                Selamat Datang {{ Auth::user()->name }}</b></div>
    @endif
    @if (Session::get('logout'))
        <div class="alert alert-warning w-100">{{ Session::get('logout') }}</div>
    @endif
    <div class="bg-dark">
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle w-100 d-flex align-items-center" type="button"
                id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                <i class="fa-solid fa-location-dot me-2 text-primary">Location</i>
            </button>
            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#">Bogor</a></li>
                <li><a class="dropdown-item" href="#">Tanggerang</a></li>
                <li><a class="dropdown-item" href="#">Banten</a></li>
            </ul>
        </div>

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
                        class="d-block w-100" style="height: 650px; object-fit: cover" alt="Wild Landscape" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/1200x/b8/2d/80/b82d8010c27cec5320fa2d1c8c78bc63.jpg"
                        class="d-block w-100" style="height: 650px; object-fit: cover" alt="Camera" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/9e/df/cb/9edfcb871f1a0eaaea23f77385a759e0.jpg"
                        class="d-block w-100" style="height: 650px; object-fit: cover" alt="Exotic Fruits" />
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
        <div class="d-flex justify-content-between container mt-4">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-clapperboard text-light"></i>
                <h5 class="mt-2 text-light">Sedang Tayang</h5>
            </div>

            <div>
                <a href="{{ route('home.movies') }}" class="btn btn-warning rounded-pill">Semua <i
                        class="fa-solid fa-angle-right"></i></a>
            </div>

        </div>

        <div class="d-flex-end gap-2 container">
            <button type="button" class="btn btn-outline-primary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">Semua Film</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">IMAX</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">Cinemapolis</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">XXI</button>
        </div>

        <div class="mt-4 d-flex flex-wrap justify-content-center gap-4">
            @foreach ($movies as $movie)
                <div class="card shadow-lg border-0 rounded-3 movie-card" style="width: 15rem;">
                    <img src="{{ asset('storage/' . $movie->poster) }}" class="card-img-top rounded-top"
                        alt="{{ $movie->title }}" style="height: 350px; object-fit: cover;" />

                    <div class="card-body p-0">
                        <p class="card-text text-center bg-primary py-2 m-0">
                            <a href="{{ route('schedules.detail', $movie['id']) }}"
                                class="text-warning fw-semibold text-decoration-none">
                                Beli Tiket
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .movie-card {
                transition: transform 0.2ss ease, box-shadow 0.3s ease;
            }

            .movie-card:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            }
        </style>



        <footer class="bg-body-tertiary text-center text-lg-start mt-4">

            <div class="text-center p-3" style="background-color: #E4A11B;">
                © 2050 Copyright:
                <a class="text-body" href="https://mdbootstrap.com/">tixid.com</a>
            </div>

        </footer>
    </div>
@endsection
