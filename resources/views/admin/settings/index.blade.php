@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog"></i> System Settings
        </h1>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-globe"></i> General Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="site_name">Site Name</label>
                            <input type="text" 
                                   class="form-control @error('site_name') is-invalid @enderror" 
                                   id="site_name" 
                                   name="site_name" 
                                   value="{{ old('site_name', $settings['general']['site_name']) }}" 
                                   required>
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_description">Site Description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" 
                                      id="site_description" 
                                      name="site_description" 
                                      rows="3">{{ old('site_description', $settings['general']['site_description']) }}</textarea>
                            @error('site_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_logo">Site Logo</label>
                            <input type="file" 
                                   class="form-control-file @error('site_logo') is-invalid @enderror" 
                                   id="site_logo" 
                                   name="site_logo" 
                                   accept="image/*">
                            @if($settings['general']['site_logo'])
                                <div class="mt-2">
                                    <img src="{{ Storage::url($settings['general']['site_logo']) }}" 
                                         alt="Current Logo" 
                                         style="max-height: 50px;">
                                </div>
                            @endif
                            @error('site_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_favicon">Site Favicon</label>
                            <input type="file" 
                                   class="form-control-file @error('site_favicon') is-invalid @enderror" 
                                   id="site_favicon" 
                                   name="site_favicon" 
                                   accept="image/*">
                            @if($settings['general']['site_favicon'])
                                <div class="mt-2">
                                    <img src="{{ Storage::url($settings['general']['site_favicon']) }}" 
                                         alt="Current Favicon" 
                                         style="max-height: 32px;">
                                </div>
                            @endif
                            @error('site_favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" 
                                   class="form-control @error('contact_email') is-invalid @enderror" 
                                   id="contact_email" 
                                   name="contact_email" 
                                   value="{{ old('contact_email', $settings['general']['contact_email']) }}" 
                                   required>
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input type="text" 
                                   class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" 
                                   name="contact_phone" 
                                   value="{{ old('contact_phone', $settings['general']['contact_phone']) }}">
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="contact_address">Contact Address</label>
                            <textarea class="form-control @error('contact_address') is-invalid @enderror" 
                                      id="contact_address" 
                                      name="contact_address" 
                                      rows="3">{{ old('contact_address', $settings['general']['contact_address']) }}</textarea>
                            @error('contact_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Business Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-business-time"></i> Business Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%)</label>
                            <input type="number" 
                                   class="form-control @error('commission_rate') is-invalid @enderror" 
                                   id="commission_rate" 
                                   name="commission_rate" 
                                   value="{{ old('commission_rate', $settings['business']['commission_rate']) }}" 
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                            @error('commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="min_booking_amount">Min Booking Amount (Rs.)</label>
                                    <input type="number" 
                                           class="form-control @error('min_booking_amount') is-invalid @enderror" 
                                           id="min_booking_amount" 
                                           name="min_booking_amount" 
                                           value="{{ old('min_booking_amount', $settings['business']['min_booking_amount']) }}" 
                                           min="0" 
                                           required>
                                    @error('min_booking_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_booking_amount">Max Booking Amount (Rs.)</label>
                                    <input type="number" 
                                           class="form-control @error('max_booking_amount') is-invalid @enderror" 
                                           id="max_booking_amount" 
                                           name="max_booking_amount" 
                                           value="{{ old('max_booking_amount', $settings['business']['max_booking_amount']) }}" 
                                           min="0" 
                                           required>
                                    @error('max_booking_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booking_advance_hours">Booking Advance Hours</label>
                                    <input type="number" 
                                           class="form-control @error('booking_advance_hours') is-invalid @enderror" 
                                           id="booking_advance_hours" 
                                           name="booking_advance_hours" 
                                           value="{{ old('booking_advance_hours', $settings['business']['booking_advance_hours']) }}" 
                                           min="1" 
                                           required>
                                    @error('booking_advance_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cancellation_hours">Cancellation Hours</label>
                                    <input type="number" 
                                           class="form-control @error('cancellation_hours') is-invalid @enderror" 
                                           id="cancellation_hours" 
                                           name="cancellation_hours" 
                                           value="{{ old('cancellation_hours', $settings['business']['cancellation_hours']) }}" 
                                           min="1" 
                                           required>
                                    @error('cancellation_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="auto_approve_providers" 
                                       name="auto_approve_providers" 
                                       {{ old('auto_approve_providers', $settings['business']['auto_approve_providers']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_approve_providers">
                                    Auto-approve new providers
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">
                            <i class="fas fa-shield-alt"></i> Security Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="require_email_verification" 
                                       name="require_email_verification" 
                                       {{ old('require_email_verification', $settings['security']['require_email_verification']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_email_verification">
                                    Require email verification
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="require_phone_verification" 
                                       name="require_phone_verification" 
                                       {{ old('require_phone_verification', $settings['security']['require_phone_verification']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_phone_verification">
                                    Require phone verification
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_login_attempts">Max Login Attempts</label>
                                    <input type="number" 
                                           class="form-control @error('max_login_attempts') is-invalid @enderror" 
                                           id="max_login_attempts" 
                                           name="max_login_attempts" 
                                           value="{{ old('max_login_attempts', $settings['security']['max_login_attempts']) }}" 
                                           min="1" 
                                           max="10" 
                                           required>
                                    @error('max_login_attempts')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="session_timeout">Session Timeout (minutes)</label>
                                    <input type="number" 
                                           class="form-control @error('session_timeout') is-invalid @enderror" 
                                           id="session_timeout" 
                                           name="session_timeout" 
                                           value="{{ old('session_timeout', $settings['security']['session_timeout']) }}" 
                                           min="30" 
                                           max="480" 
                                           required>
                                    @error('session_timeout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="enable_two_factor" 
                                       name="enable_two_factor" 
                                       {{ old('enable_two_factor', $settings['security']['enable_two_factor']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_two_factor">
                                    Enable two-factor authentication
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Settings -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-credit-card"></i> Payment Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="enable_esewa" 
                                       name="enable_esewa" 
                                       {{ old('enable_esewa', $settings['payment']['enable_esewa']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_esewa">
                                    Enable eSewa Payment
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="esewa_merchant_id">eSewa Merchant ID</label>
                            <input type="text" 
                                   class="form-control @error('esewa_merchant_id') is-invalid @enderror" 
                                   id="esewa_merchant_id" 
                                   name="esewa_merchant_id" 
                                   value="{{ old('esewa_merchant_id', $settings['payment']['esewa_merchant_id']) }}">
                            @error('esewa_merchant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="esewa_secret_key">eSewa Secret Key</label>
                            <input type="password" 
                                   class="form-control @error('esewa_secret_key') is-invalid @enderror" 
                                   id="esewa_secret_key" 
                                   name="esewa_secret_key" 
                                   value="{{ old('esewa_secret_key', $settings['payment']['esewa_secret_key']) }}">
                            @error('esewa_secret_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="enable_khalti" 
                                       name="enable_khalti" 
                                       {{ old('enable_khalti', $settings['payment']['enable_khalti']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_khalti">
                                    Enable Khalti Payment
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="khalti_public_key">Khalti Public Key</label>
                            <input type="text" 
                                   class="form-control @error('khalti_public_key') is-invalid @enderror" 
                                   id="khalti_public_key" 
                                   name="khalti_public_key" 
                                   value="{{ old('khalti_public_key', $settings['payment']['khalti_public_key']) }}">
                            @error('khalti_public_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="khalti_secret_key">Khalti Secret Key</label>
                            <input type="password" 
                                   class="form-control @error('khalti_secret_key') is-invalid @enderror" 
                                   id="khalti_secret_key" 
                                   name="khalti_secret_key" 
                                   value="{{ old('khalti_secret_key', $settings['payment']['khalti_secret_key']) }}">
                            @error('khalti_secret_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="enable_cash_payment" 
                                       name="enable_cash_payment" 
                                       {{ old('enable_cash_payment', $settings['payment']['enable_cash_payment']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_cash_payment">
                                    Enable Cash Payment
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-bell"></i> Notification Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="email_notifications" 
                                       name="email_notifications" 
                                       {{ old('email_notifications', $settings['notifications']['email_notifications']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Enable email notifications
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="sms_notifications" 
                                       name="sms_notifications" 
                                       {{ old('sms_notifications', $settings['notifications']['sms_notifications']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_notifications">
                                    Enable SMS notifications
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="push_notifications" 
                                       name="push_notifications" 
                                       {{ old('push_notifications', $settings['notifications']['push_notifications']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_notifications">
                                    Enable push notifications
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="admin_email_alerts" 
                                       name="admin_email_alerts" 
                                       {{ old('admin_email_alerts', $settings['notifications']['admin_email_alerts']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="admin_email_alerts">
                                    Enable admin email alerts
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maintenance Settings -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">
                            <i class="fas fa-tools"></i> Maintenance Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="maintenance_mode" 
                                       name="maintenance_mode" 
                                       {{ old('maintenance_mode', $settings['maintenance']['maintenance_mode']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="maintenance_mode">
                                    Enable maintenance mode
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="maintenance_message">Maintenance Message</label>
                            <textarea class="form-control @error('maintenance_message') is-invalid @enderror" 
                                      id="maintenance_message" 
                                      name="maintenance_message" 
                                      rows="3">{{ old('maintenance_message', $settings['maintenance']['maintenance_message']) }}</textarea>
                            @error('maintenance_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="backup_frequency">Backup Frequency</label>
                            <select class="form-control @error('backup_frequency') is-invalid @enderror" 
                                    id="backup_frequency" 
                                    name="backup_frequency">
                                <option value="daily" {{ old('backup_frequency', $settings['maintenance']['backup_frequency']) === 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('backup_frequency', $settings['maintenance']['backup_frequency']) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('backup_frequency', $settings['maintenance']['backup_frequency']) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('backup_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="log_retention_days">Log Retention Days</label>
                            <input type="number" 
                                   class="form-control @error('log_retention_days') is-invalid @enderror" 
                                   id="log_retention_days" 
                                   name="log_retention_days" 
                                   value="{{ old('log_retention_days', $settings['maintenance']['log_retention_days']) }}" 
                                   min="1" 
                                   max="365" 
                                   required>
                            @error('log_retention_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg ml-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
