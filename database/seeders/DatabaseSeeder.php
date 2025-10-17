<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        //$this->call(Approver_Group_Seeder::class);
        $this->call(FormSeeder::class);
        $this->call(ApplicationOptionsSeeder::class);
        $this->call(HolidaySeeder::class);
        
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //$this->call(DepartmentsTableSeeder::class);
        //$this->call(RolesTableSeeder::class);
        $this->call(UserFunctionSeeder::class);
    }
}
