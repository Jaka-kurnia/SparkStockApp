<?php

namespace App\Http\Controllers;

use App\Exports\SupplierExport;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data['supplier'] = $query->paginate(5);

        return view("supplier.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("supplier.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama Supplier wajib diisi',
            'email.required' => 'Email wajib diisi',
            'phone.required' => 'No. Telepon wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.unique' => 'No. Telepon sudah terdaftar',
            'address.unique' => 'Alamat sudah terdaftar',
            'name.max' => 'Nama Supplier maksimal 255 karakter',
            'email.max' => 'Email maksimal 255 karakter',
            'phone.max' => 'No. Telepon maksimal 255 karakter',
            'address.max' => 'Alamat maksimal 255 karakter',

        ]);

        $store = Supplier::create($request->all());
        if ($store) {
            return redirect()->route('supplier.index')->with('success', 'Data berhasil disimpan');
        } else {
            return back()->with('error', 'Data gagal disimpan');
        }
    }


    public function show(Supplier $supplier)
    {
        //
    }


    public function edit(Supplier $supplier)
    {
        $data['supplier'] = Supplier::find($supplier->id);
        return view('supplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama Supplier wajib diisi',
            'email.required' => 'Email wajib diisi',
            'phone.required' => 'No. Telepon wajib diisi',
            'address.required' => 'Alamat wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.unique' => 'No. Telepon sudah terdaftar',
            'address.unique' => 'Alamat sudah terdaftar',
            'name.max' => 'Nama Supplier maksimal 255 karakter',
            'email.max' => 'Email maksimal 255 karakter',
            'phone.max' => 'No. Telepon maksimal 255 karakter',
            'address.max' => 'Alamat maksimal 255 karakter',
        ]);

        $supplier = Supplier::find($id);
        $update = $supplier->update($request->all());

        if ($update) {
            return redirect()->route('supplier.index')->with('success', 'Data berhasil diupdate');
        } else {
            return back()->with('error', 'Data gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $delete = $supplier->delete();
        if ($delete) {
            return redirect()->route('supplier.index')->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
    }

    public function exportExcel()
    {
        return Excel::download(new SupplierExport, 'data-supplier.xlsx');
    }

    public function exportPdf()
    {
        $supplier = Supplier::all();
        $pdf = Pdf::loadView('supplier.pdf', compact('supplier'));
        return $pdf->stream('laporan-supplier.pdf');
    }
}
