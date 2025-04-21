<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Member;
use App\User;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function createPayment(Request $request)
    {
        // Set Midtrans server key
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'MEMBER-' . Str::uuid(),
                'gross_amount' => 50000,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'custom_field1' => auth()->user()->id,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error("Error creating payment: " . $e->getMessage());
            return response()->json(['error' => 'Pembayaran gagal, coba lagi.'], 500);
        }
    }

    public function paymentSuccess()
    {
        $member = Member::firstOrCreate([
            'id_user' => auth()->user()->id,
        ]);

        $member->saldo += 50000;
        $member->save();

        return redirect()->back()->with('success', 'Pendaftaran member berhasil!');
    }

    public function handleNotification(Request $request)
    {
        Log::info('Midtrans Notification received', ['data' => $request->all()]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notification = new Notification();
        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $amount = (int) $notification->gross_amount;

        Log::info('Notification data', [
            'status' => $transactionStatus,
            'order_id' => $orderId,
            'amount' => $amount,
            'custom_field1' => $notification->custom_field1 ?? 'null',
        ]);

        if ($transactionStatus === 'settlement') {
            $userId = $notification->custom_field1 ?? null;

            if ($userId) {
                $member = Member::firstOrCreate(['id_user' => $userId]);

                $member->saldo += $amount;
                $member->save();

                Log::info("Saldo berhasil ditambahkan untuk member user_id: $userId");
            }
        }

        return response()->json(['message' => 'Notification processed']);
    }
}
