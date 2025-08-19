<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plumbing',
                'description' => 'Professional plumbing services including pipe repair, installation, and maintenance',
                'icon' => 'fas fa-wrench',
                'sort_order' => 1,
            ],
            [
                'name' => 'Electrical',
                'description' => 'Electrical installation, repair, and maintenance services',
                'icon' => 'fas fa-bolt',
                'sort_order' => 2,
            ],
            [
                'name' => 'Cleaning',
                'description' => 'House cleaning, deep cleaning, and maintenance services',
                'icon' => 'fas fa-broom',
                'sort_order' => 3,
            ],
            [
                'name' => 'Carpentry',
                'description' => 'Wood work, furniture repair, and custom carpentry services',
                'icon' => 'fas fa-hammer',
                'sort_order' => 4,
            ],
            [
                'name' => 'Painting',
                'description' => 'Interior and exterior painting services',
                'icon' => 'fas fa-paint-roller',
                'sort_order' => 5,
            ],
            [
                'name' => 'Gardening',
                'description' => 'Garden maintenance, landscaping, and plant care services',
                'icon' => 'fas fa-seedling',
                'sort_order' => 6,
            ],
            [
                'name' => 'AC Repair',
                'description' => 'Air conditioning installation, repair, and maintenance',
                'icon' => 'fas fa-snowflake',
                'sort_order' => 7,
            ],
            [
                'name' => 'Appliance Repair',
                'description' => 'Home appliance repair and maintenance services',
                'icon' => 'fas fa-tools',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
