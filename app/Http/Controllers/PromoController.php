<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use App\Exports\PromoExport;
use Maatwebsite\Excel\Facades\Excel;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string|unique:promos,promo_code',
            'discount'   => 'required|integer|min:1',
            'type'       => 'required|in:percent,rupiah',
        ], [
            'promo_code.required' => 'Kode promo wajib diisi.',
            'promo_code.unique'   => 'Kode promo sudah digunakan.',
            'discount.required'   => 'Diskon wajib diisi.',
            'discount.integer'    => 'Diskon harus berupa angka.',
            'type.required'       => 'Jenis diskon harus dipilih.',
            'type.in'             => 'Jenis diskon hanya boleh berupa percent atau rupiah.',
        ]);

        Promo::create([
            'promo_code' => $request->promo_code,
            'discount'   => $request->discount,
            'type'       => $request->type,
            'actived'    => 1, // default aktif
        ]);

        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $promo = Promo::findOrFail($id);
        return view('staff.promo.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'promo_code' => 'required|string|unique:promos,promo_code,' . $id,
            'discount'   => 'required|integer|min:1',
            'type'       => 'required|in:percent,rupiah',
        ]);

        $promo->update([
            'promo_code' => $request->promo_code,
            'discount'   => $request->discount,
            'type'       => $request->type,
            'actived'    => $request->actived ?? $promo->actived,
        ]);

        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Toggle promo active status.
     */
    public function toggle($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->actived = $promo->actived ? 0 : 1; // toggle aktif/nonaktif
        $promo->save();

        return redirect()->route('staff.promos.index')
            ->with('success', 'Status promo berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect()->route('staff.promos.index')->with('success', 'Promo berhasil dihapus.');
    }

    public function export()
    {
    return Excel::download(new PromoExport, 'promos.xlsx');
    }
}
