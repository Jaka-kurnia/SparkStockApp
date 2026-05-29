<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\StokTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $stockTransactions = StokTransaction::with(['sparepart', 'supplier', 'user'])
            ->latest()
            ->get();
        $spareparts = Sparepart::where('stock', '>', 0)->get();
        $suppliers = Supplier::all();
        return view('stock_transaction.index', compact('stockTransactions', 'spareparts', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $spareparts = Sparepart::orderBy('name', 'asc')->get();
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return view('stock_transaction.create', compact('spareparts', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'type' => 'required|in:in,out,adjustment',
            'qty' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'supplier_id' => $request->type === 'in' ? 'nullable|exists:suppliers,id' : 'prohibited',
            'notes' => 'nullable|string|max:500',
        ], [
            'sparepart_id.required' => 'Sparepart harus diisi',
            'sparepart_id.exists' => 'Sparepart tidak ditemukan',
            'type.required' => 'Type harus diisi',
            'type.in' => 'Type harus in, out, atau adjustment',
            'qty.required' => 'Jumlah harus diisi',
            'qty.integer' => 'Jumlah harus berupa bilangan bulat',
            'qty.min' => 'Jumlah minimal adalah 1',
            'price_per_unit.required' => 'Harga per unit harus diisi',
            'price_per_unit.numeric' => 'Harga per unit harus berupa angka',
            'price_per_unit.min' => 'Harga per unit minimal adalah 0',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'notes.max' => 'Notes maksimal 500 karakter',
        ]);

        DB::beginTransaction();
        try {
            $sparepart = Sparepart::findOrFail($request->sparepart_id);
            $qty = $request->qty;

            if ($request->type === 'in') {
                $sparepart->stock += $qty;
            } else {
                if ($sparepart->stock < $qty) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Stok tidak mencukupi! Stok {$sparepart->name} saat ini hanya {$sparepart->stock} Pcs.");
                }
                $sparepart->stock -= $qty;
            }

            $sparepart->save();
            $totalAmount = $qty * $request->price_per_unit;
            StokTransaction::create([
                'sparepart_id'   => $request->sparepart_id,
                'type'           => $request->type,
                'qty'            => $qty,
                'price_per_unit' => $request->price_per_unit,
                'total_amount'   => $totalAmount,
                'supplier_id'    => $request->type === 'in' ? $request->supplier_id : null,
                'notes'          => $request->notes,
                'user_id'        => auth('web')->user()->id,
            ]);
            DB::commit();

            return redirect()->route('stocktransaction.index')
                ->with('success', 'Transaksi logistik stok berhasil dibukukan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StokTransaction $stokTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StokTransaction $stokTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StokTransaction $stokTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StokTransaction $stokTransaction)
    {
        //
    }
}
