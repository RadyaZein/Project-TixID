@extends('Templates.app')

@section('content')
    <div class="container mt-5">
          @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Sampah pengguna</h3>
        <table class="table table-bordered">
           <tr class="text-center">
                <th>No</th>
                <th>Kode Promo</th>
                <th>discount</th>
                <th>type</th>
                <th>aksi</th>
            </tr>
            @foreach ($promoTrash as $key => $promo)
                <tr class="text-center">
                     <td>{{ $loop->iteration }}</td>
                        <td>{{ $promo->promo_code }}</td>
                        <td>{{ $promo->discount }}</td>
                        <td>{{ $promo->type }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <form action="{{route('staff.promos.restore',$promo['id'])}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success mt-2">Kembalikan</button>
                        </form>
                        <form action="{{ route ('staff.promos.delete_permanent', $promo['id'])}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mt-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
