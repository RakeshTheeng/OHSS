<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixProviders extends Command
{
    protected $signature = 'providers:fix';
    protected $description = 'Fix provider availability status';

    public function handle()
    {
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('is_available', false)
                        ->get();
        
        $this->info('Found ' . $providers->count() . ' approved providers that are not available');
        
        foreach ($providers as $provider) {
            $provider->update(['is_available' => true]);
            $this->line("Updated {$provider->name} to be available");
        }
        
        $this->info('All approved providers are now available!');
        
        return 0;
    }
}
