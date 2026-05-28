@extends('layouts.app')
@section('title', 'Tambah Data Kendaraan')
@section('page_title', 'Tambah Data Kendaraan')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('vehicle.store') }}" method="POST" class="card">
                @csrf
                
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Kendaraan</h3>
                </div>
                
                <div class="card-body">
                    <div class="row row-cards">
                        
                        {{-- Plat Nomor --}}
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label ">Plat Nomor</label>
                                <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                    name="plate_number" value="{{ old('plate_number') }}"  placeholder="Contoh: B 1234 ABC">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tipe Kendaraan (Dropdown) --}}
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label ">Tipe</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" >
                                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>-- Pilih Tipe --</option>
                                    <option value="Matic" {{ old('type') == 'Matic' ? 'selected' : '' }}>Matic</option>
                                    <option value="Bebek" {{ old('type') == 'Bebek' ? 'selected' : '' }}>Bebek</option>
                                    <option value="Sport" {{ old('type') == 'Sport' ? 'selected' : '' }}>Sport</option>
                                    <option value="Adventure" {{ old('type') == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                                    <option value="Klasik" {{ old('type') == 'Klasik' ? 'selected' : '' }}>Klasik</option>
                                    <option value="Listrik" {{ old('type') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                       {{-- brand --}}
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label ">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                    name="brand" value="{{ old('brand') }}"  placeholder="Contoh:Honda, Yamaha, Suzuki, Kawasaki">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Merk --}}
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label ">Merk</label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                                    name="merk" value="{{ old('merk') }}"  placeholder="Contoh: Beat, Vario, Supra">
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Warna --}}
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label ">Warna</label>
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                    name="color" value="{{ old('color') }}"  placeholder="Contoh: Hitam, Putih">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tahun --}}
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label ">Tahun</label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                    name="year" value="{{ old('year') }}"  placeholder="Contoh: 2024" min="1900" max="2099">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Bagian Footer Tombol Aksi --}}
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        
                         <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="{{ route('vehicle.index') }}" class="btn btn-danger">Batal</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection