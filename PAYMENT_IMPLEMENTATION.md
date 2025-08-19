# Payment System Implementation

## Overview
This document describes the implementation of the payment system for the OHSS (Online Household Services System) platform, focusing on eSewa integration and cash payment handling.

## Payment Methods

### 1. eSewa Digital Wallet
- **Description**: Digital wallet payment through eSewa's secure payment gateway
- **Implementation**: Full integration with eSewa ePay API v2
- **Booking Flow**: Payment must be completed BEFORE booking confirmation
- **Status**: ✅ Implemented

### 2. Cash on Hand
- **Description**: Cash payment made directly to the service provider upon service completion
- **Implementation**: Manual payment tracking through the platform
- **Booking Flow**: Booking is confirmed IMMEDIATELY without payment processing
- **Status**: ✅ Implemented

## eSewa Integration Details

### Configuration
The eSewa integration uses the following configuration (set in `.env`):

```env
ESEWA_PRODUCT_CODE=EPAYTEST
ESEWA_SECRET_KEY="8gBm/:&EnhH.1/q"
ESEWA_PAYMENT_URL=https://rc-epay.esewa.com.np/api/epay/main/v2/form
ESEWA_STATUS_CHECK_URL=https://rc.esewa.com.np/api/epay/transaction/status/
```

### Payment Flow

#### eSewa Payment Flow
1. **Booking Creation**: Customer creates a booking and selects eSewa as payment method
2. **Booking Status**: Booking is set to `pending_payment` (NOT confirmed yet)
3. **Payment Initiation**: Customer is redirected to eSewa payment page with merchant details
4. **eSewa Processing**: Customer completes payment on eSewa's secure platform
5. **Payment Receipt**: Customer receives payment receipt from eSewa
6. **Callback Handling**: eSewa redirects back with payment status
7. **Verification**: System verifies payment signature and confirms booking
8. **Booking Confirmation**: Booking status changes to `confirmed`, provider is notified

#### Cash on Hand Payment Flow
1. **Booking Creation**: Customer creates a booking and selects Cash on Hand
2. **Immediate Confirmation**: Booking is immediately set to `confirmed` status
3. **Provider Notification**: Provider is notified about the confirmed booking
4. **Payment Tracking**: Payment remains `pending` until service completion

### Security Features
- **HMAC SHA256 Signature**: All requests are signed using HMAC SHA256
- **SSL Encryption**: All communications use 256-bit SSL encryption
- **Transaction Verification**: Payment responses are verified before processing

## Current Implementation Notes

### Payment Recipient
**Current Setup**: All eSewa payments go to the platform's merchant account (centralized)

**Alternative Approach**: Direct provider payments
- Each provider would need their own eSewa merchant account
- Payments would go directly to provider's eSewa account
- More complex setup but eliminates need for platform to handle payouts

### Provider Phone Numbers
Provider phone numbers are stored in the `users` table during registration and can be accessed via:
```php
$provider = $booking->provider;
$providerPhone = $provider->phone;
```

## Files Modified/Created

### Controllers
- `app/Http/Controllers/Customer/PaymentController.php` - eSewa payment processing
- `app/Http/Controllers/Customer/BookingController.php` - Updated to handle eSewa redirects
- `app/Http/Controllers/BookingController.php` - Updated validation rules

### Views
- `resources/views/customer/bookings/create.blade.php` - Updated payment options
- `resources/views/customer/payments/esewa.blade.php` - eSewa payment page
- `resources/views/customer/payments/index.blade.php` - Payment history
- `resources/views/customer/payments/show.blade.php` - Payment details

### Configuration
- `config/services.php` - Added eSewa configuration
- `routes/web.php` - Added eSewa payment routes
- `.env.example` - Added eSewa environment variables

## Testing

### Test Credentials (UAT Environment)
- **eSewa ID**: 9806800001/2/3/4/5
- **Password**: Nepal@123
- **MPIN**: 1122
- **Token**: 123456

### Test Flow
1. Create a service request as a customer
2. Book the service and select eSewa payment
3. Complete payment using test credentials
4. Verify payment status in booking details

## Production Setup

### Requirements
1. **eSewa Merchant Account**: Register for a merchant account with eSewa
2. **Production Credentials**: Obtain production product code and secret key
3. **Environment Configuration**: Update `.env` with production URLs and credentials

### Production URLs
- **Payment URL**: `https://epay.esewa.com.np/api/epay/main/v2/form`
- **Status Check URL**: `https://epay.esewa.com.np/api/epay/transaction/status/`
- **Merchant Portal**: `https://merchant.esewa.com.np`

## Future Enhancements

### 1. Provider-Specific Payments
To implement direct payments to providers:
1. Add eSewa merchant fields to provider registration
2. Modify payment controller to use provider's merchant credentials
3. Update payment flow to create separate transactions per provider

### 2. Payment Status Monitoring
- Implement automatic status checking for pending payments
- Add webhook support for real-time payment updates
- Create payment reconciliation reports

### 3. Refund System
- Implement refund processing through eSewa API
- Add refund request workflow for customers
- Create admin interface for refund management

## Support and Troubleshooting

### Common Issues
1. **Signature Mismatch**: Ensure secret key matches eSewa configuration
2. **Invalid Transaction**: Check transaction UUID format and uniqueness
3. **Payment Timeout**: Implement status check API for delayed responses

### Logs and Monitoring
- Payment attempts are logged in Laravel logs
- eSewa responses are stored in `payments.gateway_response` field
- Monitor payment success rates through admin dashboard
