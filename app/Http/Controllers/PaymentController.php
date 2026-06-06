<?php

namespace App\Http\Controllers;

use App\Models\MidtransTransaction;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $orderId = $request->get('order_id');

        if ($orderId) {
            $this->initMidtrans();
            try {
                // Get transaction status from Midtrans API
                $status = \Midtrans\Transaction::status($orderId);
                
                // Find midtrans transaction record
                $midtransTransaction = MidtransTransaction::where('order_id', $orderId)->first();
                
                if ($midtransTransaction) {
                    $order = $midtransTransaction->serviceOrder;
                    
                    if ($order) {
                        /** @var object $status */
                        $transactionStatus = $status->transaction_status;
                        $fraudStatus = $status->fraud_status;
                        
                        $dbMidtransStatus = 'pending';
                        $dbPaymentStatus = 'unpaid';
                        $paidAt = null;

                        if ($transactionStatus == 'capture') {
                            if ($fraudStatus == 'challenge') {
                                $dbMidtransStatus = 'pending';
                            } else if ($fraudStatus == 'accept') {
                                $dbMidtransStatus = 'paid';
                                $dbPaymentStatus = 'paid';
                                $paidAt = now();
                            }
                        } else if ($transactionStatus == 'settlement') {
                            $dbMidtransStatus = 'paid';
                            $dbPaymentStatus = 'paid';
                            $paidAt = now();
                        } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                            $dbMidtransStatus = 'failed';
                            $dbPaymentStatus = 'unpaid';
                        }

                        // Update service order status
                        $order->update([
                            'payment_status' => $dbPaymentStatus,
                            'midtrans_status' => $dbMidtransStatus,
                            'paid_at' => $paidAt,
                        ]);

                        // Update midtrans transaction status
                        $midtransTransaction->update([
                            'transaction_status' => $transactionStatus,
                            'transaction_id' => $status->transaction_id,
                            'payment_type' => $status->payment_type,
                            'response_payload' => json_encode($status),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Midtrans status sync failed: ' . $e->getMessage());
            }
        }

        return view('payment.finished', [
            'order_id' => $request->get('order_id'),
            'status_code' => $request->get('status_code'),
            'transaction_status' => $request->get('transaction_status')
        ]);
    }
}
