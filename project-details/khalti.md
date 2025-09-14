Khalti Test Payment Integration (Laravel Project)
This document explains how to integrate Khalti Payment Gateway in test mode into your Laravel application.

1. Prerequisites
Before starting, ensure you have:

A Khalti merchant account → https://khalti.com/join/merchant/

Test mode API keys (Public Key & Secret Key) from Khalti Sandbox.

Laravel project set up and running.

2. Integration Flow
The payment integration follows these steps:

Customer clicks “Pay with Khalti” on your site.

Khalti Checkout Widget opens for payment.

On success, Khalti returns a token and amount to your Laravel backend.

Backend verifies payment with Khalti API using the secret key.

If verified, order/service status is updated to Paid.

3. Step-by-Step Implementation
Step 1 — Add Khalti JavaScript Checkout in Blade View
Create a payment button in your Blade template.

blade
Copy
Edit
<!-- resources/views/payment.blade.php -->

<button id="payment-button" class="btn btn-primary">Pay with Khalti</button>

<script src="https://khalti.com/static/khalti-checkout.js"></script>
<script>
    var config = {
        publicKey: "{{ env('KHALTI_PUBLIC_KEY') }}", // From .env
        productIdentity: "service-123",
        productName: "Home Service Booking",
        productUrl: "https://yourwebsite.com/order/123",
        eventHandler: {
            onSuccess (payload) {
                // Send token & amount to server for verification
                fetch("/verify-khalti-payment", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        token: payload.token,
                        amount: payload.amount
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        alert("Payment successful!");
                        location.reload();
                    } else {
                        alert("Payment verification failed!");
                    }
                });
            },
            onError (error) {
                console.log(error);
                alert("Payment Error");
            },
            onClose () {
                console.log("Khalti widget closed");
            }
        }
    };
    var checkout = new KhaltiCheckout(config);
    document.getElementById("payment-button").onclick = function () {
        checkout.show({amount: 50000}); // Amount in paisa (Rs. 500)
    }
</script>
Step 2 — Add Routes in Laravel
Define payment verification route in routes/web.php:

php
Copy
Edit
use App\Http\Controllers\PaymentController;

Route::post('/verify-khalti-payment', [PaymentController::class, 'verifyKhaltiPayment']);
Step 3 — Create Payment Controller
php
Copy
Edit
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function verifyKhaltiPayment(Request $request)
    {
        $token = $request->token;
        $amount = $request->amount;
        $secretKey = env('KHALTI_SECRET_KEY');

        $args = http_build_query([
            'token' => $token,
            'amount' => $amount
        ]);

        $url = "https://khalti.com/api/v2/payment/verify/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Key {$secretKey}"
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode == 200) {
            // Payment successful — update order status
            return response()->json(['success' => true, 'message' => 'Payment verified successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Payment verification failed'], $statusCode);
        }
    }
}
Step 4 — Configure Environment Variables
In your .env file, add:

env
Copy
Edit
KHALTI_PUBLIC_KEY=test_public_key_xxxxxxxxxxxxx
KHALTI_SECRET_KEY=test_secret_key_xxxxxxxxxxxxx
Step 5 — Test the Integration
Run your Laravel app (php artisan serve)

Visit the payment page.

Click Pay with Khalti → complete the test payment using Khalti sandbox credentials.

Check if the payment status is updated in your database.

4. Official Documentation & Sandbox
📄 Official Khalti Integration Docs:
https://docs.khalti.com/

🔗 Khalti Test Payment Keys:
https://khalti.com → Log in → Merchant Panel → Keys & Settings

5. Notes & Best Practices
Always verify payment on the backend before updating order status.

Amounts must be in paisa (Rs. 100 = 10000).

Never expose your secret key in front-end code.

Enable SSL (HTTPS) in production for security.

Switch from test_public_key & test_secret_key to production keys when going live.