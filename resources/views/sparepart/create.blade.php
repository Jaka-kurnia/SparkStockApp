@extends('layouts.app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Sparepart</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('sparepart.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku"
                                    value="{{ old('sku') }}" placeholder="Input SKU Sparepart">
                                @error('sku')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Sparepart</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" placeholder="Input Nama Sparepart">
                                @error('name')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Merek</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                    name="brand" value="{{ old('brand') }}" placeholder="Input Merek/Brand">
                                @error('brand')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input  class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" value="{{ old('stock') }}" placeholder="Input Jumlah Stok">
                                @error('stock')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input  class="form-control @error('purchase_price') is-invalid @enderror"
                                        name="purchase_price" value="{{ old('purchase_price') }}" placeholder="0">
                                    @error('purchase_price')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input  class="form-control @error('selling_price') is-invalid @enderror"
                                        name="selling_price" value="{{ old('selling_price') }}" placeholder="0">
                                    @error('selling_price')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Lokasi Rak / Gudang</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    name="location" value="{{ old('location') }}" placeholder="Contoh: Rak A-01">
                                @error('location')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-footer gap-3 d-flex mt-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('sparepart.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
