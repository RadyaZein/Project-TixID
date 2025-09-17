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
                        class="d-block w-100" height="550px" style="object-fit: cover" alt="Wild Landscape" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/f0/c5/a3/f0c5a3dd213f2ffb5e0dc099feb0e78d.jpg" class="d-block w-100"
                        height="550px" style="object-fit: cover" alt="Camera" />
                </div>
                <div class="carousel-item">
                    <img src="https://i.pinimg.com/736x/59/63/a9/5963a9765d39148e6ea6a00073de8ad3.jpg" class="d-block w-100"
                        height="550px" style="object-fit: cover" alt="Exotic Fruits" />
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
                <a href="#" class="btn btn-warning rounded-pill">Semua <i class="fa-solid fa-angle-right"></i></a>
            </div>
        </div>

        <div class="d-flex gap-2 container">
            <button type="button" class="btn btn-outline-primary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">Semua Film</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">IMAX</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">Cinemapolis</button>
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-mdb-ripple-init
                data-mdb-ripple-color="dark">XXI</button>
        </div>

        <div class="mt-3 d-flex justify-content-center gap-2">
            <div class="card" style="width: 18rem;">
                <img src="https://i.pinimg.com/736x/5f/31/6b/5f316b50220823ac81376f3cee4cb579.jpg" class="card-img-top"
                    alt="Sunset Over the Sea" />
                {{-- !important : memprioritaskan, jika ada style padding dari akan dibaca yang di style --}}
                <div class="card-body" style="padding: 0 !important">
                    <a href="{{ route('schedules.detail') }}" class="btn btn-warning w-100 fw-bold text-dark">Beli Tiket</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img src="https://i.pinimg.com/1200x/d9/45/82/d94582611c1251133f66ed31fea24217.jpg" class="card-img-top"
                    alt="Sunset Over the Sea" />
                {{-- !important : memprioritaskan, jika ada style padding dari akan dibaca yang di style --}}
                <div class="card-body" style="padding: 0 !important">
                    <a href="{{ route('schedules.detail') }}" class="btn btn-warning w-100 fw-bold text-dark">Beli
                        Tiket</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img src="https://i.pinimg.com/1200x/f5/8d/b7/f58db70db0c100f892fc75a0889c5146.jpg" class="card-img-top"
                    alt="Sunset Over the Sea" />
                {{-- !important : memprioritaskan, jika ada style padding dari akan dibaca yang di style --}}
                <div class="card-body" style="padding: 0 !important">
                    <a href="{{ route('schedules.detail') }}" class="btn btn-warning w-100 fw-bold text-dark">Beli
                        Tiket</a>
                </div>
            </div>

            <div class="card" style="width: 18rem;">
                <img src="https://i.pinimg.com/736x/6c/ac/87/6cac87767fa12a06f5f937412ea86770.jpg" class="card-img-top"
                    alt="Sunset Over the Sea" />
                {{-- !important : memprioritaskan, jika ada style padding dari akan dibaca yang di style --}}
                <div class="card-body" style="padding: 0 !important">
                    <a href="{{ route('schedules.detail') }}" class="btn btn-warning w-100 fw-bold text-dark">Beli
                        Tiket</a>
                </div>
            </div>
        </div>

        <footer class="bg-body-tertiary text-center text-lg-start mt-4">

            <div class="text-center p-3" style="background-color: #E4A11B;">
                © 2050 Copyright:
                <a class="text-body" href="https://mdbootstrap.com/">tixid.com</a>
            </div>

        </footer>
    </div>
@endsection
