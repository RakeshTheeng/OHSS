<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        // Get all settings grouped by category
        $settings = [
            'general' => [
                'site_name' => $this->getSetting('site_name', 'Online Household Service System'),
                'site_description' => $this->getSetting('site_description', 'Professional household services at your doorstep'),
                'site_logo' => $this->getSetting('site_logo', ''),
                'site_favicon' => $this->getSetting('site_favicon', ''),
                'contact_email' => $this->getSetting('contact_email', 'admin@ohss.com'),
                'contact_phone' => $this->getSetting('contact_phone', '+977-1-4444444'),
                'contact_address' => $this->getSetting('contact_address', 'Kathmandu, Nepal'),
            ],
            'business' => [
                'commission_rate' => $this->getSetting('commission_rate', '10'),
                'min_booking_amount' => $this->getSetting('min_booking_amount', '500'),
                'max_booking_amount' => $this->getSetting('max_booking_amount', '50000'),
                'booking_advance_hours' => $this->getSetting('booking_advance_hours', '2'),
                'cancellation_hours' => $this->getSetting('cancellation_hours', '24'),
                'auto_approve_providers' => $this->getSetting('auto_approve_providers', '0'),
            ],
            'payment' => [
                'enable_esewa' => $this->getSetting('enable_esewa', '1'),
                'esewa_merchant_id' => $this->getSetting('esewa_merchant_id', ''),
                'esewa_secret_key' => $this->getSetting('esewa_secret_key', ''),
                'enable_khalti' => $this->getSetting('enable_khalti', '0'),
                'khalti_public_key' => $this->getSetting('khalti_public_key', ''),
                'khalti_secret_key' => $this->getSetting('khalti_secret_key', ''),
                'enable_cash_payment' => $this->getSetting('enable_cash_payment', '1'),
            ],
            'notifications' => [
                'email_notifications' => $this->getSetting('email_notifications', '1'),
                'sms_notifications' => $this->getSetting('sms_notifications', '0'),
                'push_notifications' => $this->getSetting('push_notifications', '1'),
                'admin_email_alerts' => $this->getSetting('admin_email_alerts', '1'),
            ],
            'security' => [
                'require_email_verification' => $this->getSetting('require_email_verification', '1'),
                'require_phone_verification' => $this->getSetting('require_phone_verification', '0'),
                'max_login_attempts' => $this->getSetting('max_login_attempts', '5'),
                'session_timeout' => $this->getSetting('session_timeout', '120'),
                'enable_two_factor' => $this->getSetting('enable_two_factor', '0'),
            ],
            'maintenance' => [
                'maintenance_mode' => $this->getSetting('maintenance_mode', '0'),
                'maintenance_message' => $this->getSetting('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.'),
                'backup_frequency' => $this->getSetting('backup_frequency', 'daily'),
                'log_retention_days' => $this->getSetting('log_retention_days', '30'),
            ]
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'min_booking_amount' => 'required|numeric|min:0',
            'max_booking_amount' => 'required|numeric|min:0',
            'booking_advance_hours' => 'required|integer|min:1',
            'cancellation_hours' => 'required|integer|min:1',
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'session_timeout' => 'required|integer|min:30|max:480',
            'log_retention_days' => 'required|integer|min:1|max:365',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            $this->setSetting('site_logo', $logoPath);
        }

        if ($request->hasFile('site_favicon')) {
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            $this->setSetting('site_favicon', $faviconPath);
        }

        // Update all settings
        $settingsToUpdate = [
            'site_name', 'site_description', 'contact_email', 'contact_phone', 'contact_address',
            'commission_rate', 'min_booking_amount', 'max_booking_amount', 'booking_advance_hours',
            'cancellation_hours', 'esewa_merchant_id', 'esewa_secret_key', 'khalti_public_key',
            'khalti_secret_key', 'maintenance_message', 'max_login_attempts', 'session_timeout',
            'log_retention_days'
        ];

        foreach ($settingsToUpdate as $key) {
            if ($request->has($key)) {
                $this->setSetting($key, $request->get($key));
            }
        }

        // Handle boolean settings
        $booleanSettings = [
            'auto_approve_providers', 'enable_esewa', 'enable_khalti', 'enable_cash_payment',
            'email_notifications', 'sms_notifications', 'push_notifications', 'admin_email_alerts',
            'require_email_verification', 'require_phone_verification', 'enable_two_factor',
            'maintenance_mode'
        ];

        foreach ($booleanSettings as $key) {
            $this->setSetting($key, $request->has($key) ? '1' : '0');
        }

        // Handle backup frequency
        if ($request->has('backup_frequency')) {
            $this->setSetting('backup_frequency', $request->get('backup_frequency'));
        }

        // Clear settings cache
        Cache::forget('app_settings');

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Get a setting value
     */
    private function getSetting($key, $default = null)
    {
        $settings = Cache::remember('app_settings', 3600, function () {
            return Setting::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value
     */
    private function setSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache
        Cache::forget('app_settings');
    }
}
