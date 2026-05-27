<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $data['customer'] = $query->paginate(10);
        return view('customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
        ], [
            'name.required' => 'Nama customer tidak boleh kosong',
            'email.required' => 'Email customer tidak boleh kosong',
            'phone.required' => 'Nomor telepon customer tidak boleh kosong',
            'address.required' => 'Alamat customer tidak boleh kosong',
            'email.email' => 'Email customer tidak valid',
        ]);

        $store = Customer::create($request->all());
        if ($store) {
            return redirect()->route('customer.index')->with('success', 'Data berhasil ditambahkan');
        } else {
            return back()->with('error', 'Data gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $data['customer'] = Customer::find($customer->id);
        return view('customer.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
        ], [
            'name.required' => 'Nama customer tidak boleh kosong',
            'email.required' => 'Email customer tidak boleh kosong',
            'phone.required' => 'Nomor telepon customer tidak boleh kosong',
            'address.required' => 'Alamat customer tidak boleh kosong',
            'email.email' => 'Email customer tidak valid',
        ]);

        $customer = Customer::find($id);
        $update = $customer->update($request->all());
        if ($update) {
            return redirect()->route('customer.index')->with('success', 'Data berhasil diupdate');
        } else {
            return back()->with('error', 'Data gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $delete = $customer->delete();
        if ($delete) {
            return redirect()->route('customer.index')->with('success', 'Data berhasil dihapus');
        } else {
            return back()->with('error', 'Data gagal dihapus');
        }
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $query = Customer::query();
        $data['customer'] = $query->get();

        $pdf = Pdf::loadView('customer.pdf', $data);
        return $pdf->stream('customer-list.pdf');
    }

    // Export Excel
    public function exportExcel()
    {
        return Excel::download(new CustomerExport, 'data-customer.xlsx');
    }
}
