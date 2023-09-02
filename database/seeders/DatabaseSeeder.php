<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            LocalImageSettingSeeder::class,
            UnsplashClientSettingSeeder::class,
            UnsplashQuerySettingSeeder::class,
            GoogleOutputSettingSeeder::class,
        ]);
    }
}
