@extends('layouts.app')
@section('title', 'Edit Kendaraan')
@section('page_title', 'Edit Kendaraan')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <form action="{{ route('vehicle.update', $vehicle->id) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                
                <div class="card-header">
                    <h3 class="card-title">Edit Kendaraan</h3>
                </div>
                
                <div class="card-body">
                    <div class="row row-cards">
                        
                        {{-- Plat Nomor (Readonly / Bisa Diubah tinggal hapus readonly) --}}
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                    name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" readonly>
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
                                    <option value="Mobil" {{ old('type', $vehicle->type) == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                    <option value="Motor" {{ old('type', $vehicle->type) == 'Motor' ? 'selected' : '' }}>Motor</option>
                                    <option value="Truk" {{ old('type', $vehicle->type) == 'Truk' ? 'selected' : '' }}>Truk</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Brand --}}
                        <div class="col-sm-4 col-md-4">
                            <div class="mb-3">
                                <label class="form-label ">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                    name="brand" value="{{ old('brand', $vehicle->brand) }}" >
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
                                    name="merk" value="{{ old('merk', $vehicle->merk) }}" >
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
                                    name="color" value="{{ old('color', $vehicle->color) }}" >
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
                                    name="year" value="{{ old('year', $vehicle->year) }}"  min="1900" max="2099">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Bagian Footer Tombol Aksi --}}
                <div class="card-footer text-end">
                    <div class="d-flex gap-2">
                         <button type="submit" class="btn btn-success">
                            Update
                        </button>
                        <a href="{{ route('vehicle.index') }}" class="btn btn-danger">Batal</a>
                       
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection