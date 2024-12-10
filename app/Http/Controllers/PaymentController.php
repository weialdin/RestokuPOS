<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        // Generate a unique order_id for the transaction
        $orderId = Str::uuid();

        // Create the parameters for Midtrans API
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,  // Use the generated order_id
                'gross_amount' => $request->price,
            ],
            'item_details' => [
                [
                    'price' => $request->price,
                    'quantity' => 1,
                    'name' => $request->item_name,
                ]
            ],
            'customer_details' => [
                'first_name' => $request->customerFirstName,
            ],
        ];

        // Authentication for Midtrans API
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        // Send request to Midtrans API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        // Decode the response from Midtrans
        $response = json_decode($response->body());

        // Get order details based on the generated order_id
        $orderDetails = OrderDetail::where('order_id', $orderId)->get();

        if ($orderDetails->isEmpty()) {
            return response()->json(['error' => 'Order details not found.'], 404);
        }

        // Create a new Payment record
        $payment = new Payment();
        $payment->order_id = $orderDetails->first()->order_id;  // Get order_id from order_details
        $payment->status = 'pending';  // Set initial payment status to 'pending'
        $payment->item_name = $orderDetails->first()->menu_name;  // Get item name
        $payment->customer_first_name = $request->customerFirstName;  // Customer's first name
        $payment->checkout_link = $response->redirect_url ?? '';  // Midtrans checkout link
        $payment->price = $orderDetails->sum('subtotal');  // Total price (sum of subtotals)
        $payment->save();

        // Return Midtrans response with payment details
        return response()->json([
            'payment_link' => $payment->checkout_link,
            'midtrans_response' => $response
        ]);
    }

    public function webhook(Request $request)
    {
        // Authentication for Midtrans API
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        // Send request to Midtrans API to check transaction status
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->get("https://api.sandbox.midtrans.com/v2/{$request->order_id}/status");

        // Decode the response from Midtrans
        $response = json_decode($response->body());

        // Find the payment record based on the order_id
        $payment = Payment::where('order_id', $response->order_id)->firstOrFail();

        // Update the payment status based on Midtrans response
        switch ($response->transaction_status) {
            case 'capture':
                $payment->status = 'capture';
                break;
            case 'settlement':
                $payment->status = 'settlement';
                break;
            case 'pending':
                $payment->status = 'pending';
                break;
            case 'deny':
                $payment->status = 'deny';
                break;
            case 'expire':
                $payment->status = 'expire';
                break;
            case 'cancel':
                $payment->status = 'cancel';
                break;
        }

        // Save the updated payment status
        $payment->save();

        // Return a success response
        return response()->json(['message' => 'Payment status updated successfully.']);
    }
}
