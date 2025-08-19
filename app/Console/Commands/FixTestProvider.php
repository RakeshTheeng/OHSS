<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ServiceCategory;

class FixTestProvider extends Command
{
    protected $signature = 'providers:fix-test';
    protected $description = 'Fix test provider details';

    public function handle()
    {
        $provider = User::where('email', 'testprovider@test.com')->first();
        
        if (!$provider) {
            $this->error('Test provider not found!');
            return 1;
        }
        
        // Update basic information
        $provider->update([
            'hourly_rate' => 400.00,
            'experience_years' => 3,
            'bio' => 'Professional electrical services including installation, repair, and maintenance of electrical systems.',
        ]);
        
        // Assign electrical service category
        $electricalCategory = ServiceCategory::where('slug', 'electrical')->first();
        if ($electricalCategory) {
            $provider->serviceCategories()->attach($electricalCategory->id, [
                'price' => 400.00,
                'description' => 'Electrical installation and repair services',
                'is_active' => true
            ]);
            $this->info('Assigned Electrical category to test provider');
        }
        
        $this->info('Test provider details updated successfully!');
        
        return 0;
    }
}
