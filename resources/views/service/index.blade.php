@extends('layouts.app')
@section('title', 'Daftar Service')
@section('page_title', 'Daftar Service')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Service</h3>
                <a href="{{route('service.create')}}" class="btn btn-primary">
                    <i class="ti ti-file-plus" style="font-size: 18px; padding-right:10px;"></i>
                    Tambah Service
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
                            <a href="{{route('service.exportPdf')}}" class="btn btn-danger d-inline-flex align-items-center gap-2">
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
                            <th>Kode Service</th>
                            <th>Nama Jasa</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($service as $item)
                            <tr>
                                <td>
                                    <input class="form-check-input m-0 align-middle"
                                        type="checkbox"aria-label="Select all invoices">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->complaint_name }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    @if ($item->is_service)
                                        <span class="status-dot bg-success animate-pulse me-2"></span>
                                        <span class="text-success fw-medium">Aktif</span>
                                    @else
                                        <span class="status-dot bg-danger me-2"></span>
                                        <span class="text-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $item->description }}</td>
                                <td class="text-center">
                                    <a href="{{ route('service.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="ti ti-file-pencil" style="font-size: 18px;"></i>
                                        
                                    </a>
                                    <form action="{{ route('service.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="ti ti-trash" style="font-size: 18px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data Service Tidak Tersedia</td>
                            </tr>
                        @endforelse
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
