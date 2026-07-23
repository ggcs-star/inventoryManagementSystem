<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Models\Warehouse;
use App\Models\Platform;
use App\Models\PlatformProduct;
use App\Models\PlatformPricing;
use App\Models\Supplier;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Base Entities (Warehouse, Supplier, Platform)
        $warehouse = Warehouse::create([
            'name' => 'Blinkit Main Hub',
            'code' => 'WH-BLK-01',
            'address' => 'SG Highway',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
            'pincode' => '380054',
            'status' => 'active',
        ]);

        $supplier = Supplier::create([
            'name' => 'Mega Grocery Distributors',
            'email' => 'supply@megagrocery.com',
            'phone' => '9999999999',
            'status' => 'active',
        ]);

        $platform = Platform::create([
            'name' => 'own_website',
            'display_name' => 'Blinkit Clone App',
            'status' => 'active',
            'is_enabled' => true,
        ]);

        // 2. Setup Global Variant (Size/Weight/Volume)
        $globalVariant = Variant::create([
            'name' => 'Size/Weight',
            'input_type' => 'dimension',
            'has_dimensions' => false,
            'is_active' => true,
        ]);

        // 3. Define Categories Structure (Expanded)
        $categoriesData = [
            'Fruits & Vegetables' => ['Fresh Fruits', 'Fresh Vegetables', 'Exotic Fruits & Veggies'],
            'Groceries & Staples' => ['Atta & Rice', 'Dal & Pulses', 'Oil & Ghee', 'Masala & Spices', 'Dry Fruits & Nuts', 'Sugar & Jaggery'],
            'Snacks & Branded Foods' => ['Biscuits & Cookies', 'Chips & Namkeen', 'Noodles & Pasta', 'Chocolates & Candies', 'Breakfast Cereals', 'Spreads & Sauces'],
            'Dairy & Bakery' => ['Milk & Curd', 'Bread & Butter', 'Cheese & Paneer', 'Eggs & Meats'],
            'Beverages' => ['Cold Drinks', 'Tea & Coffee', 'Juices & Syrups', 'Water & Soda'],
            'Personal Care' => ['Bath & Body', 'Oral Care', 'Hair Care', 'Skin Care', 'Men\'s Grooming'],
            'Cleaning Essentials' => ['Detergents', 'Dishwash', 'Floor & Toilet Cleaners', 'Pooja Needs', 'Repellents & Fresheners'],
            'Baby Care' => ['Diapers & Wipes', 'Baby Food', 'Baby Bath & Skin'],
            'Pet Care' => ['Dog Food', 'Cat Food', 'Pet Accessories']
        ];

        $categoryMap = []; // To store category IDs by name

        // Loop and create categories
        $sortOrder = 1;
        foreach ($categoriesData as $parentName => $childCategories) {
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'status' => 'active',
                'sort_order' => $sortOrder++,
            ]);

            foreach ($childCategories as $childName) {
                $child = Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parent->id,
                    'status' => 'active',
                    'sort_order' => $sortOrder++,
                ]);
                $categoryMap[$childName] = $child->id; // Save child ID for product linking
            }
        }

        // 4. Define Products and their Variants (Super Massive Data)
        $productsData = [
            [
                'name' => 'Fresh Potato (Aloo)',
                'category' => 'Fresh Vegetables',
                'brand' => 'Fresh Farm',
                'image' => 'products/potato.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 15, 'sell_price' => 25, 'mrp' => 30, 'discount' => 5],
                    ['value' => '5 Kg', 'pur_price' => 70, 'sell_price' => 115, 'mrp' => 140, 'discount' => 25],
                ]
            ],
            [
                'name' => 'Fresh Onion (Pyaj)',
                'category' => 'Fresh Vegetables',
                'brand' => 'Fresh Farm',
                'image' => 'products/onion.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 20, 'sell_price' => 28, 'mrp' => 35, 'discount' => 7],
                    ['value' => '5 Kg', 'pur_price' => 95, 'sell_price' => 130, 'mrp' => 170, 'discount' => 40],
                ]
            ],
            [
                'name' => 'Fresh Tomato (Tamatar)',
                'category' => 'Fresh Vegetables',
                'brand' => 'Fresh Farm',
                'image' => 'products/tomato.jpg',
                'variants' => [
                    ['value' => '500 g', 'pur_price' => 15, 'sell_price' => 22, 'mrp' => 30, 'discount' => 8],
                    ['value' => '1 Kg', 'pur_price' => 28, 'sell_price' => 40, 'mrp' => 55, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Fresh Garlic (Lahsun)',
                'category' => 'Fresh Vegetables',
                'brand' => 'Fresh Farm',
                'image' => 'products/garlic.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 35, 'sell_price' => 50, 'mrp' => 60, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Coriander Leaves (Dhania)',
                'category' => 'Fresh Vegetables',
                'brand' => 'Fresh Farm',
                'image' => 'products/coriander.jpg',
                'variants' => [
                    ['value' => '100 g', 'pur_price' => 8, 'sell_price' => 15, 'mrp' => 20, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Robusta Banana (Kela)',
                'category' => 'Fresh Fruits',
                'brand' => 'Fresh Farm',
                'image' => 'products/banana.jpg',
                'variants' => [
                    ['value' => '6 Pcs', 'pur_price' => 30, 'sell_price' => 42, 'mrp' => 50, 'discount' => 8],
                    ['value' => '12 Pcs', 'pur_price' => 55, 'sell_price' => 75, 'mrp' => 90, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Apple Royal Gala',
                'category' => 'Fresh Fruits',
                'brand' => 'Fresh Farm',
                'image' => 'products/apple.jpg',
                'variants' => [
                    ['value' => '4 Pcs', 'pur_price' => 80, 'sell_price' => 110, 'mrp' => 130, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Broccoli',
                'category' => 'Exotic Fruits & Veggies',
                'brand' => 'Fresh Farm',
                'image' => 'products/broccoli.jpg',
                'variants' => [
                    ['value' => '1 Pc (Approx 250g)', 'pur_price' => 45, 'sell_price' => 65, 'mrp' => 80, 'discount' => 15],
                ]
            ],

            // --- DAIRY & BAKERY ---
            [
                'name' => 'Amul Taaza Toned Milk',
                'category' => 'Milk & Curd',
                'brand' => 'Amul',
                'image' => 'products/amul_milk.jpg',
                'variants' => [
                    ['value' => '500 ml', 'pur_price' => 24, 'sell_price' => 27, 'mrp' => 27, 'discount' => 0],
                    ['value' => '1 L', 'pur_price' => 48, 'sell_price' => 54, 'mrp' => 54, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Mother Dairy Classic Curd',
                'category' => 'Milk & Curd',
                'brand' => 'Mother Dairy',
                'image' => 'products/mother_dairy_curd.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 18, 'sell_price' => 22, 'mrp' => 22, 'discount' => 0],
                    ['value' => '400 g', 'pur_price' => 35, 'sell_price' => 40, 'mrp' => 42, 'discount' => 2],
                ]
            ],
            [
                'name' => 'Amul Masti Spiced Buttermilk',
                'category' => 'Milk & Curd',
                'brand' => 'Amul',
                'image' => 'products/amul_buttermilk.jpg',
                'variants' => [
                    ['value' => '200 ml', 'pur_price' => 12, 'sell_price' => 15, 'mrp' => 15, 'discount' => 0],
                    ['value' => '1 L', 'pur_price' => 45, 'sell_price' => 50, 'mrp' => 50, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Amul Pasteurised Butter',
                'category' => 'Bread & Butter',
                'brand' => 'Amul',
                'image' => 'products/amul_butter.jpg',
                'variants' => [
                    ['value' => '100 g', 'pur_price' => 50, 'sell_price' => 58, 'mrp' => 60, 'discount' => 2],
                    ['value' => '500 g', 'pur_price' => 250, 'sell_price' => 285, 'mrp' => 295, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Harvest Gold White Bread',
                'category' => 'Bread & Butter',
                'brand' => 'Harvest Gold',
                'image' => 'products/white_bread.jpg',
                'variants' => [
                    ['value' => '400 g', 'pur_price' => 35, 'sell_price' => 40, 'mrp' => 40, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Britannia 100% Whole Wheat Bread',
                'category' => 'Bread & Butter',
                'brand' => 'Britannia',
                'image' => 'products/brown_bread.jpg',
                'variants' => [
                    ['value' => '400 g', 'pur_price' => 45, 'sell_price' => 50, 'mrp' => 50, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Milky Mist Paneer',
                'category' => 'Cheese & Paneer',
                'brand' => 'Milky Mist',
                'image' => 'products/milky_mist_paneer.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 80, 'sell_price' => 95, 'mrp' => 105, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Amul Cheese Slices',
                'category' => 'Cheese & Paneer',
                'brand' => 'Amul',
                'image' => 'products/amul_cheese.jpg',
                'variants' => [
                    ['value' => '200 g (10 Slices)', 'pur_price' => 115, 'sell_price' => 135, 'mrp' => 140, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Farm Fresh White Eggs',
                'category' => 'Eggs & Meats',
                'brand' => 'Farm Fresh',
                'image' => 'products/eggs.jpg',
                'variants' => [
                    ['value' => '6 Pcs', 'pur_price' => 38, 'sell_price' => 48, 'mrp' => 55, 'discount' => 7],
                    ['value' => '30 Pcs', 'pur_price' => 180, 'sell_price' => 220, 'mrp' => 250, 'discount' => 30],
                ]
            ],

            // --- GROCERIES & STAPLES ---
            [
                'name' => 'Aashirvaad Shudh Chakki Atta',
                'category' => 'Atta & Rice',
                'brand' => 'Aashirvaad',
                'image' => 'products/aashirvaad_atta.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 45, 'sell_price' => 55, 'mrp' => 60, 'discount' => 5],
                    ['value' => '5 Kg', 'pur_price' => 210, 'sell_price' => 240, 'mrp' => 270, 'discount' => 30],
                    ['value' => '10 Kg', 'pur_price' => 400, 'sell_price' => 460, 'mrp' => 520, 'discount' => 60],
                ]
            ],
            [
                'name' => 'India Gate Basmati Rice',
                'category' => 'Atta & Rice',
                'brand' => 'India Gate',
                'image' => 'products/india_gate.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 120, 'sell_price' => 145, 'mrp' => 160, 'discount' => 15],
                    ['value' => '5 Kg', 'pur_price' => 580, 'sell_price' => 690, 'mrp' => 750, 'discount' => 60],
                ]
            ],
            [
                'name' => 'Daawat Rozana Sona Masoori Rice',
                'category' => 'Atta & Rice',
                'brand' => 'Daawat',
                'image' => 'products/sona_masoori.jpg',
                'variants' => [
                    ['value' => '5 Kg', 'pur_price' => 320, 'sell_price' => 380, 'mrp' => 450, 'discount' => 70],
                ]
            ],
            [
                'name' => 'Tata Sampann Unpolished Toor Dal',
                'category' => 'Dal & Pulses',
                'brand' => 'Tata Sampann',
                'image' => 'products/tata_toor_dal.jpg',
                'variants' => [
                    ['value' => '500 g', 'pur_price' => 75, 'sell_price' => 90, 'mrp' => 110, 'discount' => 20],
                    ['value' => '1 Kg', 'pur_price' => 145, 'sell_price' => 175, 'mrp' => 210, 'discount' => 35],
                ]
            ],
            [
                'name' => 'Rajdhani Chana Dal',
                'category' => 'Dal & Pulses',
                'brand' => 'Rajdhani',
                'image' => 'products/chana_dal.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 85, 'sell_price' => 105, 'mrp' => 130, 'discount' => 25],
                ]
            ],
            [
                'name' => 'Fortune Sunlite Sunflower Oil',
                'category' => 'Oil & Ghee',
                'brand' => 'Fortune',
                'image' => 'products/fortune_oil.jpg',
                'variants' => [
                    ['value' => '1 L', 'pur_price' => 130, 'sell_price' => 145, 'mrp' => 165, 'discount' => 20],
                    ['value' => '5 L', 'pur_price' => 640, 'sell_price' => 710, 'mrp' => 800, 'discount' => 90],
                ]
            ],
            [
                'name' => 'Amul Pure Ghee',
                'category' => 'Oil & Ghee',
                'brand' => 'Amul',
                'image' => 'products/amul_ghee.jpg',
                'variants' => [
                    ['value' => '500 ml', 'pur_price' => 270, 'sell_price' => 310, 'mrp' => 320, 'discount' => 10],
                    ['value' => '1 L', 'pur_price' => 530, 'sell_price' => 610, 'mrp' => 630, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Tata Salt Vacuum Evaporated',
                'category' => 'Masala & Spices',
                'brand' => 'Tata',
                'image' => 'products/tata_salt.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 18, 'sell_price' => 25, 'mrp' => 28, 'discount' => 3],
                ]
            ],
            [
                'name' => 'Everest Garam Masala',
                'category' => 'Masala & Spices',
                'brand' => 'Everest',
                'image' => 'products/everest_garam_masala.jpg',
                'variants' => [
                    ['value' => '50 g', 'pur_price' => 32, 'sell_price' => 40, 'mrp' => 44, 'discount' => 4],
                    ['value' => '100 g', 'pur_price' => 60, 'sell_price' => 78, 'mrp' => 85, 'discount' => 7],
                ]
            ],
            [
                'name' => 'MDH Haldi Powder (Turmeric)',
                'category' => 'Masala & Spices',
                'brand' => 'MDH',
                'image' => 'products/mdh_haldi.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 45, 'sell_price' => 60, 'mrp' => 65, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Happilo Premium Almonds',
                'category' => 'Dry Fruits & Nuts',
                'brand' => 'Happilo',
                'image' => 'products/happilo_almonds.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 190, 'sell_price' => 260, 'mrp' => 310, 'discount' => 50],
                    ['value' => '500 g', 'pur_price' => 450, 'sell_price' => 620, 'mrp' => 750, 'discount' => 130],
                ]
            ],
            [
                'name' => 'Madhur Pure & Hygienic Sugar',
                'category' => 'Sugar & Jaggery',
                'brand' => 'Madhur',
                'image' => 'products/sugar.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 45, 'sell_price' => 55, 'mrp' => 60, 'discount' => 5],
                    ['value' => '5 Kg', 'pur_price' => 220, 'sell_price' => 260, 'mrp' => 280, 'discount' => 20],
                ]
            ],

            // --- SNACKS & BRANDED FOODS ---
            [
                'name' => 'Maggi 2-Minute Noodles',
                'category' => 'Noodles & Pasta',
                'brand' => 'Nestle',
                'image' => 'products/maggi.jpg',
                'variants' => [
                    ['value' => '70 g (1 Pack)', 'pur_price' => 12, 'sell_price' => 14, 'mrp' => 14, 'discount' => 0],
                    ['value' => '280 g (4 Pack)', 'pur_price' => 48, 'sell_price' => 55, 'mrp' => 56, 'discount' => 1],
                ]
            ],
            [
                'name' => 'YiPPee! Magic Masala Noodles',
                'category' => 'Noodles & Pasta',
                'brand' => 'Sunfeast',
                'image' => 'products/yippee.jpg',
                'variants' => [
                    ['value' => '240 g', 'pur_price' => 40, 'sell_price' => 48, 'mrp' => 50, 'discount' => 2],
                ]
            ],
            [
                'name' => 'Lay\'s India\'s Magic Masala',
                'category' => 'Chips & Namkeen',
                'brand' => 'Lays',
                'image' => 'products/lays_blue.jpg',
                'variants' => [
                    ['value' => '50 g', 'pur_price' => 16, 'sell_price' => 20, 'mrp' => 20, 'discount' => 0],
                    ['value' => '90 g', 'pur_price' => 32, 'sell_price' => 40, 'mrp' => 40, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Kurkure Masala Munch',
                'category' => 'Chips & Namkeen',
                'brand' => 'Kurkure',
                'image' => 'products/kurkure.jpg',
                'variants' => [
                    ['value' => '90 g', 'pur_price' => 16, 'sell_price' => 20, 'mrp' => 20, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Haldiram\'s Aloo Bhujia',
                'category' => 'Chips & Namkeen',
                'brand' => 'Haldiram',
                'image' => 'products/haldiram_bhujia.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 40, 'sell_price' => 52, 'mrp' => 55, 'discount' => 3],
                    ['value' => '400 g', 'pur_price' => 80, 'sell_price' => 100, 'mrp' => 110, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Haldiram\'s Moong Dal',
                'category' => 'Chips & Namkeen',
                'brand' => 'Haldiram',
                'image' => 'products/moong_dal.jpg',
                'variants' => [
                    ['value' => '200 g', 'pur_price' => 45, 'sell_price' => 55, 'mrp' => 60, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Parle-G Gold Biscuits',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Parle',
                'image' => 'products/parleg.jpg',
                'variants' => [
                    ['value' => '100 g', 'pur_price' => 8, 'sell_price' => 10, 'mrp' => 10, 'discount' => 0],
                    ['value' => '1 Kg', 'pur_price' => 80, 'sell_price' => 95, 'mrp' => 100, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Oreo Vanilla Creme Biscuit',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Cadbury',
                'image' => 'products/oreo.jpg',
                'variants' => [
                    ['value' => '120 g', 'pur_price' => 25, 'sell_price' => 35, 'mrp' => 40, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Cadbury Dairy Milk Silk',
                'category' => 'Chocolates & Candies',
                'brand' => 'Cadbury',
                'image' => 'products/dairy_milk_silk.jpg',
                'variants' => [
                    ['value' => '60 g', 'pur_price' => 60, 'sell_price' => 80, 'mrp' => 85, 'discount' => 5],
                    ['value' => '150 g', 'pur_price' => 130, 'sell_price' => 170, 'mrp' => 180, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Kinder Joy Chocolate (For Boys/Girls)',
                'category' => 'Chocolates & Candies',
                'brand' => 'Kinder',
                'image' => 'products/kinder_joy.jpg',
                'variants' => [
                    ['value' => '20 g', 'pur_price' => 35, 'sell_price' => 45, 'mrp' => 45, 'discount' => 0],
                ]
            ],
            [
                'name' => 'Kellogg\'s Corn Flakes Original',
                'category' => 'Breakfast Cereals',
                'brand' => 'Kelloggs',
                'image' => 'products/corn_flakes.jpg',
                'variants' => [
                    ['value' => '475 g', 'pur_price' => 160, 'sell_price' => 195, 'mrp' => 210, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Kissan Fresh Tomato Ketchup',
                'category' => 'Spreads & Sauces',
                'brand' => 'Kissan',
                'image' => 'products/kissan_ketchup.jpg',
                'variants' => [
                    ['value' => '950 g', 'pur_price' => 110, 'sell_price' => 135, 'mrp' => 155, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Pintola Peanut Butter (Crunchy)',
                'category' => 'Spreads & Sauces',
                'brand' => 'Pintola',
                'image' => 'products/peanut_butter.jpg',
                'variants' => [
                    ['value' => '350 g', 'pur_price' => 140, 'sell_price' => 175, 'mrp' => 199, 'discount' => 24],
                ]
            ],

            // --- BEVERAGES ---
            [
                'name' => 'Coca-Cola Soft Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Coca-Cola',
                'image' => 'products/coke.jpg',
                'variants' => [
                    ['value' => '250 ml (Can)', 'pur_price' => 30, 'sell_price' => 40, 'mrp' => 40, 'discount' => 0],
                    ['value' => '1.5 L', 'pur_price' => 70, 'sell_price' => 85, 'mrp' => 95, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Sprite Lemon-Lime Soft Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Sprite',
                'image' => 'products/sprite.jpg',
                'variants' => [
                    ['value' => '750 ml', 'pur_price' => 35, 'sell_price' => 40, 'mrp' => 45, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Thums Up Soft Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Thums Up',
                'image' => 'products/thums_up.jpg',
                'variants' => [
                    ['value' => '1.25 L', 'pur_price' => 55, 'sell_price' => 65, 'mrp' => 70, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Red Bull Energy Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Red Bull',
                'image' => 'products/red_bull.jpg',
                'variants' => [
                    ['value' => '250 ml', 'pur_price' => 90, 'sell_price' => 115, 'mrp' => 125, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Taj Mahal Tea',
                'category' => 'Tea & Coffee',
                'brand' => 'Brooke Bond',
                'image' => 'products/tajmahal.jpg',
                'variants' => [
                    ['value' => '250 g', 'pur_price' => 150, 'sell_price' => 175, 'mrp' => 190, 'discount' => 15],
                    ['value' => '500 g', 'pur_price' => 290, 'sell_price' => 340, 'mrp' => 370, 'discount' => 30],
                ]
            ],
            [
                'name' => 'Lipton Honey Lemon Green Tea Bags',
                'category' => 'Tea & Coffee',
                'brand' => 'Lipton',
                'image' => 'products/green_tea.jpg',
                'variants' => [
                    ['value' => '25 Bags', 'pur_price' => 120, 'sell_price' => 150, 'mrp' => 170, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Nescafe Classic Instant Coffee',
                'category' => 'Tea & Coffee',
                'brand' => 'Nescafe',
                'image' => 'products/nescafe.jpg',
                'variants' => [
                    ['value' => '50 g', 'pur_price' => 120, 'sell_price' => 145, 'mrp' => 160, 'discount' => 15],
                    ['value' => '100 g', 'pur_price' => 230, 'sell_price' => 280, 'mrp' => 310, 'discount' => 30],
                ]
            ],
            [
                'name' => 'Frooti Mango Drink',
                'category' => 'Juices & Syrups',
                'brand' => 'Frooti',
                'image' => 'products/frooti.jpg',
                'variants' => [
                    ['value' => '1.2 L', 'pur_price' => 55, 'sell_price' => 65, 'mrp' => 75, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Real Mixed Fruit Juice',
                'category' => 'Juices & Syrups',
                'brand' => 'Real',
                'image' => 'products/real_mixed_fruit.jpg',
                'variants' => [
                    ['value' => '1 L', 'pur_price' => 90, 'sell_price' => 110, 'mrp' => 125, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Kinley Mineral Water',
                'category' => 'Water & Soda',
                'brand' => 'Kinley',
                'image' => 'products/kinley_water.jpg',
                'variants' => [
                    ['value' => '1 L', 'pur_price' => 14, 'sell_price' => 20, 'mrp' => 20, 'discount' => 0],
                    ['value' => '2 L', 'pur_price' => 25, 'sell_price' => 35, 'mrp' => 35, 'discount' => 0],
                ]
            ],

            // --- PERSONAL CARE ---
            [
                'name' => 'Dove Cream Beauty Bathing Bar',
                'category' => 'Bath & Body',
                'brand' => 'Dove',
                'image' => 'products/dove_soap.jpg',
                'variants' => [
                    ['value' => '100 g (1 Pc)', 'pur_price' => 40, 'sell_price' => 55, 'mrp' => 60, 'discount' => 5],
                    ['value' => '300 g (3 Pcs)', 'pur_price' => 120, 'sell_price' => 155, 'mrp' => 170, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Dettol Original Liquid Handwash',
                'category' => 'Bath & Body',
                'brand' => 'Dettol',
                'image' => 'products/dettol_handwash.jpg',
                'variants' => [
                    ['value' => '750 ml (Refill)', 'pur_price' => 90, 'sell_price' => 115, 'mrp' => 135, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Colgate Strong Teeth Toothpaste',
                'category' => 'Oral Care',
                'brand' => 'Colgate',
                'image' => 'products/colgate.jpg',
                'variants' => [
                    ['value' => '100 g', 'pur_price' => 45, 'sell_price' => 58, 'mrp' => 62, 'discount' => 4],
                    ['value' => '200 g', 'pur_price' => 85, 'sell_price' => 110, 'mrp' => 120, 'discount' => 10],
                ]
            ],
            [
                'name' => 'Sunsilk Stunning Black Shine Shampoo',
                'category' => 'Hair Care',
                'brand' => 'Sunsilk',
                'image' => 'products/sunsilk.jpg',
                'variants' => [
                    ['value' => '340 ml', 'pur_price' => 200, 'sell_price' => 245, 'mrp' => 275, 'discount' => 30],
                ]
            ],
            [
                'name' => 'Parachute Advanced Jasmine Hair Oil',
                'category' => 'Hair Care',
                'brand' => 'Parachute',
                'image' => 'products/parachute_oil.jpg',
                'variants' => [
                    ['value' => '300 ml', 'pur_price' => 90, 'sell_price' => 115, 'mrp' => 130, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Himalaya Purifying Neem Face Wash',
                'category' => 'Skin Care',
                'brand' => 'Himalaya',
                'image' => 'products/himalaya_facewash.jpg',
                'variants' => [
                    ['value' => '100 ml', 'pur_price' => 95, 'sell_price' => 120, 'mrp' => 135, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Gillette Mach 3 Razor',
                'category' => 'Men\'s Grooming',
                'brand' => 'Gillette',
                'image' => 'products/gillette.jpg',
                'variants' => [
                    ['value' => '1 Pc', 'pur_price' => 180, 'sell_price' => 220, 'mrp' => 245, 'discount' => 25],
                ]
            ],
            [
                'name' => 'Fogg Master Cedar Body Odour',
                'category' => 'Men\'s Grooming',
                'brand' => 'Fogg',
                'image' => 'products/fogg_deo.jpg',
                'variants' => [
                    ['value' => '150 ml', 'pur_price' => 140, 'sell_price' => 180, 'mrp' => 220, 'discount' => 40],
                ]
            ],

            // --- CLEANING ESSENTIALS ---
            [
                'name' => 'Surf Excel Easy Wash Detergent',
                'category' => 'Detergents',
                'brand' => 'Surf Excel',
                'image' => 'products/surf_excel.jpg',
                'variants' => [
                    ['value' => '1 Kg', 'pur_price' => 105, 'sell_price' => 125, 'mrp' => 135, 'discount' => 10],
                    ['value' => '3 Kg', 'pur_price' => 300, 'sell_price' => 360, 'mrp' => 399, 'discount' => 39],
                ]
            ],
            [
                'name' => 'Ariel Matic Front Load Detergent Powder',
                'category' => 'Detergents',
                'brand' => 'Ariel',
                'image' => 'products/ariel.jpg',
                'variants' => [
                    ['value' => '2 Kg', 'pur_price' => 380, 'sell_price' => 450, 'mrp' => 520, 'discount' => 70],
                ]
            ],
            [
                'name' => 'Vim Lemon Dishwash Bar',
                'category' => 'Dishwash',
                'brand' => 'Vim',
                'image' => 'products/vim_bar.jpg',
                'variants' => [
                    ['value' => '300 g', 'pur_price' => 20, 'sell_price' => 28, 'mrp' => 30, 'discount' => 2],
                    ['value' => '500 g', 'pur_price' => 35, 'sell_price' => 45, 'mrp' => 50, 'discount' => 5],
                ]
            ],
            [
                'name' => 'Harpic Power Plus Toilet Cleaner',
                'category' => 'Floor & Toilet Cleaners',
                'brand' => 'Harpic',
                'image' => 'products/harpic.jpg',
                'variants' => [
                    ['value' => '500 ml', 'pur_price' => 75, 'sell_price' => 95, 'mrp' => 105, 'discount' => 10],
                    ['value' => '1 L', 'pur_price' => 140, 'sell_price' => 175, 'mrp' => 195, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Lizol Disinfectant Floor Cleaner',
                'category' => 'Floor & Toilet Cleaners',
                'brand' => 'Lizol',
                'image' => 'products/lizol.jpg',
                'variants' => [
                    ['value' => '1 L', 'pur_price' => 150, 'sell_price' => 189, 'mrp' => 210, 'discount' => 21],
                ]
            ],
            [
                'name' => 'Colin Glass and Surface Cleaner',
                'category' => 'Floor & Toilet Cleaners',
                'brand' => 'Colin',
                'image' => 'products/colin.jpg',
                'variants' => [
                    ['value' => '500 ml', 'pur_price' => 70, 'sell_price' => 92, 'mrp' => 105, 'discount' => 13],
                ]
            ],
            [
                'name' => 'All Out Ultra Mosquito Repellent Refill',
                'category' => 'Repellents & Fresheners',
                'brand' => 'All Out',
                'image' => 'products/allout.jpg',
                'variants' => [
                    ['value' => '45 ml (Pack of 2)', 'pur_price' => 120, 'sell_price' => 145, 'mrp' => 160, 'discount' => 15],
                ]
            ],
            [
                'name' => 'Mangaldeep Rose Agarbatti',
                'category' => 'Pooja Needs',
                'brand' => 'Mangaldeep',
                'image' => 'products/agarbatti.jpg',
                'variants' => [
                    ['value' => '100 Sticks', 'pur_price' => 35, 'sell_price' => 45, 'mrp' => 50, 'discount' => 5],
                ]
            ],

            // --- BABY CARE ---
            [
                'name' => 'Pampers Active Baby Taped Diapers',
                'category' => 'Diapers & Wipes',
                'brand' => 'Pampers',
                'image' => 'products/pampers.jpg',
                'variants' => [
                    ['value' => 'Medium (20 Pcs)', 'pur_price' => 280, 'sell_price' => 349, 'mrp' => 399, 'discount' => 50],
                    ['value' => 'Large (20 Pcs)', 'pur_price' => 320, 'sell_price' => 399, 'mrp' => 450, 'discount' => 51],
                ]
            ],
            [
                'name' => 'Himalaya Baby Wipes',
                'category' => 'Diapers & Wipes',
                'brand' => 'Himalaya',
                'image' => 'products/baby_wipes.jpg',
                'variants' => [
                    ['value' => '72 Pcs', 'pur_price' => 120, 'sell_price' => 150, 'mrp' => 175, 'discount' => 25],
                ]
            ],
            [
                'name' => 'Nestle Cerelac Wheat Apple',
                'category' => 'Baby Food',
                'brand' => 'Nestle',
                'image' => 'products/cerelac.jpg',
                'variants' => [
                    ['value' => '300 g', 'pur_price' => 220, 'sell_price' => 260, 'mrp' => 280, 'discount' => 20],
                ]
            ],
            [
                'name' => 'Johnson\'s Baby Soap',
                'category' => 'Baby Bath & Skin',
                'brand' => 'Johnsons',
                'image' => 'products/baby_soap.jpg',
                'variants' => [
                    ['value' => '100 g', 'pur_price' => 40, 'sell_price' => 52, 'mrp' => 60, 'discount' => 8],
                ]
            ],

            // --- PET CARE ---
            [
                'name' => 'Pedigree Adult Dry Dog Food (Meat & Rice)',
                'category' => 'Dog Food',
                'brand' => 'Pedigree',
                'image' => 'products/pedigree.jpg',
                'variants' => [
                    ['value' => '1.2 Kg', 'pur_price' => 260, 'sell_price' => 320, 'mrp' => 360, 'discount' => 40],
                    ['value' => '3 Kg', 'pur_price' => 600, 'sell_price' => 750, 'mrp' => 850, 'discount' => 100],
                ]
            ],
            [
                'name' => 'Whiskas Adult Dry Cat Food',
                'category' => 'Cat Food',
                'brand' => 'Whiskas',
                'image' => 'products/whiskas.jpg',
                'variants' => [
                    ['value' => '1.2 Kg', 'pur_price' => 280, 'sell_price' => 340, 'mrp' => 380, 'discount' => 40],
                ]
            ],
            [
                'name' => 'Bingo Mad Angles Masala',
                'category' => 'Chips & Namkeen',
                'brand' => 'Bingo',
                'image' => 'products/bingo_mad_angles.jpg',
                'variants' => [
                    [
                        'value' => '72 g',
                        'pur_price' => 18,
                        'sell_price' => 25,
                        'mrp' => 30,
                        'discount' => 5,
                    ],
                    [
                        'value' => '144 g',
                        'pur_price' => 35,
                        'sell_price' => 48,
                        'mrp' => 55,
                        'discount' => 7,
                    ],
                ]
            ],
            [
                'name' => 'Bournvita Health Drink',
                'category' => 'Breakfast Cereals',
                'brand' => 'Cadbury',
                'image' => 'products/bournvita.jpg',
                'variants' => [
                    [
                        'value' => '500 g',
                        'pur_price' => 185,
                        'sell_price' => 235,
                        'mrp' => 255,
                        'discount' => 20,
                    ],
                    [
                        'value' => '1 Kg',
                        'pur_price' => 360,
                        'sell_price' => 445,
                        'mrp' => 480,
                        'discount' => 35,
                    ],
                ]
            ],
            [
                'name' => 'Tropicana Orange Juice',
                'category' => 'Juices & Syrups',
                'brand' => 'Tropicana',
                'image' => 'products/tropicana_orange.jpg',
                'variants' => [
                    [
                        'value' => '1 L',
                        'pur_price' => 95,
                        'sell_price' => 125,
                        'mrp' => 140,
                        'discount' => 15,
                    ]
                ]
            ],
            [
                'name' => 'Good Day Cashew Cookies',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Britannia',
                'image' => 'products/goodday.jpg',
                'variants' => [
                    [
                        'value' => '200 g',
                        'pur_price' => 28,
                        'sell_price' => 38,
                        'mrp' => 45,
                        'discount' => 7,
                    ]
                ]
            ],
            [
                'name' => 'Balaji Wafers Simply Salted',
                'category' => 'Chips & Namkeen',
                'brand' => 'Balaji',
                'image' => 'products/balaji_salted.jpg',
                'variants' => [
                    [
                        'value' => '45 g',
                        'pur_price' => 12,
                        'sell_price' => 18,
                        'mrp' => 20,
                        'discount' => 2,
                    ],
                    [
                        'value' => '90 g',
                        'pur_price' => 22,
                        'sell_price' => 35,
                        'mrp' => 40,
                        'discount' => 5,
                    ],
                ]
            ],

            [
                'name' => 'Pringles Original Chips',
                'category' => 'Chips & Namkeen',
                'brand' => 'Pringles',
                'image' => 'products/pringles.jpg',
                'variants' => [
                    [
                        'value' => '107 g',
                        'pur_price' => 85,
                        'sell_price' => 110,
                        'mrp' => 125,
                        'discount' => 15,
                    ],
                ]
            ],

            [
                'name' => 'Britannia Marie Gold',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Britannia',
                'image' => 'products/marie_gold.jpg',
                'variants' => [
                    [
                        'value' => '250 g',
                        'pur_price' => 22,
                        'sell_price' => 30,
                        'mrp' => 35,
                        'discount' => 5,
                    ],
                    [
                        'value' => '500 g',
                        'pur_price' => 42,
                        'sell_price' => 58,
                        'mrp' => 65,
                        'discount' => 7,
                    ],
                ]
            ],

            [
                'name' => 'Hide & Seek Chocolate',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Parle',
                'image' => 'products/hide_seek.jpg',
                'variants' => [
                    [
                        'value' => '120 g',
                        'pur_price' => 28,
                        'sell_price' => 38,
                        'mrp' => 45,
                        'discount' => 7,
                    ],
                ]
            ],

            [
                'name' => 'Monster Energy Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Monster',
                'image' => 'products/monster.jpg',
                'variants' => [
                    [
                        'value' => '350 ml',
                        'pur_price' => 90,
                        'sell_price' => 115,
                        'mrp' => 125,
                        'discount' => 10,
                    ],
                ]
            ],

            [
                'name' => 'Pepsi Soft Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Pepsi',
                'image' => 'products/pepsi.jpg',
                'variants' => [
                    [
                        'value' => '750 ml',
                        'pur_price' => 38,
                        'sell_price' => 48,
                        'mrp' => 55,
                        'discount' => 7,
                    ],
                    [
                        'value' => '2.25 L',
                        'pur_price' => 85,
                        'sell_price' => 110,
                        'mrp' => 125,
                        'discount' => 15,
                    ],
                ]
            ],

            [
                'name' => 'Maaza Mango Drink',
                'category' => 'Juices & Syrups',
                'brand' => 'Maaza',
                'image' => 'products/maaza.jpg',
                'variants' => [
                    [
                        'value' => '600 ml',
                        'pur_price' => 28,
                        'sell_price' => 38,
                        'mrp' => 45,
                        'discount' => 7,
                    ],
                    [
                        'value' => '1.2 L',
                        'pur_price' => 55,
                        'sell_price' => 70,
                        'mrp' => 80,
                        'discount' => 10,
                    ],
                ]
            ],

            [
                'name' => 'Bru Instant Coffee',
                'category' => 'Tea & Coffee',
                'brand' => 'Bru',
                'image' => 'products/bru.jpg',
                'variants' => [
                    [
                        'value' => '50 g',
                        'pur_price' => 110,
                        'sell_price' => 135,
                        'mrp' => 150,
                        'discount' => 15,
                    ],
                    [
                        'value' => '100 g',
                        'pur_price' => 220,
                        'sell_price' => 265,
                        'mrp' => 290,
                        'discount' => 25,
                    ],
                ]
            ],

            [
                'name' => 'Fortune Besan',
                'category' => 'Atta & Rice',
                'brand' => 'Fortune',
                'image' => 'products/besan.jpg',
                'variants' => [
                    [
                        'value' => '500 g',
                        'pur_price' => 38,
                        'sell_price' => 48,
                        'mrp' => 55,
                        'discount' => 7,
                    ],
                    [
                        'value' => '1 Kg',
                        'pur_price' => 75,
                        'sell_price' => 95,
                        'mrp' => 110,
                        'discount' => 15,
                    ],
                ]
            ],

            [
                'name' => 'Fortune Poha',
                'category' => 'Atta & Rice',
                'brand' => 'Fortune',
                'image' => 'products/poha.jpg',
                'variants' => [
                    [
                        'value' => '500 g',
                        'pur_price' => 32,
                        'sell_price' => 42,
                        'mrp' => 48,
                        'discount' => 6,
                    ],
                    [
                        'value' => '1 Kg',
                        'pur_price' => 60,
                        'sell_price' => 78,
                        'mrp' => 90,
                        'discount' => 12,
                    ],
                ]
            ],

            [
                'name' => 'Fortune Maida',
                'category' => 'Atta & Rice',
                'brand' => 'Fortune',
                'image' => 'products/fortune_maida.jpg',
                'variants' => [
                    [
                        'value' => '500 g',
                        'pur_price' => 24,
                        'sell_price' => 32,
                        'mrp' => 38,
                        'discount' => 6,
                    ],
                    [
                        'value' => '1 Kg',
                        'pur_price' => 46,
                        'sell_price' => 60,
                        'mrp' => 68,
                        'discount' => 8,
                    ],
                ]
            ],

            [
                'name' => 'Tata Sampann Moong Dal',
                'category' => 'Dal & Pulses',
                'brand' => 'Tata Sampann',
                'image' => 'products/moong_dal.jpg',
                'variants' => [
                    [
                        'value' => '500 g',
                        'pur_price' => 72,
                        'sell_price' => 88,
                        'mrp' => 100,
                        'discount' => 12,
                    ],
                    [
                        'value' => '1 Kg',
                        'pur_price' => 140,
                        'sell_price' => 170,
                        'mrp' => 195,
                        'discount' => 25,
                    ],
                ]
            ],

            [
                'name' => 'Fortune Kachi Ghani Mustard Oil',
                'category' => 'Oil & Ghee',
                'brand' => 'Fortune',
                'image' => 'products/mustard_oil.jpg',
                'variants' => [
                    [
                        'value' => '1 L',
                        'pur_price' => 155,
                        'sell_price' => 175,
                        'mrp' => 195,
                        'discount' => 20,
                    ],
                    [
                        'value' => '5 L',
                        'pur_price' => 760,
                        'sell_price' => 845,
                        'mrp' => 940,
                        'discount' => 95,
                    ],
                ]
            ],

            [
                'name' => 'Catch Black Pepper Powder',
                'category' => 'Masala & Spices',
                'brand' => 'Catch',
                'image' => 'products/black_pepper.jpg',
                'variants' => [
                    [
                        'value' => '100 g',
                        'pur_price' => 85,
                        'sell_price' => 110,
                        'mrp' => 125,
                        'discount' => 15,
                    ],
                ]
            ],

            [
                'name' => 'Balaji Aloo Sev',
                'category' => 'Chips & Namkeen',
                'brand' => 'Balaji',
                'image' => 'products/aloo_sev.jpg',
                'variants' => [
                    [
                        'value' => '200 g',
                        'pur_price' => 38,
                        'sell_price' => 50,
                        'mrp' => 58,
                        'discount' => 8,
                    ],
                ]
            ],

            [
                'name' => 'Bourbon Chocolate Biscuits',
                'category' => 'Biscuits & Cookies',
                'brand' => 'Britannia',
                'image' => 'products/bourbon.jpg',
                'variants' => [
                    [
                        'value' => '150 g',
                        'pur_price' => 28,
                        'sell_price' => 38,
                        'mrp' => 45,
                        'discount' => 7,
                    ],
                ]
            ],

            [
                'name' => 'Cadbury 5 Star',
                'category' => 'Chocolates & Candies',
                'brand' => 'Cadbury',
                'image' => 'products/5star.jpg',
                'variants' => [
                    [
                        'value' => '40 g',
                        'pur_price' => 18,
                        'sell_price' => 25,
                        'mrp' => 30,
                        'discount' => 5,
                    ],
                ]
            ],

            [
                'name' => 'KitKat Chocolate',
                'category' => 'Chocolates & Candies',
                'brand' => 'Nestle',
                'image' => 'products/kitkat.jpg',
                'variants' => [
                    [
                        'value' => '37 g',
                        'pur_price' => 18,
                        'sell_price' => 25,
                        'mrp' => 30,
                        'discount' => 5,
                    ],
                ]
            ],

            [
                'name' => 'Limca Soft Drink',
                'category' => 'Cold Drinks',
                'brand' => 'Limca',
                'image' => 'products/limca.jpg',
                'variants' => [
                    [
                        'value' => '750 ml',
                        'pur_price' => 34,
                        'sell_price' => 42,
                        'mrp' => 48,
                        'discount' => 6,
                    ],
                    [
                        'value' => '2 L',
                        'pur_price' => 78,
                        'sell_price' => 98,
                        'mrp' => 110,
                        'discount' => 12,
                    ],
                ]
            ],

            [
                'name' => 'Slice Mango Drink',
                'category' => 'Juices & Syrups',
                'brand' => 'Slice',
                'image' => 'products/slice.jpg',
                'variants' => [
                    [
                        'value' => '1.2 L',
                        'pur_price' => 55,
                        'sell_price' => 68,
                        'mrp' => 78,
                        'discount' => 10,
                    ],
                ]
            ],

            [
                'name' => 'Clinic Plus Shampoo',
                'category' => 'Hair Care',
                'brand' => 'Clinic Plus',
                'image' => 'products/clinic_plus.jpg',
                'variants' => [
                    [
                        'value' => '340 ml',
                        'pur_price' => 155,
                        'sell_price' => 190,
                        'mrp' => 220,
                        'discount' => 30,
                    ],
                ]
            ],

            [
                'name' => 'Lux Rose Soap',
                'category' => 'Bath & Body',
                'brand' => 'Lux',
                'image' => 'products/lux.jpg',
                'variants' => [
                    [
                        'value' => '150 g',
                        'pur_price' => 28,
                        'sell_price' => 38,
                        'mrp' => 45,
                        'discount' => 7,
                    ],
                    [
                        'value' => 'Pack of 4',
                        'pur_price' => 110,
                        'sell_price' => 145,
                        'mrp' => 170,
                        'discount' => 25,
                    ],
                ]
            ],

            [
                'name' => 'Wheel Active Detergent Powder',
                'category' => 'Detergents',
                'brand' => 'Wheel',
                'image' => 'products/wheel.jpg',
                'variants' => [
                    [
                        'value' => '1 Kg',
                        'pur_price' => 75,
                        'sell_price' => 92,
                        'mrp' => 105,
                        'discount' => 13,
                    ],
                    [
                        'value' => '3 Kg',
                        'pur_price' => 210,
                        'sell_price' => 265,
                        'mrp' => 299,
                        'discount' => 34,
                    ],
                ]
            ],

            [
                'name' => 'Johnson Baby Powder',
                'category' => 'Baby Bath & Skin',
                'brand' => 'Johnson',
                'image' => 'products/johnson_powder.jpg',
                'variants' => [
                    [
                        'value' => '200 g',
                        'pur_price' => 105,
                        'sell_price' => 130,
                        'mrp' => 145,
                        'discount' => 15,
                    ],
                ]
            ],

            [
                'name' => 'Drools Adult Dog Food',
                'category' => 'Dog Food',
                'brand' => 'Drools',
                'image' => 'products/drools.jpg',
                'variants' => [
                    [
                        'value' => '1.2 Kg',
                        'pur_price' => 240,
                        'sell_price' => 295,
                        'mrp' => 340,
                        'discount' => 45,
                    ],
                    [
                        'value' => '3 Kg',
                        'pur_price' => 560,
                        'sell_price' => 690,
                        'mrp' => 780,
                        'discount' => 90,
                    ],
                ]
            ],
        ];

        // 5. Dynamic Variant Values Creation & Lookup Map
        $variantValueMap = [];
        foreach ($productsData as $prod) {
            foreach ($prod['variants'] as $var) {
                if (!isset($variantValueMap[$var['value']])) {
                    $valObj = VariantValue::firstOrCreate(
                        ['variant_id' => $globalVariant->id, 'value' => $var['value']],
                        ['is_active' => true]
                    );
                    $variantValueMap[$var['value']] = $valObj->id;
                }
            }
        }

        // 6. Loop and Create Products + Variants + Platform Linkages
        foreach ($productsData as $index => $prodData) {
            $skuBase = 'GRC-' . strtoupper(Str::slug($prodData['brand'])) . '-' . sprintf('%03d', $index + 1);

            // Create Product
            $product = Product::create([
                'sku' => $skuBase,
                'name' => $prodData['name'],
                'slug' => Str::slug($prodData['name'] . '-' . rand(100, 999)), // Ensure unique slug if names match
                'category_id' => $categoryMap[$prodData['category']], // Linked automatically
                'supplier_id' => $supplier->id,
                'warehouse_id' => $warehouse->id,
                'brand' => $prodData['brand'],
                'image_url' => $prodData['image'],
                'status' => 'active',
                'visibility' => 'public',
                'is_top_selling' => (bool) rand(0, 1), // Randomly true or false
            ]);

            // Create Platform Product
            $platformProduct = PlatformProduct::create([
                'platform_id' => $platform->id,
                'product_id' => $product->id,
                'platform_sku' => 'PLT-' . $skuBase,
                'status' => 'active',
                'is_enabled' => true,
            ]);

            // Loop Variants of this product
            foreach ($prodData['variants'] as $vIndex => $varData) {

                // Create Product Variant
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_id' => $globalVariant->id,
                    'variant_value_id' => $variantValueMap[$varData['value']],
                    'quantity' => rand(50, 200), // Random Stock
                    'purchase_price' => $varData['pur_price'],
                    'selling_price' => $varData['mrp'], // MRP
                    'total_price' => $varData['mrp'],
                    'sku_suffix' => strtoupper(Str::slug($varData['value'])),
                    'status' => 'active',
                ]);

                // Create Pricing for the Platform (Applying Discounts)
                PlatformPricing::create([
                    'platform_product_id' => $platformProduct->id,
                    'product_variant_id' => $productVariant->id,
                    'price' => $varData['mrp'],
                    'discount_type' => 'fixed',
                    'discount_value' => $varData['discount'],
                    'final_price' => $varData['sell_price'], // Final customer price
                    'quantity' => rand(50, 200),
                    'currency' => 'INR',
                    'status' => 'active',
                ]);
            }
        }
    }
}