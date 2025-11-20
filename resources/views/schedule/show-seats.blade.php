@extends('Templates.app')

@section('content')
    <div class="container card my-5 p-4" style="margin-bottom: 10% !important">
        <div class="card-body">
            <div>
                <b>{{ $schedule['cinema']['name'] }}</b>
                {{-- now() ambil tanggal hari ini, format d (tgl) f (nama bulan) y (tahun) --}}
                <br>
                <b>{{ now()->format('d F Y') }} || {{ $hour }}</b>
            </div>
            <div class="alert my-4 alert-secondary">
                <i class="fa-solid fa-info text-danger me-3"></i>anak usia 2 tahun ke atas wajib membeli tiket.
            </div>
            <div class="d-flex justify-content-center">
                <div class="row w-75">
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; Background: #112646"></div>Kursi Tersedia
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; Background: #eaeaea"></div>Kursi Terjual
                    </div>
                    <div class="col-4 d-flex">
                        <div style="width: 20px; height: 20px; Background: blue"></div>Kursi Dipilih
                    </div>
                </div>
            </div>
            @php
                // membuat data A-H untuk baris kursi
                $row = range('A', 'H');
                // membuat data 1-18 untuk nomor kursi
                $col = range(1, 18);
            @endphp
            {{-- looping baris A-H --}}
            @foreach ($row as $baris)
                <div class="d-flex justify-content-center">
                    {{-- looping angka kursi --}}
                    @foreach ($col as $nomorKursi)
                        {{-- jika kursi nomor 7 kasi space kosong untuk jalan kurso --}}
                        @if ($nomorKursi == 7)
                            <div style="width: 55px"></div>
                        @endif
                        @if ($nomorKursi == 15)
                            <div style="width: 55px"></div>
                        @endif
                        @php
                            $seat = $baris . '-' . $nomorKursi;
                        @endphp
                        @if (in_array($seat, $seatsFormat))
                            <div
                                style="background: #eaeaea; color: black; text-align: center; padding-top: 10px; width: 45px; height: 45px; border-radius: 10px;
                        margin: 10px 3px; cursor: pointer">
                                {{ $baris }}-{{ $nomorKursi }}
                            </div>
                        @else
                            <div style="background: #112646; color: white; text-align: center; padding-top: 10px; width: 45px; height: 45px; border-radius: 10px;
                        margin: 10px 3px; cursor: pointer"
                                onclick="selectedSeats('{{ $schedule->price }}', '{{ $baris }}', '{{ $nomorKursi }}', this)">
                                {{ $baris }}-{{ $nomorKursi }}
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="fixed-bottom w-100 bg-light text-center pt-4">
        <b class="text-center">LAYAR BIOSKOP</b>
        <div class="row mt-4" style="border: 1px solid #eaeaea">
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea">
                <h5>Total Harga</h5>
                <h5 id="totalPrice">Rp. -</h5>
            </div>
            <div class="col-6 p-4 text-center" style="border: 1px solid #eaeaea">
                <h5>Tempat duduk</h5>
                <h5 id="seats">Belum dipilih</h5>
            </div>
        </div>
        {{-- input hidden yang disembunyikan hanya untuk menyimpan nilai yang diperlukan JS untuk proses tambah data ticker --}}
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="hous" id="hours" value="{{ $hour }}">

        <div class="text-center p-2 w-100" style="cursor: pointer" id="btnOrder"><b>RINGKASAN ORDER</b></div>
    </div>
@endsection
@push('script')
    <script>
        // menyimpan data kursi yang dipilih
        let seats = [];
        let totalPrice = 0;

        function selectedSeats(price, baris, nomorKursi, element) {
            // buat A-1
            let seat = baris + '-' + nomorKursi;
            // cek apakah kursi sudah dipilih sebelumnya, cek dari apakah ada di array seats atau ngga jika kembalikan indexnya (indexOf)
            let indexSeat = seats.indexOf(seat);
            // jika tidak ada berarti kursi baru dipilih. kalu tidak ada indexnya -1
            if (indexSeat === -1) {
                // kalau gada kasi warna biru terang dan simpan data kursi ke array diatas
                element.style.background = 'blue';
                seats.push(seat);
            } else {
                // jika ada, berarti ini diklick kedua kali di kursi tsb. kembalikan ke warna biru tua dan hapus item dari array
                element.style.background = '#112646';
                seats.splice(indexSeat, 1);
            }

            let totalPriceElement = document.querySelector("#totalPrice");
            let seatsElement = document.querySelector("#seats");
            // hitung harga dari parameter dikali jumlah kursi yang dipilih
            totalPrice = price * (seats.length); // lenght : menghitung jumlah item array
            // simpan harga di element html
            totalPriceElement.innerText = "Rp. " + totalPrice;
            // join(", ") : mengubah array jadi sting dipishkan dengan tand tertentu
            seatsElement.innerText = seats.join(", ");

            let btnOrder = document.querySelector("#btnOrder");
            // seats array isinya lebih dari sama dengan satu, aktifin btnOrder
            if (seats.length >= 1) {
                btnOrder.style.background = '#112646';
                btnOrder.style.color = 'yellow';
                // untuk mengarahkan ke proses create ticket
                btnOrder.onclick = createTicket;
            } else {
                btnOrder.style.background = '';
                btnOrder.style.color = '';
            }
        }

        function createTicket() {
            // AJAX (Asynchronous JavaScript And XML)
            // Digunakan untuk menambah/mengambil data dari database tanpa reload halaman
            $.ajax({
                url: "{{ route('tickets.store') }}", //route untuk proses data
                method: "POST", //http method sesuaii url
                data: {
                    // data yg mau dikirim ke route (kalo di html, input form)
                    _token: "{{ csrf_token() }}",
                    user_id: $("#user_id").val(), //value+"" dr input id="user_id"
                    schedule_id: $("#schedule_id").val(),
                    hours: $("#hours").val(),
                    quantity: seats.length, // jumlah item array seats
                    total_price: totalPrice,
                    rows_of_seats: seats,
                    //fillable : value
                },
                success: function(response) { //kalau berhasil, mau ngapain. data hasil disimpan di respone
                    // console.log(response)
                    // redirect JS : window.location.href
                    // response messae & data
                    let ticketId = response.data.id;
                    window.location.href = `/tickets/${ticketId}/order`;
                },
                error: function(message) { //kalau diservernya ada error mau ngapain
                    alert("Terjadi kesalahan ketika membuat data ticket!");
                }
            })
        }
    </script>
@endpush
