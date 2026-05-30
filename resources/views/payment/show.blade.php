@extends('layouts.app')
@section('title', 'Pembayaran Order #' . $order->order_number)
@section('page_title', 'Invoice Pembayaran Bengkel')

@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8 mx-auto">
                <div class="card card-md">
                    <div class="card-status-top bg-green"></div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">Ringkasan Tagihan Nota Servis</h3>
                            <p class="card-subtitle text-muted">Silahkan periksa detail data order sebelum melakukan
                                pembayaran.</p>
                        </div>
                        <span class="badge bg-warning-lt px-3 py-2">Menunggu Pembayaran</span>
                    </div>

                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card card-sm bg-light-lt border-0 shadow-none">
                                    <div class="card-body">
                                        <div class="text-muted mb-1">Informasi Pelanggan</div>
                                        <div class="font-weight-medium h3 mb-1 text-dark">{{ $order->customer->name }}</div>
                                        <div class="text-secondary d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1 text-muted"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                            </svg>
                                            {{ $order->customer->phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-sm bg-light-lt border-0 shadow-none">
                                    <div class="card-body">
                                        <div class="text-muted mb-1">Detail Kendaraan</div>
                                        <div class="font-weight-medium h3 mb-1 text-dark">{{ $order->vehicle->brand }}</div>
                                        <div class="text-secondary d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1 text-muted"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M5 11h14v4h-14z" />
                                                <path d="M7 11v-4a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4" />
                                                <path d="M11 5v4" />
                                                <path d="M14 11v4" />
                                            </svg>
                                            <span class="badge bg-dark-lt font-monospace text-uppercase ms-1"
                                                style="letter-spacing: 0.5px;">{{ $order->vehicle->plate_number }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top my-4"></div>

                        <div class="d-flex justify-content-between align-items-center text-muted mb-3">
                            <div>No. Order / Ref</div>
                            <div class="font-monospace text-dark font-weight-medium">#{{ $order->order_number }}</div>
                        </div>

                        <div class="card bg-primary-lt border-0 shadow-none">
                            <div class="card-body d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h4 class="mb-0 text-primary">Total yang Harus Dibayar</h4>
                                    <small class="text-muted">Sudah termasuk PPN & Jasa Pemasangan</small>
                                </div>
                                <h1 class="text-primary mb-0 font-weight-bold" style="font-size: 1.8rem;">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <button type="button" id="pay-button"
                            class="btn btn-success btn-lg px-4 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                <path d="M3 10l18 0" />
                                <path d="M7 15l.01 0" />
                                <path d="M11 15l2 0" />
                            </svg>
                            Bayar Sekarang via Midtrans
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panggil Script Snap Midtrans Sandbox --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();

            const button = this;
            button.disabled = true;
            // Memberikan feedback loading dengan spinner bawaan Bootstrap
            button.innerHTML =
                `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memuat Pembayaran...`;

            // Tembak Controller Laravel via AJAX Fetch untuk meminta SNAP Token
            fetch("{{ route('payment.snap-token', $order->id) }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        // Munculkan Pop-up SNAP Midtrans jika token sukses didapat
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                window.location.href = "{{ route('payment.finished') }}?order_id=" +
                                    result.order_id + "&status_code=" + result.status_code +
                                    "&transaction_status=" + result.transaction_status;
                            },
                            onPending: function(result) {
                                window.location.href = "{{ route('payment.finished') }}?order_id=" +
                                    result.order_id + "&status_code=" + result.status_code +
                                    "&transaction_status=" + result.transaction_status;
                            },
                            onError: function(result) {
                                alert("Pembayaran gagal!");
                                resetButton(button);
                            },
                            onClose: function() {
                                alert('Anda menutup pop-up sebelum menyelesaikan pembayaran.');
                                resetButton(button);
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan akses token dari server.');
                        resetButton(button);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resetButton(button);
                });
        };

        function resetButton(button) {
            button.disabled = false;
            button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
            Bayar Sekarang via Midtrans
        `;
        }
    </script>
@endsection
