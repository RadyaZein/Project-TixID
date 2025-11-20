<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Ticket;
use App\Models\TicketPayment;
use App\Models\Schedule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
   public function showSeats($scheduleId, $hourId) {
        // dd($scheduleId, $hourId);
        $schedule = Schedule::where('id', $scheduleId)->with('cinema')->first();
        // jika tidak ada data jam kasi default kosong
        $hour = $schedule['hours'] [$hourId] ?? '-';
        // ambil data kusi  di tiket yang sesuai dengan jam, tgl, dan sudah di bayar
        $seats = Ticket::whereHas('ticketPayment', function($q) {
            // whereDate : mencari bedasarkan tgl
            $q->whereDate('paid_date', now()->format('Y-m-d'));
        })->whereTime('hours', $hour)->pluck('rows_of_seats');
        // pluck() : mengambil hanya dari 1 field, bedanya dengan value() kalau value() ambil 1 data pertama hanya dari field tersebut, kalau pluck() ambil semua data dari field tersebut
        $seatsFormat = array_merge(...$seats);
        // (...) : spread operator : mengeluarkan nilai array
        // spread operator mengeluarkna nilai array  lalu disimpan lagi ke 1 dimensi oleh array-merge
        // dd($seatsFormat);
        return view('schedule.show-seats', compact('schedule', 'hour','seatsFormat'));
   }

   public function chartData()
   {
    // ambil data dibulan ini
    $month = now()->format('m'); //bulan saat ini
    // ambil data tiket yang sudah dibayar dan dibayar di bulan ini, kemudian kelompokan datanya bedasarkan tanggal pembayaran (groupBy)
    $tickets = Ticket::whereHas('ticketPayment', function($q) use($month) {
        // whereMonth : mencari bedasarkan bulan
        $q ->whereMonth('paid_date', $month);
    })->get()->groupBy(function($ticket) {
        return \Carbon\Carbon::parse($ticket->ticketPayment->paid_date)->format('Y-m-d');
    })->toArray();
        // $tickets berisi ["tanggal"=>data di tanggal tersebut]
        // pisahkan tanggal untuk lebels di chartJs
        $labels = array_keys($tickets);
        // hitung isi data di key  tanggal tersebut, untuk data di chartJs
        $data = [];
        foreach ($tickets as $item) {
            // simpan hasil count() ke array data
            array_push($data, count($item));
        }
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
   }
    public function index()
    {
        $ticketActive = Ticket::whereHas('ticketPayment', function($q) {
            $q->whereDate('booked_date', now()->format('Y-m-d'))->where('paid_date',
            '<>', NULL);
        })->get();
        // <> tidak sama dengan
        $ticketNonActive = Ticket::whereHas('ticketPayment', function($q) {
            $q->whereDate('booked_date', '<', now()->format('Y-m-d'))->where('paid_date', '<>', NULL);
        })->get();
        return view('ticket.index', compact('ticketActive', 'ticketNonActive'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'hours' => 'required',
            'total_price' => 'required',
            'quantity' => 'required',
            'rows_of_seats' => 'required',
        ]);
            $createData = Ticket::create([
                'user_id' => $request->user_id,
                'schedule_id' => $request->schedule_id,
                'hours' => $request->hours,
                'total_price' => $request->total_price,
                'quantity' => $request->quantity,
                'rows_of_seats' => $request->rows_of_seats,
                'actived' => 0, // kalau udah dibayar baru di ubha ke 1 (aktif)
                'date' => now()
            ]);
            // karena dia di panggil di ajax jadi returnnya bentuk json
            return response()->json([
                'message' => 'Berhasi membuat data tiket',
                'data' => $createData
            ]);
    }

    public function ticketOrder($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with('schedule.movie', 'schedule.cinema')->first();
        $promos = Promo::where('actived', 1)->get();
        return view('schedule.order', compact('ticket', 'promos'));
    }

    public function ticketPayment(Request $request)
    {
        $kodeBarcode = 'TICKET'. $request->ticket_id;

        $qrImage = QrCode::format('svg')->size(300)->margin(2)->errorCorrection('H')
            ->generate($kodeBarcode);

            // penamaan file
            $filename = $kodeBarcode . '.svg';
            // tempat menyimpan barcode public/barcodes
            $path = 'barcodes/' . $filename;
            Storage::disk('public')->put($path, $qrImage);

            $createData = TicketPayment::create([
                'ticket_id' => $request->ticket_id,
                'barcode' => $path,
                'status' => 'process',
                'booked_date' => now(),
            ]);

            $ticket = Ticket::find($request->ticket_id);
            $totalPrice = $ticket['total_price'];
            if ($request->promo_id != NULL) {
                $promo = Promo::find($request->promo_id);
                if($promo['type'] == 'percent') {
                    $discount = $ticket['total_price'] * ($promo['discount'] / 100);
                } else {
                    $discount = $promo['discount'];
                }
                $totalPrice = $ticket['total_price'] - $discount;
            }
            // update total harga setelah mengunakan diskon
            $updateTicket = Ticket::where('id', $request->ticket_id)->update([
                'promo_id' => $request->promo_id,
                'total_price' => $totalPrice,
            ]);

            return response()->json([
                'message' => 'Berhasil membuat pesanan tiket sementara!',
                'data' => $createData,
            ]);
    }

    public function ticketPaymentPage($ticketId)
{
   $ticket = Ticket::where('id', $ticketId)->with(['promo', 'ticketPayment', 'schedule'])->first();
    return view('schedule.payment', compact('ticket'));

}

    public function paymentProof($ticketId)
    {

        $updateData = Ticket::where('id', $ticketId)->update([
            'actived' => 1,
        ]);
        // karna data hanya ada ticket_id jadi update payment bedasarkan ticket_id nya
        $updatePayment = TicketPayment::where('ticket_id', $ticketId)->update([
            'paid_date' => now()
        ]);
        // karna route receipt perlu ticket_id maka perlu dikirim
        return redirect()->route('tickets.receipt', $ticketId);
    }

    public function ticketReceipt($ticketId)
    {
        $ticket = Ticket::where('id', $ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'ticketPayment'])->first();
        return view('schedule.receipt', compact('ticket'));
    }

    public function exportPdf($ticketId)
    {
        // use/Barryvdh\DomPDF\facade\Pdf;
        // siapkan data. data yang dikirim harus berupa array
        $ticket = Ticket::where('id',$ticketId)->with(['schedule', 'schedule.cinema', 'schedule.movie', 'promo', 'ticketPayment'])->first()->toArray();
        // buat inisial nama yang nanti akan digunakan pada blade pdf
        view()->share('ticket',$ticket);
        // generete file blade yang akan dicetak pdf
        $pdf = Pdf::loadView('schedule.export-pdf', $ticket);
        // untuk pdf dengan nama fie tertentu
        $fileName = 'TICKET' . $ticket['id'] . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
