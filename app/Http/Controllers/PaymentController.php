<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Snap;

class PaymentController extends Controller
{

    // callback midtrans
    public function handleCallback(Request $request)
    {

        $serverKey = config('midtrans.server_key');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }



        $data = $request->all();
        $transactionStatus = $request->transaction_status;

        $transaction = Transaction::where('kode_transaksi', $data['order_id'])->first();
      

        if (!$transaction) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $transaction->update(['status' => 'pending']);
                    } else {
                        $transaction->update(['status' => 'completed']);
                    }
                }
                break;
            case 'settlement':
                $transaction->update(['status' => 'completed']);
                break;
            case 'pending':
                $transaction->update(['status' => 'pending']);
                break;
            case 'deny':
                $transaction->update(['status' => 'failed']);
                break;
            case 'expire':
                $transaction->update(['status' => 'failed']);
                break;
            case 'cancel':
                $transaction->update(['status' => 'failed']);
                break;
            default:
                $transaction->update(['status' => 'pending']);
                break;
        }
    }
}
