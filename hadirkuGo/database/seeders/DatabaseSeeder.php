<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Panggil RoleSeeder
        $this->call([
            RoleSeeder::class,
        ]);

        $this->call(LevelsTableSeeder::class);

        $this->call(AchievementSeeder::class);

        $this->call(QuotesTableSeeder::class);
    }
}
