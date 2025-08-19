<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestProviderListing extends Command
{
    protected $signature = 'providers:test-listing';
    protected $description = 'Test provider listing query';

    public function handle()
    {
        // This is the same query used in the Customer\ProviderController
        $providers = User::where('role', 'provider')
                        ->where('provider_status', 'approved')
                        ->where('is_available', true)
                        ->with(['serviceCategories', 'reviews'])
                        ->orderBy('rating', 'desc')
                        ->get();
        
        $this->info('Providers that will show in customer listing:');
        $this->line('');
        
        foreach ($providers as $provider) {
            $this->line("✓ {$provider->name}");
            $this->line("  Email: {$provider->email}");
            $this->line("  Rate: Rs. {$provider->hourly_rate}/hr");
            $this->line("  Experience: {$provider->experience_years} years");
            $this->line("  Categories: " . $provider->serviceCategories->pluck('name')->join(', '));
            $this->line('');
        }
        
        $this->info("Total providers visible: {$providers->count()}");
        
        if ($providers->count() === 3) {
            $this->info('✅ All 3 providers are now visible!');
        } else {
            $this->error('❌ Not all providers are visible');
        }
        
        return 0;
    }
}
