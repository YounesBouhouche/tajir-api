<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $categories = $this->getCategories();
        $this->command->info('Seeding product types and categories...');
        DB::transaction(function () use ($categories) {
            foreach ($categories as $categoryData) {
                if (!isset($categoryData['product_types']) || !is_array($categoryData['product_types'])) {
                    continue;
                }
                $category = Category::create(
                    ['name' => $categoryData['name'], 'description' => $categoryData['description'] ?? null]
                );
                $this->command->info("Created category: $category->name");
                foreach ($categoryData['product_types'] as $productTypeData) {
                    $productType = ProductType::create([
                        'name' => $productTypeData['name'],
                        'description' => $productTypeData['description'] ?? null,
                        'category_id' => $category->id,
                        'attributes_schema' => $productTypeData['attributes_schema'] ?? null,
                    ]);
                    $this->command->info("Created product type: $productType->name");
                }
            }
        });

        $this->command->info('Product types seeding completed.');
    }

    public function getCategories(): array
    {
        $path = database_path('seeders/data/categories.json');
        if (!file_exists($path)) {
            return [];
        }
        $jsonContent = File::get($path);
        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Invalid JSON format: ' . json_last_error_msg());
            return [];
        }
        return $data['categories'] ?? [];
    }
}
