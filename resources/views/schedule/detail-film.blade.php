@extends ('Templates.app')

@section('content')
    <div class="container mt-5">
        <div class="w-75 d-block m-auto">
            {{-- Poster + Detail Film --}}
            <div class="mb-5 ms-5 d-block" style="width: 150px; height: 200px">
                <img src="https://i.pinimg.com/736x/91/cb/6b/91cb6b2002507c25efb4e760c60a4ad3.jpg" alt="This is Roblox" class="w-100 rounded">
            </div>
            <div class="ms-5 mt-4">
                <h5 class="fw-bold">Kisah Seram Roblox</h5>
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><b class="text-secondary">Genre</b></td>
                        <td class="ps-3">Action, Advanture, Comedy</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Durasi</b></td>
                        <td class="ps-3">2 Jam 30 Menit</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Sutradara</b></td>
                        <td class="ps-3">Tom Lembong</td>
                    </tr>
                    <tr>
                        <td><b class="text-secondary">Rating Usia</b></td>
                        <td class="ps-3"> <span class="badge bg-danger">17+</span></td>
                    </tr>
                </table>
            </div>
        </div>
    @endsection
