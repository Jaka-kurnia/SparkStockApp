@extends('layouts.app')
@section('title', 'Pembayaran Order #' . $order->kode_order)
@section('page_title', 'Invoice Pembayaran Bengkel')

@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-2 navbar-brand">
                                            <h3 class="card-title mb-0 me-2">Ringkasan Tagihan Nota Servis</h3>
                                            <span class="badge bg-warning text-warning-fg rounded-pill ms-2">Menunggu Pembayaran</span>
                                        </div>
                                        <p class="mb-0">No. Order: <span class="font-monospace fw-bold">#{{ $order->kode_order }}</span></p>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <h6>Tanggal Order: <span class="text-muted f-w-400">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</span></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="border rounded p-3" style="height: 100%;">
                                    <h6 class="mb-1 text-muted">Detail Kendaraan:</h6>
                                    <h5 class="mb-1 text-dark">{{ $order->vehicle->brand }}</h5>
                                    <p class="mb-0">
                                        Plat Nomor: 
                                        <span class="badge bg-dark-lt font-monospace text-uppercase" style="letter-spacing: 0.5px;">
                                            {{ $order->vehicle->plate_number }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded p-3" style="height: 100%;">
                                    <h6 class="mb-1 text-muted">Pelanggan:</h6>
                                    <h5>{{ $order->customer->name }}</h5>
                                    <p class="mb-0 text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline me-1 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                        </svg>
                                        {{ $order->customer->phone }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Deskripsi Layanan / Item Bengkel</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Biaya Perawatan & Jasa Pemasangan Bengkel (All-in)</td>
                                                <td class="text-end font-monospace">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="invoice-total ms-auto" style="max-width: 350px;">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <p class="f-w-600 mb-0 text-start fw-bold text-primary">Grand Total :</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="f-w-600 mb-0 text-end fw-bold text-primary h2 font-monospace">
                                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted">Catatan</label>
                                <p class="mb-0 text-secondary">Silahkan periksa detail data order sebelum melakukan pembayaran. Total di atas sudah termasuk PPN (jika ada) & Jasa Pemasangan.</p>
                            </div>

                            <div class="col-12 text-end d-print-none mt-4">
                                <button type="button" id="pay-button" class="btn btn-success">
                                    Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Snap Midtrans Sandbox --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(e) {
            e.preventDefault();

            const button = this;
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memuat Pembayaran...`;

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