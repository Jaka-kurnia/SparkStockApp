<?php

namespace App\Http\Controllers;

use App\Exports\VehicleExport;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('plat_number')) {
            $query->where('plat_number', 'like', '%' . $request->plat_number . '%');
        }

        $data['vehicle'] = $query->paginate(5);

        return view('vehicle.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'plate_number' => 'required',
            'type' => 'required',
            'brand' => 'required',
            'color' => 'required',
            'year' => 'required',
        ],[
            'plate_number.required' => 'Plat nomor harus diisi',
            'type.required' => 'Tipe harus diisi',
            'brand.required' => 'Merk harus diisi',
            'color.required' => 'Warna harus diisi',
            'year.required' => 'Tahun harus diisi',
        ]);

        $store = Vehicle::create($request->all());
        
        if($store){
            return redirect()->route('vehicle.index')->with('success', 'Data berhasil ditambahkan');
        }else{
            return redirect()->back()->with('error', 'Data gagal ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['vehicle'] = Vehicle::find($id);
        return view('vehicle.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'plate_number' => 'required',
            'type' => 'required',
            'brand' => 'required',
            'color' => 'required',
            'year' => 'required',
        ],[
            'plate_number.required' => 'Plat nomor harus diisi',
            'type.required' => 'Tipe harus diisi',
            'brand.required' => 'Merk harus diisi',
            'color.required' => 'Warna harus diisi',
            'year.required' => 'Tahun harus diisi',
        ]);

        $update = Vehicle::find($id)->update($request->all());
        
        if($update){
            return redirect()->route('vehicle.index')->with('success', 'Data berhasil diupdate');
        }else{
            return redirect()->back()->with('error', 'Data gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Vehicle::find($id)->delete();
        
        if($delete){
            return redirect()->route('vehicle.index')->with('success', 'Data berhasil dihapus');
        }else{
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }

    // export pdf
    public function exportPdf()
    {
        $data['vehicle'] = Vehicle::all();
        $pdf = Pdf::loadView('vehicle.pdf', $data);
        return $pdf->stream('vehicle.pdf');
    }

    // export excel
    public function exportExcel()
    {
        return Excel::download(new VehicleExport, 'vehicle.xlsx');
    }
}
