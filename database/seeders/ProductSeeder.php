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
                ['name' => 'Hot Dishes',
                'created_at' => now(),
                'updated_at' => now()],

                ['name' => 'Cold Dishes',
                'created_at' => now(),
                'updated_at' => now()],

                ['name' => 'Soup',
                'created_at' => now(),
                'updated_at' => now()],

                ['name' => 'Grill',
                'created_at' => now(),
                'updated_at' => now()],

                ['name' => 'Appetizer',
                'created_at' => now(),
                'updated_at' => now()],

                ['name' => 'Dessert',
                'created_at' => now(),
                'updated_at' => now()],

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
                    'name' => 'Super Mario Odyssey',
                    'des'  => '11 Bowls available',
                    'unit_price' => 49.99,
                    'image' => 'static/Products/Platformer/SuperMarioOdyssey.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G002',
                    'type_id' => '1',
                    'name' => 'Call of Duty: Modern Warfare',
                    'des'  => '11 Bowls available',
                    'unit_price' => 59.99,
                    'image' => 'static/Products/Action/CallOfDutyModernWarfare.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G003',
                    'type_id' => '2',
                    'name' => 'The Legend of Zelda: Breath of the Wild',
                    'des'  => '11 Bowls available',
                    'unit_price' => 59.99,
                    'image' => 'static/Products/Adventure/LegendOfZeldaBreathOfTheWild.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'code' => 'G004',
                    'type_id' => '3',
                    'name' => 'Need for Speed: Heat',
                    'des'  => '11 Bowls available',
                    'unit_price' => 39.99,
                    'image' => 'static/Products/Racing/NeedForSpeedHeat.jpg',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                // [
                //     'code' => 'G005',
                //     'type_id' => '4',
                //     'name' => 'Tetris Effect',
                //     'unit_price' => 29.99,
                //     'image' => 'static/Products/Puzzle/TetrisEffect.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G006',
                //     'type_id' => '5',
                //     'name' => 'The Sims 4',
                //     'unit_price' => 39.99,
                //     'image' => 'static/Products/Simulation/TheSims4.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G007',
                //     'type_id' => '6',
                //     'name' => 'Sid Meier\'s Civilization VI',
                //     'unit_price' => 59.99,
                //     'image' => 'static/Products/Strategy/CivilizationVI.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G008',
                //     'type_id' => '7',
                //     'name' => 'FIFA 22',
                //     'unit_price' => 69.99,
                //     'image' => 'static/Products/Sports/FIFA22.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G009',
                //     'type_id' => '8',
                //     'name' => 'The Witcher 3: Wild Hunt',
                //     'unit_price' => 29.99,
                //     'image' => 'static/Products/Role-Playing/TheWitcher3WildHunt.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G010',
                //     'type_id' => '9',
                //     'name' => 'Street Fighter V',
                //     'unit_price' => 19.99,
                //     'image' => 'static/Products/Fighting/StreetFighterV.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G011',
                //     'type_id' => '10',
                //     'name' => 'Celeste',
                //     'unit_price' => 14.99,
                //     'image' => 'static/Products/Platformer/Celeste.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G012',
                //     'type_id' => '1',
                //     'name' => 'Assassin\'s Creed Valhalla',
                //     'unit_price' => 59.99,
                //     'image' => 'static/Products/Action/AssassinsCreedValhalla.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G013',
                //     'type_id' => '2',
                //     'name' => 'Red Dead Redemption 2',
                //     'unit_price' => 49.99,
                //     'image' => 'static/Products/Adventure/RedDeadRedemption2.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G014',
                //     'type_id' => '3',
                //     'name' => 'Forza Horizon 5',
                //     'unit_price' => 69.99,
                //     'image' => 'static/Products/Racing/ForzaHorizon5.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G015',
                //     'type_id' => '4',
                //     'name' => 'Portal 2',
                //     'unit_price' => 19.99,
                //     'image' => 'static/Products/Puzzle/Portal2.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G016',
                //     'type_id' => '5',
                //     'name' => 'Microsoft Flight Simulator',
                //     'unit_price' => 59.99,
                //     'image' => 'static/Products/Simulation/MicrosoftFlightSimulator.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G017',
                //     'type_id' => '6',
                //     'name' => 'Total War: Three Kingdoms',
                //     'unit_price' => 39.99,
                //     'image' => 'static/Products/Strategy/TotalWarThreeKingdoms.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G018',
                //     'type_id' => '7',
                //     'name' => 'NBA 2K22',
                //     'unit_price' => 69.99,
                //     'image' => 'static/Products/Sports/NBA2K22.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G019',
                //     'type_id' => '8',
                //     'name' => 'Final Fantasy XV',
                //     'unit_price' => 39.99,
                //     'image' => 'static/Products/Role-Playing/FinalFantasyXV.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
                // [
                //     'code' => 'G020',
                //     'type_id' => '9',
                //     'name' => 'Mortal Kombat 11',
                //     'unit_price' => 49.99,
                //     'image' => 'static/Products/Fighting/MortalKombat11.jpg',
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ],
            ]
        );

    }
}
