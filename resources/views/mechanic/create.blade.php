@extends('layouts.app')
@section('title', 'Data Mekanik')
@section('page_title', 'Data Mekanik Baru')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Mekanik</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('mechanic.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        {{-- Select user_id --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role User</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                    <option value="">Pilih Role User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- End select --}}
                        {{-- Nama Mekanik --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Mekanik</label>
                                <input type="text" class="form-control @error('name_mechanic') is-invalid @enderror"
                                    name="name_mechanic" value="{{ old('name_mechanic') }}"
                                    placeholder="Input Nama Mekanik">
                                @error('name_mechanic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- End Nama Mekanik --}}
                        {{-- No Telepon --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" placeholder="Input No Telepon">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- End no telepon --}}

                        {{-- Select setatus --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Setatus</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                    <option value="">Pilih Setatus</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- End select --}}

                        <div class="form-footer gap-3 d-flex">

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('mechanic.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
