<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Create User Type: Admin & Staff
        |--------------------------------------------------------------------------
        */
        DB::table('users_type')->insert(
            [
                ['name' => 'Admin'],
                ['name' => 'Staff'],
            ]
        );
        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */
        $users =  [
            [
                'type_id'       => 1,
                'email'         => 'yimklok.kh@gmail.com',
                'phone'         => '0977779688',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Yim Klok',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'type_id'       => 2,
                'email'         => 'choengkimlay@gmail.com',
                'phone'         => '0977779690',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Choeng Kimlay',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'chharngchhit@gmail.com',
                'phone'         => '0977779691',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Chharng Chhit',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'heikly@gmail.com',
                'phone'         => '0977779692',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Heik Ly',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'monyratanak@gmail.com',
                'phone'         => '0977779693',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Mony Ratanak',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'haitongmeng@gmail.com',
                'phone'         => '0977779694',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Hai Tongmeng',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'hychin@gmail.com',
                'phone'         => '0977779695',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Hy Chin',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'kangeangchheang@gmail.com',
                'phone'         => '0977779696',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Kang Eangchheang',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'dyrongrath@gmail.com',
                'phone'         => '0977779697',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Dy Rongrath',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'vendara@gmail.com',
                'phone'         => '0977779698',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Ven Dara',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'type_id'       => 2,
                'email'         => 'hoksochetra@gmail.com',
                'phone'         => '0977779699',
                'password'      => bcrypt('123456'),
                'is_active'     => 1,
                'name'          => 'Hok Sochetra',
                'avatar'        => 'static/icon/user.png',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Write To Database
        |--------------------------------------------------------------------------
        |
        | It will insert to table users of database minimart.
        |
        */
        DB::table('user')->insert($users);
    }
}
