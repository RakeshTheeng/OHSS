<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckProviderDetails extends Command
{
    protected $signature = 'providers:details';
    protected $description = 'Check provider details and service categories';

    public function handle()
    {
        $providers = User::where('role', 'provider')
                        ->with('serviceCategories')
                        ->get();
        
        foreach ($providers as $provider) {
            $this->line("=== {$provider->name} ===");
            $this->line("Email: {$provider->email}");
            $this->line("Status: {$provider->provider_status}");
            $this->line("Available: " . ($provider->is_available ? 'Yes' : 'No'));
            $this->line("Hourly Rate: Rs. " . ($provider->hourly_rate ?? 'Not set'));
            $this->line("Experience: " . ($provider->experience_years ?? 'Not set') . " years");
            $this->line("Rating: " . ($provider->rating ?? 'No rating'));
            $this->line("Bio: " . ($provider->bio ?? 'Not set'));
            $this->line("Phone: " . ($provider->phone ?? 'Not set'));
            $this->line("Address: " . ($provider->address ?? 'Not set'));
            
            $categories = $provider->serviceCategories;
            if ($categories->count() > 0) {
                $this->line("Service Categories: " . $categories->pluck('name')->join(', '));
            } else {
                $this->error("No service categories assigned!");
            }
            $this->line('');
        }
        
        return 0;
    }
}
