@extends('layouts.app')
@section('title', 'Service Order')
@section('page_title', 'Service Orders')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Service Order</h3>
                <a href="{{route('serviceorder.create')}}" class="btn btn-primary">
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
                            <th>Status Midtrans</th>
                            <th>Dibayar Pada</th>
                            <th>Catatan</th>
                            <th class="w-1 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceOrders as $item)
                            <tr>
                                <td><input class="form-check-input m-0 align-middle" type="checkbox"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-secondary text-white">{{ $item->kode_queue }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary text-white">{{ $item->kode_order }}</span>
                                </td>
                                <td>{{ $item->service_date ? $item->service_date->format('d-m-Y') : '-' }}</td>
                                <td>{{ $item->customer->name ?? '-' }}</td>
                                <td>{{ $item->mechanic->name_mechanic ?? '-' }}</td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                        title="{{ $item->keluhan }}">
                                        {{ $item->keluhan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->status == 'pending')
                                        <span class="badge bg-warning-lt">Pending</span>
                                    @elseif($item->status == 'in_progress')
                                        <span class="badge bg-info-lt">In Progress</span>
                                    @elseif($item->status == 'completed')
                                        <span class="badge bg-success-lt">Completed</span>
                                    @elseif($item->status == 'paid')
                                        <span class="badge bg-purple-lt">Paid</span>
                                    @elseif($item->status == 'closed')
                                        <span class="badge bg-secondary-lt">Closed</span>
                                    @else
                                        <span class="badge bg-danger-lt">Cancelled</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->total_service, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->total_part, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->discount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->tax, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if ($item->payment_method == 'cash')
                                        <span class="badge bg-success-lt">Cash</span>
                                    @elseif($item->payment_method == 'midtrans')
                                        <span class="badge bg-info-lt">Midtrans</span>
                                    @elseif($item->payment_method == 'transfer')
                                        <span class="badge bg-blue-lt">Transfer</span>
                                    @elseif($item->payment_method == 'qris')
                                        <span class="badge bg-purple-lt">QRIS</span>
                                    @elseif($item->payment_method == 'hutang')
                                        <span class="badge bg-danger-lt">Hutang</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_status == 'unpaid')
                                        <span class="badge bg-danger-lt">Unpaid</span>
                                    @elseif($item->payment_status == 'paid')
                                        <span class="badge bg-success-lt">Paid</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($item->midtrans_status == 'pending')
                                        <span class="badge bg-warning-lt">Pending</span>
                                    @elseif($item->midtrans_status == 'paid')
                                        <span class="badge bg-success-lt">Paid</span>
                                    @elseif($item->midtrans_status == 'failed')
                                        <span class="badge bg-danger-lt">Failed</span>
                                    @elseif($item->midtrans_status == 'expired')
                                        <span class="badge bg-secondary-lt">Expired</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->paid_at ? $item->paid_at->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    <span class="text-secondary text-truncate d-inline-block" style="max-width: 120px;"
                                        title="{{ $item->note }}">
                                        {{ $item->note ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-list flex-nowrap justify-content-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="ti ti-eye" style="font-size: 16px;"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="ti ti-edit" style="font-size: 16px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="21" class="text-center text-danger py-4">
                                    <i class="ti ti-alert-circle d-block mb-2" style="font-size: 24px;"></i>
                                    Data Service Order belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                <p class="m-0 text-secondary">
                    Total: <span>{{ $serviceOrders->count() }}</span> entries
                </p>
            </div>
            {{-- End pagination --}}
            </div>
        </div>
    @endsection
