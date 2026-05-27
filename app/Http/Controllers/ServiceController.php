<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['service'] = Service::paginate(10);
        return view('service.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'complaint_name' => 'required',
            'price' => 'required',
            'is_service' => 'required',
            'description' => 'required',
        ],[
            'code.required' => 'Kode Service wajib diisi',
            'complaint_name.required' => 'Nama Service wajib diisi',
            'price.required' => 'Harga wajib diisi',
            'is_service.required' => 'Status wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
        ]);
        $store = Service::create($request->all());
        if($store){
            return redirect()->route('service.index')->with('success','Data berhasil ditambahkan');
        }
        return redirect()->route('service.index')->with('error','Data gagal ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['service'] = Service::find($id);
        return view('service.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required',
            'complaint_name' => 'required',
            'price' => 'required',
            'is_service' => 'required',
            'description' => 'required',
        ],[
            'code.required' => 'Kode Service wajib diisi',
            'complaint_name.required' => 'Nama Service wajib diisi',
            'price.required' => 'Harga wajib diisi',
            'is_service.required' => 'Status wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
        ]);
        $update = Service::find($id)->update($request->all());
        if($update){
            return redirect()->route('service.index')->with('success','Data berhasil diupdate');
        }
        return redirect()->route('service.index')->with('error','Data gagal diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Service::find($id)->delete();
        if($delete){
            return redirect()->route('service.index')->with('success','Data berhasil dihapus');
        }
        return redirect()->route('service.index')->with('error','Data gagal dihapus');
    }

    // export pdf
    public function exportPdf()
    {
        $data['service'] = Service::all();
        $pdf = Pdf::loadView('service.pdf', $data);
        return $pdf->stream('service.pdf');
    }
}
