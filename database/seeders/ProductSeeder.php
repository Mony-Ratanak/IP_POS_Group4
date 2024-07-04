<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products_type')->insert(
            [
                [
                    'name' => 'Hot Dishes',
                    'image' => 'static/Products_Type/Hot_Dishes.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name' => 'Cold Dishes',
                    'image' => 'static/Products_Type/Cold_Dishes.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name' => 'Soup',
                    'image' => 'static/Products_Type/Soup.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name' => 'Grill',
                    'image' => 'static/Products_Type/Grill.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name' => 'Appetizer',
                    'image' => 'static/Products_Type/Appetizer.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

                [
                    'name' => 'Dessert',
                    'image' => 'static/Products_Type/Dessert.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

            ]
        );

        /*
        |-------------------------------------------------------------------------------
        | Add 20 Products
        |-------------------------------------------------------------------------------
        */
        DB::table('product')->insert(
            [
                [
                    'code' => 'G001',
                    'type_id' => '1',
                    'name' => 'Spicy seasoned seafood noodles',
                    'des'  => '12 Bowls available',
                    'unit_price' => 3.49,
                    'image' => 'static/Products/Hot-Dishes/image_1.png',
                    'quantity' => 3, // Set quantity as an integer
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G002',
                    'type_id' => '1',
                    'name' => 'Salted Pasta with mushroom sauce',
                    'des'  => '11 Bowls available',
                    'unit_price' => 2.99,
                    'image' => 'static/Products/Hot-Dishes/image_2.png',
                    'quantity' => 3,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G003',
                    'type_id' => '1',
                    'name' => 'Beef dumpling in hot and sour soup',
                    'des'  => '5 Bowls available',
                    'unit_price' => 3.99,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_3.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G004',
                    'type_id' => '1',
                    'name' => 'Healthy noodle with spinach leaf',
                    'des'  => '10 Bowls available',
                    'unit_price' => 39.99,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_4.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G005',
                    'type_id' => '1',
                    'name' => 'Hot spicy fried rice with omelet',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_5.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G006',
                    'type_id' => '1',
                    'name' => 'Spicy instant noodle with special omelette',
                    'des'  => '17 Bowls available',
                    'unit_price' => 3.59,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_6.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G007',
                    'type_id' => '1',
                    'name' => 'Healthy noodle with spinach leaf',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.29,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_7.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G008',
                    'type_id' => '1',
                    'name' => 'Hot spicy fried rice with omelet',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_8.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G009',
                    'type_id' => '1',
                    'name' => 'Spicy instant noodle with special omelette',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Hot-Dishes/image_9.png',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'C001',
                    'type_id' => '2',
                    'name' => 'Japchae / Korean stir-fried noodles',
                    'des'  => '20 Bowls available',
                    'unit_price' => 2.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Cold-Dishes/image_1.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'C002',
                    'type_id' => '2',
                    'name' => '18 oz Round White Plastic Salad Bowl ',
                    'des'  => '12 Bowls available',
                    'unit_price' => 2.59,
                    'quantity' => 3,
                    'image' => 'static/Products/Cold-Dishes/image_2.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'C003',
                    'type_id' => '2',
                    'name' => 'Paper Bowl With Tuna Salad',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.29,
                    'quantity' => 3,
                    'image' => 'static/Products/Cold-Dishes/image_3.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'C004',
                    'type_id' => '2',
                    'name' => 'Paper Bowl With Asian Lunch',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Cold-Dishes/image_4.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'C005',
                    'type_id' => '2',
                    'name' => 'Salad Mixes',
                    'des'  => '13 Bowls available',
                    'unit_price' => 3.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Cold-Dishes/image_5.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S001',
                    'type_id' => '3',
                    'name' => 'Cream of Mushroom Soup',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Soup/image_1.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S002',
                    'type_id' => '3',
                    'name' => 'Carrot potato soup',
                    'des'  => '7 Bowls available',
                    'unit_price' => 5.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Soup/image_2.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S003',
                    'type_id' => '3',
                    'name' => 'Dumpling (Pelmeni) Soup',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Soup/image_3.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S004',
                    'type_id' => '3',
                    'name' => 'Traditional Polish Grochówka - Split Pea Soup',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Soup/image_4.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'S005',
                    'type_id' => '3',
                    'name' => 'Zupa Jarzynowa - Polish Vegetable Soup',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Soup/image_5.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G001',
                    'type_id' => '4',
                    'name' => 'Chopped Grilled Vegetable Salad with Lemon Basil Vinaigrette',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Grill/image_1.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G002',
                    'type_id' => '4',
                    'name' => 'Roasted Vege Salad with Mustard Dressing',
                    'des'  => '6 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Grill/image_2.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G003',
                    'type_id' => '4',
                    'name' => 'Everyday Italian Salad',
                    'des'  => '7 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Grill/image_3.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G004',
                    'type_id' => '4',
                    'name' => 'Vegan Cobb Salad - The Simple Veganista',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Grill/image_4.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G005',
                    'type_id' => '4',
                    'name' => 'Avocado Salad with Tomato, Eggs and Cucumber',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Grill/image_5.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'A001',
                    'type_id' => '5',
                    'name' => 'Avocado Salad with Tomato, Eggs and Cucumber',
                    'des'  => '5 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Appetizer/image_1.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'A002',
                    'type_id' => '5',
                    'name' => 'Grilled Chicken Salad Recipe - Crunchy Creamy Sweet',
                    'des'  => '6 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Appetizer/image_2.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'A003',
                    'type_id' => '5',
                    'name' => 'Grilled Chicken Cobb Salad with Honey Dijon {Paleo}',
                    'des'  => '7 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Appetizer/image_3.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'A004',
                    'type_id' => '5',
                    'name' => 'Best Healthy Dinner Recipes for Weight Loss',
                    'des'  => '7 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Appetizer/image_4.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'A005',
                    'type_id' => '5',
                    'name' => 'Simple Miso Sesame Salmon — Kvaroy Arctic',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Appetizer/image_5.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'D001',
                    'type_id' => '6',
                    'name' => 'Sutlac - Turkish Rice Pudding',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Dessert/image_1.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'D002',
                    'type_id' => '6',
                    'name' => 'Moist Namoura (Basbousa)',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Dessert/image_2.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'D003',
                    'type_id' => '6',
                    'name' => 'Basbousa',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Dessert/image_3.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'D004',
                    'type_id' => '6',
                    'name' => 'Keskul - Turkish Almond Pudding',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Dessert/image_4.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'D005',
                    'type_id' => '6',
                    'name' => 'Saffron Cardamom Baked Yogurt',
                    'des'  => '8 Bowls available',
                    'unit_price' => 4.49,
                    'quantity' => 3,
                    'image' => 'static/Products/Dessert/image_5.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],

            ]
        );
    }
}
