<?php

namespace App\Http\Controllers;

use App\Exports\MechanicExport;
use App\Models\Mechanic;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MechanicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['user'] = User::all();
        $data['mechanic'] = Mechanic::paginate(10);
        return view('mechanic.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $query = Mechanic::query();
        $mechanics = $query->paginate(5);


        return view('mechanic.create', compact('users', 'mechanics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name_mechanic' => 'required',
            'phone' => 'required',
            'is_active' => 'required',
        ], [
            'user_id.required' => 'User wajib diisi',
            'name_mechanic.required' => 'Nama Mekanik wajib diisi',
            'phone.required' => 'No Telepon wajib diisi',
            'is_active.required' => 'Setatus wajib diisi',
        ]);
        $store = Mechanic::create($request->all());
        if ($store) {
            return redirect()->route('mechanic.index')->with('success', 'Data berhasil ditambahkan');
        }
        return redirect()->route('mechanic.index')->with('error', 'Data gagal ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mechanic $mechanic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['mechanic'] = Mechanic::find($id);
        $data['users'] = User::all();
        return view('mechanic.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'name_mechanic' => 'required',
            'phone' => 'required',
            'is_active' => 'required',
        ], [
            'user_id.required' => 'User wajib diisi',
            'name_mechanic.required' => 'Nama Mekanik wajib diisi',
            'phone.required' => 'No Telepon wajib diisi',
            'is_active.required' => 'Setatus wajib diisi',
        ]);
        $update = Mechanic::find($id)->update($request->all());
        if ($update) {
            return redirect()->route('mechanic.index')->with('success', 'Data berhasil diupdate');
        }
        return redirect()->route('mechanic.index')->with('error', 'Data gagal diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $delete = Mechanic::find($id)->delete();
        if ($delete) {
            return redirect()->route('mechanic.index')->with('success', 'Data berhasil dihapus');
        }
        return redirect()->route('mechanic.index')->with('error', 'Data gagal dihapus');
    }
    // export pdf
    public function exportPdf()
    {
        $data['mechanic'] = Mechanic::all();
        $pdf = Pdf::loadView('mechanic.pdf', $data);
        return $pdf->stream('mechanic.pdf');
    }

    // export excel
    public function exportExcel()
    {
        return Excel::download(new MechanicExport(), 'mechanic.xlsx');
    }
}
