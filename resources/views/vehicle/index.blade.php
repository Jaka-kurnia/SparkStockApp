@extends('layouts.app')
@section('title', 'Daftar Kendaraan')
@section('page_title', 'Daftar Kendaraan')
@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Kendaraan</h3>
                <a href="{{ route('vehicle.create') }}" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Kendaraan
                </a>
            </div>

            {{-- Bagian Filter & Search --}}
            <div class="card-body border-bottom py-3">
                <form action="#" method="GET" id="search-form">
                    <div class="d-flex align-items-center justify-content-between">

                        <div class="text-secondary d-flex align-items-center gap-1">
                            Search:
                            <div class="ms-2 d-inline-block shadow-sm">
                                <div class="input-group gap-2">
                                    <input type="text" class="form-control form-control-sm" name="name"
                                        value="{{ request('name') }}" placeholder="Cari nama..."
                                        onchange="this.form.submit()">
                                    <button class="btn btn-sm btn-primary" type="submit">
                                        <i class="ti ti-search" style="font-size: 18px; padding-right:10px;"></i>
                                        Cari
                                    </button>
                                </div>
                            </div>
                            @if (request('name'))
                                <a href="{{ route('supplier.index') }}"
                                    class="btn btn-sm btn-link text-danger ms-1">Reset</a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            {{-- PDF --}}
                            <a href="{{route('vehicle.exportPdf')}}" class="btn btn-danger d-inline-flex align-items-center gap-2">
                                <i class="ti ti-file-type-pdf" style="font-size: 18px"></i>
                                <span>Export PDF</span>
                            </a>

                            {{-- Excel --}}
                            <a href="{{route('vehicle.exportExcel')}}" class="btn btn-success btn-md d-inline-flex align-items-center gap-2">
                                <i class="ti ti-file-excel" style="font-size: 18px"></i>
                                <span>Export Excel</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">
                                <input class="form-check-input m-0 align-middle"
                                    type="checkbox"aria-label="Select all invoices">
                            </th>
                            <th class="w-1">No.</th>
                            <th>Plat Number</th>
                            <th>Tipe</th>
                            <th>Tahun</th>
                            <th>Merk</th>
                            <th>Warna</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($vehicle as $item)
                           <tr>
                                <td><input class="form-check-input m-0 align-middle" type="checkbox"></td>
                                {{-- Penomoran yang benar untuk pagination --}}
                                <td><span
                                        class="text-secondary">{{ ($vehicle->currentPage() - 1) * $vehicle->perPage() + $loop->iteration }}</span>
                                </td>
                                <td><a href="#" class="text-reset">{{ $item->plate_number }}</a></td>
                                <td>{{ $item->type }}</td>
                                <td>{{ $item->year }}</td>
                                <td>{{ $item->brand }}</td>
                                <td>{{ $item->color }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('vehicle.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                            <i class="ti ti-edit" style="font-size: 18px;"></i>
                                        </a>
                                        <form action="{{ route('vehicle.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="ti ti-trash" style="font-size: 18px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                       @empty
                           <tr>
                                <td colspan="7" class="text-center">Tidak ada data Kendaraan</td>
                           </tr>
                       @endforelse
                </table>
            </div>

            {{-- Bagian Pagination Terintegrasi Laravel --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                <p class="m-0 text-secondary">
                    Showing <span>{{ $vehicle->firstItem() ?? 0 }}</span> to
                    <span>{{ $vehicle->lastItem() ?? 0 }}</span> of <span>{{ $vehicle->total() }}</span> entries
                </p>

                {{-- Memanggil link pagination bawaan Laravel yang otomatis kompatibel dengan Bootstrap/Tabler --}}
                <div class="m-0 ms-auto">
                    {{ $vehicle->links() }}
                </div>
            </div>
            {{-- End pagination --}}
        </div>
    </div>
@endsection
