<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use App\Models\MidtransTransaction;

use Midtrans\Snap;


class PaymentController extends Controller
{
    private function initMidtrans()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function show(ServiceOrder $order)
    {
        $order->load(['customer', 'vehicle', 'midtransTransaction']);
        return view('payment.show', compact('order'));
    }

    public function getSnapToken(ServiceOrder $order)
    {
        $this->initMidtrans();

        if ($order->midtransTransaction && $order->midtransTransaction->snap_token) {
            return response()->json(['snap_token' => $order->midtransTransaction->snap_token]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->kode_order . '-' . time(),
                'gross_amount' => (int) $order->grand_total,
            ],
            'customer_details' => [
                'first_name' => $order->customer->name,
                'phone' => $order->customer->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            MidtransTransaction::create([
                'service_order_id' => $order->id,
                'order_id' => $params['transaction_details']['order_id'],
                'snap_token' => $snapToken,
                'gross_amount' => $order->grand_total,
                'transaction_status' => 'pending',
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

  
    public function paymentFinished(Request $request)
    {
        return view('payment.finished', [
            'order_id' => $request->get('order_id'),
            'status_code' => $request->get('status_code'),
            'transaction_status' => $request->get('transaction_status')
        ]);
    }
}
