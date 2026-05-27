@extends('layouts.app')
@section('title', 'Daftar Customer')
@section('page_title', 'Daftar Customer')


@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Customer</h3>
                <a href="{{ route('customer.create') }}" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Customer
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
                                <a href="#" class="btn btn-sm btn-link text-danger ms-1">Reset</a>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            {{-- PDF --}}
                            <a href="{{route('customer.export.Pdf')}}" class="btn btn-danger d-inline-flex align-items-center gap-2">
                                <i class="ti ti-file-type-pdf" style="font-size: 18px"></i>
                                <span>Export PDF</span>
                            </a>

                            {{-- Excel --}}
                            <a href="{{route('customer.export.Excel')}}" class="btn btn-success btn-md d-inline-flex align-items-center gap-2">
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
                            <th>Nama Customer</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($customer as $item)
                           <tr>
                            <td><input class="form-check-input m-0 align-middle" type="checkbox"></td>
                                {{-- Penomoran yang benar untuk pagination --}}
                                <td><span
                                        class="text-secondary">{{ ($customer->currentPage() - 1) * $customer->perPage() + $loop->iteration }}</span>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->address }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('customer.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                            <i class="ti ti-edit" style="font-size: 18px;"></i>
                                        </a>
                                        <form action="{{route('customer.destroy', $item->id)}}" method="POST">
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
                            <td colspan="7" class="text-center">Data Customer Tidak Ada</td>
                           </tr>
                       @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination Terintegrasi Laravel --}}
            <div class="card-footer d-flex align-items-center justify-content-between">
                <p class="m-0 text-secondary">
                    Showing <span>{{ $customer->firstItem() ?? 0 }}</span> to
                    <span>{{ $customer->lastItem() ?? 0 }}</span> of <span>{{ $customer->total() }}</span> entries
                </p>

                {{-- Memanggil link pagination bawaan Laravel yang otomatis kompatibel dengan Bootstrap/Tabler --}}
                <div class="m-0 ms-auto">
                    {{ $customer->links() }}
                </div>
            </div>
            {{-- End pagination --}}
        </div>
    </div>
@endsection
