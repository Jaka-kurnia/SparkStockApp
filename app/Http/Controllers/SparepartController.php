<?php

namespace App\Http\Controllers;


use App\Exports\SparepartExport;
use App\Models\Sparepart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class SparepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sparepart::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data['sparepart'] = $query->paginate(5);
        return view('sparepart.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sparepart.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:spareparts,sku',
            'name' => 'required',
            'brand' => 'required',
            'stock' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'location' => 'required',
        ], [
            'sku.required' => 'SKU wajib diisi',
            'name.required' => 'Nama sparepart wajib diisi',
            'brand.required' => 'Merek wajib diisi',
            'stock.required' => 'Stok wajib diisi',
            'purchase_price.required' => 'Harga beli wajib diisi',
            'selling_price.required' => 'Harga jual wajib diisi',
            'location.required' => 'Lokasi wajib diisi',
            'sku.unique' => 'SKU sudah terdaftar',
            'name.unique' => 'Nama sudah terdaftar',
            'brand.unique' => 'Merek sudah terdaftar',
            'stock.unique' => 'Stok sudah terdaftar',
            'purchase_price.unique' => 'Harga beli sudah terdaftar',
            'selling_price.unique' => 'Harga jual sudah terdaftar',
            'location.unique' => 'Lokasi sudah terdaftar',

        ]);

        $store = Sparepart::create($request->all());
        if ($store) {
            return redirect()->route('sparepart.index')->with('success', 'Data berhasil disimpan');
        } else {
            return back()->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sparepart $sparepart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sparepart $sparepart)
    {
        $data['sparepart'] = Sparepart::find($sparepart->id);
        return view('sparepart.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'sku' => 'required|unique:spareparts,sku,' . $id,
            'name' => 'required',
            'brand' => 'required',
            'stock' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
            'location' => 'required',
        ], [
            'sku.required' => 'SKU wajib diisi',
            'name.required' => 'Nama sparepart wajib diisi',
            'brand.required' => 'Merek wajib diisi',
            'stock.required' => 'Stok wajib diisi',
            'purchase_price.required' => 'Harga beli wajib diisi',
            'selling_price.required' => 'Harga jual wajib diisi',
            'location.required' => 'Lokasi wajib diisi',
            'sku.unique' => 'SKU sudah terdaftar',
            'name.unique' => 'Nama sudah terdaftar',
            'brand.unique' => 'Merek sudah terdaftar',
            'stock.unique' => 'Stok sudah terdaftar',
            'purchase_price.unique' => 'Harga beli sudah terdaftar',
            'selling_price.unique' => 'Harga jual sudah terdaftar',
            'location.unique' => 'Lokasi sudah terdaftar',
        ]);

        $sparepart = Sparepart::find($id);
        $update = $sparepart->update($request->all());
        if ($update) {
            return redirect()->route('sparepart.index')->with('success', 'Data berhasil diupdate');
        } else {
            return back()->with('error', 'Data gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sparepart = Sparepart::find($id);
        $delete = $sparepart->delete();
        if ($delete) {
            return redirect()->route('sparepart.index')->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
    }

    // export pdf
    public function exportPdf(Request $request)
    {
        $query = Sparepart::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data['sparepart'] = $query->get();

        $pdf = Pdf::loadView('sparepart.pdf', $data);
        return $pdf->stream('sparepart-list.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SparepartExport, 'data-sparepart.xlsx');
    }
}
