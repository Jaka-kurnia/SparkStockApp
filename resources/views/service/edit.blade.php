@extends('layouts.app')
@section('title', 'Edit Service')
@section('page_title', 'Edit Service')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <form action="{{ route('service.update', $service->id) }}" method="POST" class="card">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <h3 class="card-title">Edit Service</h3>
                    </div>

                    <div class="card-body">
                        <div class="row row-cards">

                            {{-- Kode Service (Readonly) --}}
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kode Service</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        name="code" value="{{ old('code', $service->code) }}" readonly>
                                    @error('code')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>

                            {{-- Nama Service --}}
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Nama Service</label>
                                    <input type="text" class="form-control @error('complaint_name') is-invalid @enderror"
                                        name="complaint_name" value="{{ old('complaint_name', $service->complaint_name) }}"
                                        required>
                                    @error('complaint_name')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>

                            {{-- Harga --}}
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            name="price" value="{{ old('price', $service->price) }}" required>
                                        @error('price')
                                            {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <select class="form-select @error('is_service') is-invalid @enderror" name="is_service">
                                        <option value="1"
                                            {{ old('is_service', $service->is_service) == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0"
                                            {{ old('is_service', $service->is_service) == '0' ? 'selected' : '' }}>Tidak
                                            Aktif</option>
                                    </select>
                                    @error('is_service')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea rows="4" class="form-control @error('description') is-invalid @enderror" name="description"
                                        placeholder="Tambahkan keterangan deskripsi jika ada...">{{ old('description', $service->description) }}</textarea>
                                    @error('description')
                                        {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Bagian Footer Tombol Aksi --}}
                    <div class="card-footer text-end">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success ">
                               Update
                            </button>
                            <a href="{{ route('service.index') }}" class="btn btn-danger">Cancel</a>
                            
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
