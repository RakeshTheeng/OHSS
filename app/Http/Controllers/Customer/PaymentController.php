<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display payment history for the customer
     */
    public function index()
    {
        $customer = auth()->user();

        $payments = Payment::where('customer_id', $customer->id)
                          ->with(['booking.serviceRequest.serviceCategory', 'provider'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $customer = auth()->user();

        if ($payment->customer_id !== $customer->id) {
            abort(403);
        }

        return view('customer.payments.show', compact('payment'));
    }

    /**
     * Initiate eSewa payment
     */
    public function esewa(Payment $payment)
    {
        $customer = auth()->user();

        if ($payment->customer_id !== $customer->id) {
            abort(403);
        }

        if (!in_array($payment->status, ['awaiting_payment', 'pending'])) {
            return redirect()->route('customer.bookings.show', $payment->booking)
                           ->with('error', 'Payment has already been processed.');
        }

        // Generate unique transaction UUID (alphanumeric and hyphen only as per eSewa docs)
        $transactionUuid = 'OHSS-' . time() . '-' . $payment->id;

        // eSewa configuration (exactly as per eSewa documentation)
        $esewaConfig = [
            'amount' => $payment->amount,
            'tax_amount' => 0,
            'total_amount' => $payment->amount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => config('services.esewa.product_code', 'EPAYTEST'),
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => route('customer.payments.esewa.success'),
            'failure_url' => route('customer.payments.esewa.failure'),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        // Generate signature using HMAC SHA256 (exactly as per eSewa documentation)
        $signature = $this->generateEsewaSignature(
            $esewaConfig['total_amount'],
            $esewaConfig['transaction_uuid'],
            $esewaConfig['product_code']
        );

        $esewaConfig['signature'] = $signature;

        // Update payment with transaction UUID
        $payment->update(['transaction_id' => $transactionUuid]);

        return view('customer.payments.esewa', compact('payment', 'esewaConfig'));
    }

    /**
     * Handle eSewa success callback
     */
    public function esewaSuccess(Request $request)
    {
        $data = $request->get('data');

        if (!$data) {
            return redirect()->route('customer.dashboard')
                           ->with('error', 'Invalid payment response.');
        }

        // Decode the base64 response
        $decodedData = json_decode(base64_decode($data), true);

        if (!$decodedData) {
            return redirect()->route('customer.dashboard')
                           ->with('error', 'Invalid payment data.');
        }

        // Find payment by transaction UUID
        $transactionUuid = $decodedData['transaction_uuid'] ?? null;
        $payment = Payment::where('transaction_id', $transactionUuid)->first();

        if (!$payment) {
            return redirect()->route('customer.dashboard')
                           ->with('error', 'Payment not found.');
        }

        // Verify signature
        $expectedSignature = $this->generateEsewaSignature(
            $decodedData['total_amount'],
            $decodedData['transaction_uuid'],
            $decodedData['product_code']
        );

        if ($decodedData['signature'] !== $expectedSignature) {
            return redirect()->route('customer.bookings.show', $payment->booking)
                           ->with('error', 'Payment verification failed.');
        }

        // Mark payment as completed
        $payment->markAsPaid($decodedData['transaction_code'], $decodedData);
        $payment->update(['esewa_ref_id' => $decodedData['transaction_code']]);

        // Now confirm the booking since payment is complete
        $booking = $payment->booking;
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid'
        ]);

        // Create chat for communication now that booking is confirmed
        if (!$booking->chat) {
            Chat::create([
                'booking_id' => $booking->id,
                'customer_id' => $payment->customer_id,
                'provider_id' => $payment->provider_id,
            ]);
        }

        // Notify provider about confirmed booking with payment
        Notification::createForUser(
            $payment->provider_id,
            'booking_confirmed_paid',
            'New Paid Booking Confirmed',
            "You have a new confirmed booking with payment from " . $payment->customer->name,
            ['booking_id' => $booking->id]
        );

        return redirect()->route('customer.bookings.show', $payment->booking)
                        ->with('success', 'Payment completed successfully! Your booking is now confirmed.');
    }

    /**
     * Handle eSewa failure callback
     */
    public function esewaFailure(Request $request)
    {
        return redirect()->route('customer.dashboard')
                        ->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    /**
     * Generate eSewa signature using HMAC SHA256 (as per eSewa documentation)
     */
    private function generateEsewaSignature($totalAmount, $transactionUuid, $productCode)
    {
        // Message format exactly as per eSewa documentation
        $message = "total_amount={$totalAmount},transaction_uuid={$transactionUuid},product_code={$productCode}";

        // Secret key from configuration (UAT key: 8gBm/:&EnhH.1/q)
        $secretKey = config('services.esewa.secret_key', '8gBm/:&EnhH.1/q');

        // Generate HMAC SHA256 signature and encode in base64 (as per eSewa docs)
        $signature = base64_encode(hash_hmac('sha256', $message, $secretKey, true));

        // Log signature generation details for debugging
        \Log::info('eSewa Signature Generation', [
            'message' => $message,
            'secret_key' => $secretKey,
            'signature' => $signature,
            'total_amount' => $totalAmount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode
        ]);

        return $signature;
    }

    /**
     * Initiate Khalti payment
     */
    public function khalti(Payment $payment)
    {
        // Check if payment belongs to authenticated customer
        if ($payment->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access to payment');
        }

        // Check if payment can be processed
        if (!in_array($payment->status, ['awaiting_payment', 'pending'])) {
            return redirect()->route('customer.payments.show', $payment)
                ->with('error', 'This payment has already been processed.');
        }

        // Generate unique transaction identifier
        $transactionId = 'OHSS-KH-' . time() . '-' . $payment->id;

        // Khalti configuration
        $khaltiConfig = [
            'public_key' => config('services.khalti.public_key'),
            'amount' => (int)($payment->amount * 100), // Convert to paisa (Rs. 1 = 100 paisa) and ensure integer
            'product_identity' => $transactionId,
            'product_name' => 'OHSS Service - ' . $payment->booking->serviceRequest->serviceCategory->name,
            'product_url' => url('/'),
            'payment_id' => $payment->id,
        ];

        // Update payment with transaction ID
        $payment->update(['transaction_id' => $transactionId]);

        return view('customer.payments.khalti', compact('payment', 'khaltiConfig'));
    }

    /**
     * Verify Khalti payment
     */
    public function verifyKhaltiPayment(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'amount' => 'required|numeric',
            'payment_id' => 'required|exists:payments,id',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Check if payment belongs to authenticated customer
        if ($payment->customer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }

        // Check if payment is still pending
        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment already processed'], 400);
        }

        // Verify payment with Khalti API
        $verificationResult = $this->verifyWithKhaltiAPI($request->token, $request->amount);

        if ($verificationResult['success']) {
            // Update payment status
            $payment->update([
                'status' => 'completed',
                'payment_method' => 'khalti',
                'transaction_id' => $verificationResult['data']['idx'] ?? $payment->transaction_id,
                'paid_at' => now(),
            ]);

            // Update booking status
            $payment->booking->update(['status' => 'confirmed']);

            // Log successful payment
            \Log::info('Khalti Payment Successful', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'khalti_response' => $verificationResult['data']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect_url' => route('customer.payments.khalti.success', ['payment' => $payment->id])
            ]);
        } else {
            // Log failed verification
            \Log::error('Khalti Payment Verification Failed', [
                'payment_id' => $payment->id,
                'error' => $verificationResult['message']
            ]);

            return response()->json([
                'success' => false,
                'message' => $verificationResult['message']
            ], 400);
        }
    }

    /**
     * Handle successful Khalti payment
     */
    public function khaltiSuccess(Request $request)
    {
        $paymentId = $request->query('payment');
        $payment = Payment::find($paymentId);

        if (!$payment || $payment->customer_id !== auth()->id()) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Payment not found or unauthorized access.');
        }

        return view('customer.payments.success', compact('payment'))
            ->with('success', 'Payment completed successfully via Khalti!');
    }

    /**
     * Handle failed Khalti payment
     */
    public function khaltiFailure(Request $request)
    {
        $paymentId = $request->query('payment');
        $payment = Payment::find($paymentId);

        if (!$payment || $payment->customer_id !== auth()->id()) {
            return redirect()->route('customer.dashboard')
                ->with('error', 'Payment not found or unauthorized access.');
        }

        return view('customer.payments.failure', compact('payment'))
            ->with('error', 'Khalti payment was cancelled or failed.');
    }

    /**
     * Verify payment with Khalti API
     */
    private function verifyWithKhaltiAPI($token, $amount)
    {
        $secretKey = config('services.khalti.secret_key');
        $verifyUrl = config('services.khalti.verify_url');

        $postData = http_build_query([
            'token' => $token,
            'amount' => $amount
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Key {$secretKey}",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'message' => 'Network error: ' . $error
            ];
        }

        if ($statusCode == 200) {
            $responseData = json_decode($response, true);
            return [
                'success' => true,
                'data' => $responseData,
                'message' => 'Payment verified successfully'
            ];
        } else {
            $errorData = json_decode($response, true);
            return [
                'success' => false,
                'message' => $errorData['detail'] ?? 'Payment verification failed',
                'status_code' => $statusCode
            ];
        }
    }
}
