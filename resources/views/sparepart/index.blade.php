@extends('layouts.app')
@section('title', 'Daftar Sparepart')
@section('page_title', 'Daftar Sparepart')
@section('content')
<div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Sparepart</h3>
                <a href="{{ route('sparepart.create') }}" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Sparepart
                </a>
            </div>

            {{-- Bagian Filter & Search --}}
            <div class="card-body border-bottom py-3">
                <form action="{{ route('sparepart.index') }}" method="GET" id="search-form">
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
                                <a href="{{ route('sparepart.index') }}"
                                    class="btn btn-sm btn-outline-danger ms-1">Reset</a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            {{-- PDF --}}
                            <a href="{{ route('sparepart.exportPdf') }}" class="btn btn-danger d-inline-flex align-items-center gap-2">
                                <i class="ti ti-file-type-pdf" style="font-size: 18px"></i>
                                <span>Export PDF</span>
                            </a>

                            {{-- Excel --}}
                            <a href="{{ route('sparepart.exportExcel') }}" class="btn btn-success btn-md d-inline-flex align-items-center gap-2">
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
                            <th>SKU</th>
                            <th>Nama Sparepart</th>
                            <th>Merek</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Lokasi</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sparepart as $item)
                             <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->brand }}</td>
                                <td>{{ $item->purchase_price }}</td>
                                <td>{{ $item->selling_price }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>{{ $item->location }}</td>
                                <td class="text-center">
                                    <a href="{{ route('sparepart.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="ti ti-edit" style="font-size: 18px"></i>
                                    </a>
                                    <form action="{{ route('sparepart.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="ti ti-trash" style="font-size: 18px"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                    
                        @empty
                      <tr>
                            <td colspan="10" class="text-center text-danger py-4">
                                <i class="ti ti-alert-circle d-block mb-2" style="font-size: 24px;"></i>
                                Data Sparepart belum tersedia.
                            </td>
                        </tr>
                        @endforelse
                           
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination Terintegrasi Laravel --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                <p class="m-0 text-secondary">
                    Showing <span>{{ $sparepart->firstItem() ?? 0 }}</span> to
                    <span>{{ $sparepart->lastItem() ?? 0 }}</span> of <span>{{ $sparepart->total() }}</span> entries
                </p>

                {{-- Memanggil link pagination bawaan Laravel yang otomatis kompatibel dengan Bootstrap/Tabler --}}
                <div class="m-0 ms-auto">
                    {{ $sparepart->links() }}
                </div>
            </div>
            {{-- End pagination --}}
        </div>
    </div>
@endsection
