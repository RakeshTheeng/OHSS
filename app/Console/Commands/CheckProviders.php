<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckProviders extends Command
{
    protected $signature = 'providers:check';
    protected $description = 'Check and display provider statuses';

    public function handle()
    {
        $providers = User::where('role', 'provider')->get();
        
        $this->info('Total Providers: ' . $providers->count());
        $this->line('');
        
        foreach ($providers as $provider) {
            $this->line("ID: {$provider->id}");
            $this->line("Name: {$provider->name}");
            $this->line("Email: {$provider->email}");
            $this->line("Status: {$provider->provider_status}");
            $this->line("Available: " . ($provider->is_available ? 'Yes' : 'No'));
            $this->line("Created: {$provider->created_at}");
            $this->line('---');
        }
        
        // Count by status
        $approved = $providers->where('provider_status', 'approved')->count();
        $pending = $providers->where('provider_status', 'pending')->count();
        $rejected = $providers->where('provider_status', 'rejected')->count();
        $available = $providers->where('is_available', true)->count();
        
        $this->info("Approved: {$approved}");
        $this->info("Pending: {$pending}");
        $this->info("Rejected: {$rejected}");
        $this->info("Available: {$available}");
        
        return 0;
    }
}
