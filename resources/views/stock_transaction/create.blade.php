@extends('layouts.app')
@section('title', 'Tambah Transaksi Stok')
@section('page_title', 'Tambah Transaksi Stok')
@section('content')
    <div class="col-12">
        <form action="{{ route('stocktransaction.store') }}" method="POST" class="card">
            @csrf

            <div class="card-header">
                <h3 class="card-title">Form Input Mutasi & Transaksi Stok</h3>
            </div>

            <div class="card-body">
                <div class="row row-cards">

                    {{-- 1. Pilihan Tipe Transaksi --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipe Transaksi</label>
                            <select name="type" id="transaction_type"
                                class="form-select @error('type') is-invalid @enderror">
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

                    {{-- 2. Pilihan Suku Cadang (Sparepart) --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pilih Suku Cadang (Sparepart)</label>
                            <select name="sparepart_id" class="form-select @error('sparepart_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Suku Cadang --</option>
                                @foreach ($spareparts as $part)
                                    <option value="{{ $part->id }}" {{ old('sparepart_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->sku }} - {{ $part->name }} (Stok Saat Ini: {{ $part->stock }} Pcs)
                                    </option>
                                @endforeach
                            </select>
                            @error('sparepart_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. Pilihan Supplier --}}
                    <div class="col-md-12" id="supplier_field_wrapper" style="display: none;">
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

                    {{-- 4. Jumlah Kuantitas Barang (Qty) --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Jumlah (Quantity)</label>
                            <div class="input-group">
                                <input type="number" min="1" name="qty" id="qty"
                                    class="form-control @error('qty') is-invalid @enderror" value="{{ old('qty') }}"
                                    placeholder="Contoh: 10">
                                <span class="input-group-text">Pcs</span>
                            </div>
                            @error('qty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 5. Harga Satuan (Price Per Unit) --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Harga Satuan</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="price_per_unit" id="price_per_unit"
                                    class="form-control @error('price_per_unit') is-invalid @enderror"
                                    value="{{ old('price_per_unit') }}" placeholder="0">
                            </div>
                            @error('price_per_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- 6. Total Kalkulasi Otomatis (Read Only) --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Total Biaya</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="total_amount_placeholder" class="form-control bg-light" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- 7. Catatan / Deskripsi --}}
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Tulis alasan jika melakukan adjustment stok atau informasi tambahan terkait supplier...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Bagian Tombol Aksi --}}
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan Transaksi
                </button>
                <a href="#" class="btn btn-danger">Batal</a>
            </div>
        </form>
    </div>

    {{-- JavaScript Interaktif - Struktur Kurung Sudah Diperbaiki --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeSelect = document.getElementById('transaction_type');
            const supplierWrapper = document.getElementById('supplier_field_wrapper');
            const qtyInput = document.getElementById('qty');
            const priceInput = document.getElementById('price_per_unit');
            const totalPlaceholder = document.getElementById('total_amount_placeholder');

            // A. Logika Muncul/Sembunyi Lapangan Supplier
            function toggleSupplierField() {
                if (typeSelect.value === 'in') {
                    supplierWrapper.style.display = 'block';
                } else {
                    supplierWrapper.style.display = 'none';
                    supplierWrapper.querySelector('select').value = '';
                }
            }

            // B. Logika Hitung Total Otomatis di Sisi Klien
            function calculateTotal() {
                const qty = parseFloat(qtyInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = qty * price;

                // Format angka ke format ribuan rupiah biasa
                totalPlaceholder.value = total.toLocaleString('id-ID');
            }

            // Daftarkan Event Listener secara mandiri (terpisah, tidak saling tumpang tindih)
            typeSelect.addEventListener('change', toggleSupplierField);
            qtyInput.addEventListener('input', calculateTotal);
            priceInput.addEventListener('input', calculateTotal);

            // Jalankan fungsi di awal untuk mengantisipasi old value setelah validasi error
            toggleSupplierField();
            calculateTotal();
        }); // Tutup event listener DOMContentLoaded yang benar
    </script>

    
@endsection