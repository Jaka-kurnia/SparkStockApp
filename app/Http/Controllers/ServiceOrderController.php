<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderDetail;
use App\Models\Sparepart;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceOrders = ServiceOrder::with(['customer', 'vehicle', 'mechanic'])->paginate(5);
        return view('service_order.index', compact('serviceOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name', 'asc')->get();
        $vehicles = Vehicle::orderBy('plate_number', 'asc')->get();
        $mechanics = Mechanic::orderBy('name_mechanic', 'asc')->get();
        $services = Service::orderBy('complaint_name', 'asc')->get();
        $spareparts = Sparepart::where('stock', '>', 0)->orderBy('name', 'asc')->get();
        return view('service_order.create', compact('customers', 'vehicles', 'mechanics', 'services', 'spareparts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'mechanic_id' => 'required|exists:mechanics,id',
            'service_date' => 'required|date',
            'keluhan' => 'required|string',
            'total_service' => 'required|numeric|min:0',
            'total_part' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,credit,midtrans',
            'note' => 'nullable|string',
            'services' => 'nullable|array',
            'services.*.service_id' => 'required_with:services|exists:services,id',
            'services.*.price' => 'required_with:services|numeric|min:0',
            'services.*.qty' => 'required_with:services|integer|min:1',
            'spareparts' => 'nullable|array',
            'spareparts.*.sparepart_id' => 'required_with:spareparts|exists:spareparts,id',
            'spareparts.*.price' => 'required_with:spareparts|numeric|min:0',
            'spareparts.*.qty' => 'required_with:spareparts|integer|min:1',
        ], [
            'customer_id.required' => 'Pelanggan wajib dipilih',
            'customer_id.exists' => 'Pelanggan tidak valid',
            'vehicle_id.required' => 'Kendaraan wajib dipilih',
            'vehicle_id.exists' => 'Kendaraan tidak valid',
            'mechanic_id.required' => 'Mekanik wajib dipilih',
            'mechanic_id.exists' => 'Mekanik tidak valid',
            'service_date.required' => 'Tanggal servis wajib diisi',
            'service_date.date' => 'Tanggal servis tidak valid',
            'keluhan.required' => 'Keluhan wajib diisi',
            'total_service.required' => 'Total biaya jasa wajib diisi',
            'total_service.numeric' => 'Total biaya jasa harus berupa angka',
            'total_service.min' => 'Total biaya jasa tidak boleh negatif',
            'total_part.required' => 'Total biaya part wajib diisi',
            'total_part.numeric' => 'Total biaya part harus berupa angka',
            'total_part.min' => 'Total biaya part tidak boleh negatif',
            'discount.numeric' => 'Diskon harus berupa angka',
            'discount.min' => 'Diskon tidak boleh negatif',
            'tax.numeric' => 'Pajak harus berupa angka',
            'tax.min' => 'Pajak tidak boleh negatif',
            'payment_method.required' => 'Metode pembayaran wajib dipilih',
            'payment_method.in' => 'Metode pembayaran tidak valid',
            'services.*.service_id.required_with' => 'Layanan wajib dipilih',
            'services.*.service_id.exists' => 'Layanan tidak valid',
            'services.*.price.required_with' => 'Harga layanan wajib diisi',
            'services.*.price.numeric' => 'Harga layanan harus berupa angka',
            'services.*.price.min' => 'Harga layanan tidak boleh negatif',
            'services.*.qty.required_with' => 'Kuantitas layanan wajib diisi',
            'services.*.qty.integer' => 'Kuantitas layanan harus berupa angka bulat',
            'services.*.qty.min' => 'Kuantitas layanan minimal 1',
        ]);

        $totalService = $request->total_service;
        $totalPart = $request->total_part;
        $discount = $request->discount ?? 0;
        $tax = $request->tax ?? 0;
        $grandTotal = ($totalService + $totalPart) - $discount + $tax;

        // Generate kode order
        $today = now()->format('Ymd');
        $lastOrder = ServiceOrder::where('kode_order', 'like', "TRX-$today-%")->latest('id')->first();
        $sequence = $lastOrder ? (int) substr($lastOrder->kode_order, -3) + 1 : 1;
        $kodeOrder = "TRX-$today-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);

        // Generate kode queue
        $lastQueue = ServiceOrder::where('service_date', $request->service_date)->latest('id')->first();
        $queueSeq = $lastQueue ? (int) substr($lastQueue->kode_queue, -3) + 1 : 1;
        $kodeQueue = 'A' . str_pad($queueSeq, 3, '0', STR_PAD_LEFT);

        // Tentukan status pembayaran awal di sini
        // Cash, Debit, dan Credit dianggap langsung lunas di kasir/bengkel. Midtrans menunggu callback webhook.
        $paymentStatus = in_array($request->payment_method, ['cash', 'debit', 'credit']) ? 'paid' : 'unpaid';

        $now = now();

        // Menggunakan DB Transaction agar aman jika terjadi error di tengah proses database
        $serviceOrder = DB::transaction(function () use ($request, $kodeOrder, $kodeQueue, $totalService, $totalPart, $discount, $tax, $grandTotal, $paymentStatus,) {

            $order = ServiceOrder::create([
                'customer_id' => $request->customer_id,
                'vehicle_id' => $request->vehicle_id,
                'mechanic_id' => $request->mechanic_id,
                'kode_order' => $kodeOrder,
                'kode_queue' => $kodeQueue,
                'status' => 'pending',
                'keluhan' => $request->keluhan,
                'service_date' => $request->service_date,
                'total_service' => $totalService,
                'total_part' => $totalPart,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'paid_at' => $paymentStatus == 'paid' ? now() : null,
                'note' => $request->note
            ]);

            if ($request->has('services')) {
                foreach ($request->services as $service) {
                    $subtotal = $service['price'] * $service['qty'];
                    \App\Models\ServiceOrderService::create([
                        'service_order_id' => $order->id,
                        'service_id' => $service['service_id'],
                        'quantity' => $service['qty'],
                        'price' => $service['price'],
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            if ($request->has('spareparts')) {
                foreach ($request->spareparts as $part) {
                    $subtotal = $part['price'] * $part['qty'];
                    ServiceOrderDetail::create([
                        'service_order_id' => $order->id,
                        'sparepart_id' => $part['sparepart_id'],
                        'quantity' => $part['qty'],
                        'price' => $part['price'],
                        'subtotal' => $subtotal,
                    ]);

                    // Kurangi stok sparepart
                    $sparepart = \App\Models\Sparepart::find($part['sparepart_id']);
                    if ($sparepart) {
                        $sparepart->decrement('stock', $part['qty']);
                    }
                }
            }

            return $order;
        });

        // Mengarahkan halaman berdasarkan metode pembayaran
        if ($request->payment_method == 'midtrans') {
            return redirect()->route('service-orders.show', $serviceOrder->id)
                ->with('success', 'Service Order berhasil dibuat! Silakan selesaikan pembayaran Midtrans Anda.');
        }

        return redirect()->route('serviceorder.index')
            ->with('success', 'Service Order berhasil dibuat dan pembayaran lunas!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceOrder $serviceOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceOrder $serviceOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceOrder $serviceOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceOrder $serviceOrder)
    {
        //
    }
}
