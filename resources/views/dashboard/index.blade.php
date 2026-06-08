@extends('layouts.app')
@section('title', 'Dashboard Analitik')
@section('page_title', 'Dashboard Analitik')
@section('content')
    <div class="row row-cards">
        <!-- Filter Card -->
        <div class="col-12">
            <div class="card card-md shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('dashboard.index') }}" method="GET" id="filter-form">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label font-weight-bold text-muted">Periode Analisis</label>
                                <select name="period" id="period-select" class="form-select form-control">
                                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Kustom Rentang Tanggal</option>
                                </select>
                            </div>

                            <div class="col-md-3 date-range-inputs {{ $period == 'custom' ? '' : 'd-none' }}">
                                <label class="form-label font-weight-bold text-muted">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate->toDateString() }}">
                            </div>

                            <div class="col-md-3 date-range-inputs {{ $period == 'custom' ? '' : 'd-none' }}">
                                <label class="form-label font-weight-bold text-muted">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate->toDateString() }}">
                            </div>

                            <div class="col-md-3 d-flex align-items-end gap-2 mt-auto">
                                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                    <i class="ti ti-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('dashboard.exportPdf', request()->all()) }}" target="_blank" class="btn btn-danger btn-icon shadow-sm" title="Ekspor PDF">
                                    <i class="ti ti-file-type-pdf fs-2"></i>
                                </a>
                                <a href="{{ route('dashboard.exportExcel', request()->all()) }}" class="btn btn-success btn-icon shadow-sm" title="Ekspor Excel">
                                    <i class="ti ti-file-spreadsheet fs-2"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Metric Statistics -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm bg-gradient-green text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-white text-green avatar rounded-circle">
                                <i class="ti ti-wallet fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-white-50">Total Pendapatan</div>
                            <div class="h1 mb-0 font-weight-bold text-white">
                                Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm bg-gradient-blue text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-white text-blue avatar rounded-circle">
                                <i class="ti ti-package fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-white-50">Total Order Servis</div>
                            <div class="h1 mb-0 font-weight-bold text-white">{{ $stats['total_orders'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm bg-gradient-cyan text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-white text-cyan avatar rounded-circle">
                                <i class="ti ti-checkbox fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-white-50">Servis Selesai</div>
                            <div class="h1 mb-0 font-weight-bold text-white">{{ $stats['completed_orders'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm border-0 shadow-sm bg-gradient-orange text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-white text-orange avatar rounded-circle">
                                <i class="ti ti-settings-automation fs-2"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium text-white-50">Sparepart Terjual</div>
                            <div class="h1 mb-0 font-weight-bold text-white">{{ $stats['parts_sold'] }} <span style="font-size: 0.8rem;">pcs</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Info Cards -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader text-muted">Total Pelanggan Terdaftar</div>
                    </div>
                    <div class="h2 mb-2 font-weight-bold text-dark">{{ $stats['total_customers'] }}</div>
                    <div class="text-muted fs-5">Pelanggan Aktif</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader text-muted">Mekanik Aktif</div>
                    </div>
                    <div class="h2 mb-2 font-weight-bold text-dark">{{ $stats['total_mechanics'] }}</div>
                    <div class="text-muted fs-5">Siap Melayani Pelanggan</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="subheader text-muted">Sparepart Hampir Habis</div>
                    </div>
                    <div class="h2 mb-2 font-weight-bold {{ $stats['low_stock'] > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $stats['low_stock'] }}
                    </div>
                    <div class="text-muted fs-5">Stok < 5 unit</div>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <!-- 1. Revenue & Order Trends -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold text-dark">Tren Pendapatan & Jumlah Order</h3>
                    <div id="chart-revenue-trends" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- 2. Payment Methods -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold text-dark">Metode Pembayaran</h3>
                    <div id="chart-payment-methods" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- 3. Mechanic Performance -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold text-dark">Kinerja Mekanik (Jumlah Order Servis)</h3>
                    <div id="chart-mechanic-performance" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- 4. Top Services -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title font-weight-bold text-dark">Top 5 Layanan Terlaris</h3>
                    <div id="chart-top-services" style="min-height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles for premium aesthetics -->
    <style>
        .bg-gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .bg-gradient-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .bg-gradient-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }
        .bg-gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        .card-sm .avatar {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>

  @include('dashboard.script')
@endsection