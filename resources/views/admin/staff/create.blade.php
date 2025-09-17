@extends ('Templates.app')

@section('content')
    <div class="mt-5 w-75 d-block m-auto">
        @if (Session::get('failed'))
            <div class="alert alert-danger"> {{ Session::get('failed') }}</div>
        @endif
        <nav data-mdb-navbar-init class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.staffs.index') }}">Staff</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staffs.index') }}">Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="#">Tambah</a> </li>
                    </ol>
                </nav>
            </div>
        </nav>
    </div>

    <div class="card w-75 mx-auto my-3 p-4">
        <h5 class="text-center my-3">Buat Data Pengguna</h5>
        <form action="{{ route('admin.staffs.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="from-label">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="form2Example1"
                    class="form-control @error('email') is-invalid

                @enderror" name="email" />
                <label class="form-label" for="form2Example1">Email address</label>
            </div>

            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="form3Example4" class="form-control @error('password') is-invalid @enderror"
                    name="password"/>
                <label class="form-label" for="form3Example4">Password</label>
            </div>

            <button type="submit" class="btn btn-primary">Buat</button>
        </form>
    </div>
@endsection
