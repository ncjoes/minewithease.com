<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\Account;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

/**
 * Class AuthTablesSeeder
 */
class AuthTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Role::create([
                'name'         => Role::ROOT,
                'display_name' => 'Root-User',
                'description'  => 'System Root User',
            ]);

            Role::create([
                'name'         => Role::ADMIN,
                'display_name' => 'System-Administrator',
                'description'  => 'System Administrator',
            ]);
        });

        DB::transaction(function () {
            $root_permission = Permission::create([
                'name'         => Permission::ROOT,
                'display_name' => 'All Permissions',
                'description'  => 'All Permissions',
            ]);

            /**
             * @var Role $root_role
             */
            $root_role = Role::findByName(Role::ROOT);
            $root_role->permissions()->attach($root_permission->id);
        });

        DB::transaction(function () {
            $root_user       = Role::findByName(Role::ROOT);
            $site_admin      = Role::findByName(Role::ADMIN);
            $root_permission = Permission::findByName(Permission::ROOT);
            $default_country = Country::findByISO2('ng');

            $now = Carbon::now();
            $user = User::factory()->create([
                    'uuid'              => User::generateUUID(),
                    'country_id'        => $default_country->id,
                    'email'             => app()->environment('local') ?'admin@domain.test' : 'admin@' . config('app.domain'),
                    'password'          => 'admin-secret', //bcrypt('admin-secret')
                    'account_settings'  => User::defaultSettings(),
                    'email_verified_at' => $now,
                    'phone_verified_at' => $now,
                ]);
            $user->roles()->attach([$root_user->id, $site_admin->id]);
            $user->permissions()->attach([$root_permission->id]);

            $crypto_currencies = Currency::activeOnly()->where('is_crypto', true)->get();

            foreach($crypto_currencies as $currency) {
                Account::factory()->create([
                    'user_id'     => $user->id,
                    'currency_id' => $currency->id,
                ]);
            }

        });
    }
}
