@extends('layouts.app')
@section('title', 'Tambah Customer')
@section('page_title', 'Tambah Customer')
@section('content')
    <div class="col-12">
        <form action="{{ route('customer.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama Customer</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Input Nama Customer">
                                @error('name')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="email">Email Customer</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="Input Email Customer">
                                @error('email')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="phone">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                    placeholder="Input Nomor Telepon">
                                @error('phone')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="address">Alamat</label>
                                <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Input Alamat Lengkap">{{ old('address') }}</textarea>
                                @error('address')
                                    {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer form-footer gap-3 d-flex">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('customer.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
