@extends('layouts.app')
@section('title', 'Tambah Transaksi Stok')
@section('page_title', 'Tambah Transaksi Stok')
@section('content')
    <div class="col-12">
        <form action="{{ route('stocktransaction.store') }}" method="POST" class="card" id="transaction_form">
            @csrf

            <div class="card-header">
                <h3 class="card-title">Form Input Mutasi & Transaksi Stok</h3>
            </div>

            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-alert-circle" style="font-size: 20px; margin-right: 10px;"></i>
                            </div>
                            <div>
                                {{ session('error') }}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-check" style="font-size: 20px; margin-right: 10px;"></i>
                            </div>
                            <div>
                                {{ session('success') }}
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-cards">

                    {{-- 1. Pilihan Tipe Transaksi --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label required">Tipe Transaksi</label>
                            <select name="type" id="transaction_type"
                                class="form-select @error('type') is-invalid @enderror" required>
                                <option value="" selected disabled>-- Pilih Tipe Mutasi --</option>
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Barang Masuk (Restock / Pembelian)</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Barang Keluar (Penjualan Langsung)</option>
                                <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment (Stock Opname)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 2. Pilihan Supplier --}}
                    <div class="col-md-6" id="supplier_field_wrapper" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Pemasok (Supplier)</label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="" selected>-- Pilih Supplier (Opsional) --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }} ({{ $supplier->phone ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. Catatan / Deskripsi Transaksi --}}
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Tulis alasan jika melakukan adjustment stok atau informasi tambahan terkait supplier...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-3">Form Tambah Barang</h4>
                <div class="row row-cards align-items-end mb-4">
                    <div class="col-md-4">
                        <div class="mb-3 mb-md-0">
                            <label class="form-label">Pilih Suku Cadang (Sparepart)</label>
                            <select id="part_select" class="form-select">
                                <option value="" selected disabled>-- Pilih Suku Cadang --</option>
                                @foreach ($spareparts as $part)
                                    <option value="{{ $part->id }}" data-name="{{ $part->name }}" data-stock="{{ $part->stock }}">
                                        {{ $part->sku }} - {{ $part->name }} (Stok: {{ $part->stock }} Pcs)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3 mb-md-0">
                            <label class="form-label">Jumlah (Qty)</label>
                            <div class="input-group">
                                <input type="number" min="1" id="part_qty" class="form-control" placeholder="0">
                                <span class="input-group-text">Pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3 mb-md-0">
                            <label class="form-label">Harga Satuan</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" id="part_price" class="form-control" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button type="button" id="btn_add_cart" class="btn btn-success w-100">
                            <i class="ti ti-plus" style="margin-right: 5px;"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped" id="cart_table">
                        <thead>
                            <tr>
                                <th>Nama Sparepart</th>
                                <th class="text-center">Jumlah (Qty)</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total Biaya</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody id="cart_body">
                            <tr id="empty_cart_row">
                                <td colspan="5" class="text-center text-muted py-4">Keranjang masih kosong. Tambahkan barang di atas.</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                <td class="text-end fw-bold" id="grand_total_text">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    @error('items')
                        <div class="text-danger mt-2" style="font-size: 87.5%;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Bagian Tombol Aksi --}}
            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('stocktransaction.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn_submit">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

@include('stock_transaction.script')
@endsection