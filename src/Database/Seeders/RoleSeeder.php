<?php

namespace Monsterz\Paagez\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'user_api',
                'guard_name' => 'api'
            ]
        ];
        $pdo = config('database.default');
        $table = config("database.$pdo.prefix").'roles';
        foreach($roles as $role){
            \DB::table($table)->insert([
                'name' => $role['name'],
                'guard_name' => $role['guard_name']
            ]);
        }
    }
}
