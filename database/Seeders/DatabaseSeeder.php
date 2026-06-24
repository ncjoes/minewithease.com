<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EtcTablesSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(AuthTablesSeeder::class);
        $this->call(CmsTablesSeeder::class);
        $this->call(CoreTablesSeeder::class);
    }
}
