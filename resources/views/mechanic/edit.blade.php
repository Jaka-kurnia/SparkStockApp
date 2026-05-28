@extends('layouts.app')

@section('title', 'Edit Data Mekanik')
@section('page_title', 'Edit Mekanik: ' . $mechanic->name_mechanic)

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Mekanik</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('mechanic.update', $mechanic->id) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role User</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                    <option value="">--- Pilih Role User ---</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" 
                                            {{ (old('user_id', $mechanic->user_id) == $user->id) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Nama Mekanik --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Mekanik</label>
                                <input type="text" class="form-control @error('name_mechanic') is-invalid @enderror"
                                    name="name_mechanic" value="{{ old('name_mechanic', $mechanic->name_mechanic) }}"
                                    placeholder="Input Nama Mekanik">
                                @error('name_mechanic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- No Telepon --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">No Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone', $mechanic->phone) }}" placeholder="Input No Telepon">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Select Status --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" name="is_active">
                                    <option value="">---Pilih Status---</option>
                                    <option value="1" {{ (old('is_active', $mechanic->is_active) == '1') ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ (old('is_active', $mechanic->is_active) == '0') ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-footer gap-3 d-flex">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('mechanic.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection