@extends('layouts.app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Supplier</h3>
                <a href="{{ route('supplier.create') }}" class="btn btn-primary">Tambah Supplier</a>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-secondary">
                        Show
                        <div class="mx-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" value="8" size="3"
                                aria-label="Invoices count">
                        </div>
                        entries
                    </div>
                    <div class="ms-auto text-secondary">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                                    aria-label="Select all invoices"></th>
                            <th class="w-1">No.
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M6 15l6 -6l6 6"></path>
                                </svg>
                            </th>
                            <th>Supplier Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    {{-- Table Body --}}
                    <tbody>
                        @forelse ($supplier as $item)
                            <tr>
                                {{-- checkbox --}}
                                <td>
                                    <input class="form-check-input m-0 align-middle"
                                        type="checkbox"aria-label="Select invoice">
                                </td>
                                {{-- no --}}
                                <td>
                                    <span class="text-secondary">{{ $loop->iteration }}</span>
                                </td>
                                {{-- supplier name --}}
                                <td>
                                    <a href="invoice.html" class="text-reset" tabindex="-1">{{ $item->name }}</a>
                                </td>
                                {{-- email --}}
                                <td>
                                    {{ $item->email }}
                                </td>
                                {{-- phone --}}
                                <td>
                                    {{ $item->phone }}
                                </td>
                                {{-- address --}}
                                <td>
                                    {{ $item->address }}
                                </td>
                                {{-- actions --}}
                                <td>
                                    <div class=" d-flex justify-content-around gap-2">
                                        <a href="{{ route('supplier.edit', $item->id) }}"
                                            class="btn btn-warning btn-md gap-0.5">Edit</a>
                                        <form action="{{ route('supplier.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-md gap-0.5"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                return false;>Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data Supplier Tidak Ada</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            {{-- pagination --}}
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                            prev
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 6l6 6l-6 6"></path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            {{-- End pagination --}}
        </div>
        {{-- End card --}}
    </div>
    {{-- End col --}}
@endsection
