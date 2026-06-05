@extends('layouts.app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Supplier</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('supplier.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Supplier</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" placeholder="Input Nama Supplier">
                                @error('name')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" placeholder="Input Email">
                                @error('email')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" placeholder="Input No. Telepon">
                                @error('phone')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="4"
                                    placeholder="Input Alamat">{{ old('address') }}</textarea>
                                @error('address')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-footer gap-3 d-flex">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('supplier.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
