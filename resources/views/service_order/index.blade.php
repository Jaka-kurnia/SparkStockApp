@extends('layouts.app')
@section('title', 'Service Order')
@section('page_title', 'Service Orders')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Service Order</h3>
                <a href="#" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Service Order
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
                                <a href="#" class="btn btn-sm btn-outline-danger ms-1">Reset</a>
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
                                <input class="form-check-input m-0 align-middle" type="checkbox">
                            </th>
                            <th class="w-1">No.</th>
                            <th>No. Antrean</th>
                            <th>No. Order</th>
                            <th>Tanggal Servis</th>
                            <th>Pelanggan</th>
                            <th>Mekanik</th>
                            <th>Keluhan</th>
                            <th>Status Servis</th>
                            <th>Total Jasa</th>
                            <th>Total Sparepart</th>
                            <th>Diskon</th>
                            <th>Pajak</th>
                            <th>Grand Total</th>
                            <th>Metode Bayar</th>
                            <th>Status Bayar</th>
                            <th>Metode Pembayaran</th>
                            <th>Status Pembayaran</th>
                            <th>Status Midtrans</th>
                            <th>Di Bayar Pada</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>

                </table>
            </div>

            {{-- Bagian Pagination Terintegrasi Laravel --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                {{-- <p class="m-0 text-secondary">
                    Showing <span>{{ $stockTransactions->firstItem() ?? 0 }}</span> to
                    <span>{{ $stockTransactions->lastItem() ?? 0 }}</span> of <span>{{ $stockTransactions->total() ?? 0 }}</span> entries
                </p>

                <div class="m-0 ms-auto">
                    {{ $stockTransactions->links() }}
                </div> --}}
            </div>
            {{-- End pagination --}}
        </div>
    </div>

@endsection
