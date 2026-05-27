@extends('layouts.app')
@section('title', 'Daftar Sparepart')
@section('page_title', 'Daftar Sparepart')
@section('content')
<div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Sparepart</h3>
                <a href="#" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Sparepart
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
                                <a href="#"
                                    class="btn btn-sm btn-link text-danger ms-1">Reset</a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            {{-- PDF --}}
                            <a href="#" class="btn btn-danger d-inline-flex align-items-center gap-2">
                                <i class="ti ti-file-type-pdf" style="font-size: 18px"></i>
                                <span>Export PDF</span>
                            </a>

                            {{-- Excel --}}
                            <a href="#" class="btn btn-success btn-md d-inline-flex align-items-center gap-2">
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
                        @foreach ($sparepart as $item)
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
                                    <a href="#" class="btn btn-sm btn-warning">
                                        <i class="ti ti-edit" style="font-size: 18px"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger">
                                        <i class="ti ti-trash" style="font-size: 18px"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination Terintegrasi Laravel --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                {{-- <p class="m-0 text-secondary">
                    Showing <span>{{ $supplier->firstItem() ?? 0 }}</span> to
                    <span>{{ $supplier->lastItem() ?? 0 }}</span> of <span>{{ $supplier->total() }}</span> entries
                </p> --}}

                {{-- Memanggil link pagination bawaan Laravel yang otomatis kompatibel dengan Bootstrap/Tabler --}}
                {{-- <div class="m-0 ms-auto">
                    {{ $supplier->links() }}
                </div> --}}
            </div>
            {{-- End pagination --}}
        </div>
    </div>
@endsection
