@extends('layouts.app')
@section('title', 'Tambah Service Order')
@section('page_title', 'Tambah Service Order')
@section('content')
    <div class="col-12">
        <form action="{{ route('serviceorder.store') }}" method="POST" class="card" id="service_order_form">
            @csrf

            <div class="card-header">
                <h3 class="card-title">Form Input Service Order</h3>
            </div>

            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div><i class="ti ti-alert-circle" style="font-size: 20px; margin-right: 10px;"></i></div>
                            <div>{{ session('error') }}</div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <div class="row row-cards">
                    {{-- Pelanggan --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label ">Pelanggan</label>
                            <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Pelanggan --</option>
                                @foreach ($customers as $cust)
                                    <option value="{{ $cust->id }}"
                                        {{ old('customer_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    {{-- Kendaraan --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label ">Kendaraan</label>
                            <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Kendaraan --</option>
                                @foreach ($vehicles as $veh)
                                    <option value="{{ $veh->id }}"
                                        {{ old('vehicle_id') == $veh->id ? 'selected' : '' }}>
                                        {{ $veh->plate_number }} - {{ $veh->merk }} {{ $veh->color }}
                                        ({{ $veh->year }})
                                    </option>
                                @endforeach
                            </select>
                            @error('vehicle_id')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    {{-- Mekanik --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label ">Mekanik</label>
                            <select name="mechanic_id" class="form-select @error('mechanic_id') is-invalid @enderror">
                                <option value="" selected disabled>-- Pilih Mekanik --</option>
                                @foreach ($mechanics as $mech)
                                    <option value="{{ $mech->id }}"
                                        {{ old('mechanic_id') == $mech->id ? 'selected' : '' }}>
                                        {{ $mech->name_mechanic }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mechanic_id')
                                {{-- <div class="invalid-feedback">{{ $message }}</div>  --}}
                            @enderror
                        </div>
                    </div>

                    {{-- Tanggal Servis --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label ">Tanggal Servis</label>
                            <input type="date" name="service_date"
                                class="form-control @error('service_date') is-invalid @enderror"
                                value="{{ old('service_date', now()->format('Y-m-d')) }}">
                            @error('service_date')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    {{-- Keluhan --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label ">Keluhan Kendaraan</label>
                            <input type="text" name="keluhan" class="form-control @error('keluhan') is-invalid @enderror"
                                placeholder="Contoh: Mesin brebet, ganti oli, rem blong" value="{{ old('keluhan') }}">
                            @error('keluhan')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Daftar Jasa / Keluhan</h4>
                    <button type="button" class="btn btn-sm btn-primary" id="btn_add_service">
                        <i class="ti ti-plus"></i> Tambah Jasa
                    </button>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-vcenter" id="service_table">
                        <thead>
                            <tr>
                                <th>Jasa Servis</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Subtotal</th>
                                <th style="width: 5%">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="service_cart_body">
                            <!-- JS akan mengisi ini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Jasa:</th>
                                <th>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="total_service_display"
                                            class="form-control fw-bold bg-light" readonly value="0">
                                        <input type="hidden" name="total_service" id="total_service" value="0">
                                    </div>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr class="my-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Daftar Suku Cadang (Sparepart)</h4>
                    <button type="button" class="btn btn-sm btn-success" id="btn_add_sparepart">
                        <i class="ti ti-plus"></i> Tambah Sparepart
                    </button>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-vcenter" id="sparepart_table">
                        <thead>
                            <tr>
                                <th>Sparepart</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Subtotal</th>
                                <th style="width: 5%">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="sparepart_cart_body">
                            <!-- JS akan mengisi ini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Sparepart:</th>
                                <th>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="total_part_display"
                                            class="form-control fw-bold bg-light" readonly value="0">
                                        <input type="hidden" name="total_part" id="total_part" value="0">
                                    </div>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h4 class="mb-3">Rincian Tambahan</h4>

                <div class="row row-cards">
                    {{-- <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label ">Total Sparepart</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="total_part" id="total_part"
                                    class="form-control @error('total_part') is-invalid @enderror"
                                    value="{{ old('total_part', 0) }}">
                            </div>
                            @error('total_part')
                            
                            @enderror
                        </div>
                    </div> --}}

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Diskon</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="discount" id="discount"
                                    class="form-control @error('discount') is-invalid @enderror"
                                    value="{{ old('discount', 0) }}">
                            </div>
                            @error('discount')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Pajak (Tax)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" min="0" name="tax" id="tax"
                                    class="form-control @error('tax') is-invalid @enderror" value="{{ old('tax', 0) }}">
                            </div>
                            @error('tax')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label ">Grand Total</label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold">Rp</span>
                                <input type="text" id="grand_total" class="form-control fw-bold bg-light" readonly
                                    value="0">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label ">Metode Pembayaran</label>
                            <select name="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror">
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai
                                    (Cash)</option>
                                <option value="debit" {{ old('payment_method') == 'debit' ? 'selected' : '' }}>Kartu
                                    Debit</option>
                                <option value="credit" {{ old('payment_method') == 'credit' ? 'selected' : '' }}>Kartu
                                    Kredit</option>
                                <option value="midtrans" {{ old('payment_method') == 'midtrans' ? 'selected' : '' }}>
                                    Midtrans / Online</option>
                            </select>
                            @error('payment_method')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea name="note" rows="2" class="form-control @error('note') is-invalid @enderror"
                                placeholder="Opsional: Tulis instruksi mekanik atau catatan transaksi...">{{ old('note') }}</textarea>
                            @error('note')
                                {{-- <div class="invalid-feedback">{{ $message }}</div> --}}
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('serviceorder.index') }}" class="btn btn-link">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn_submit">
                    Buat Service Order
                </button>
            </div>
        </form>
    </div>

    @include('service_order.secript')
@endsection
