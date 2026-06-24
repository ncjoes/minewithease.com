<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Core\Transaction;
use Database\Seeders\AuthTablesSeeder;
use Database\Seeders\CoreTablesSeeder;
use Database\Seeders\EtcTablesSeeder;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class SpecialFixes
 * @package App\Console\Commands
 */
class SpecialFixes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runFix {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');

        if (method_exists($this, $name)) {
            $this->line($this->$name());
        } else {
            $this->line('Undefined method - ' . $name);
        }
    }

    public function setupNewPackages(): string
    {
        return CoreTablesSeeder::setupNewPackages();
    }

    /**
     * @return string
     */
    public function updateCurrencySymbols(): string
    {
        return EtcTablesSeeder::updateCurrencySymbols();
    }

    /**
     * @return string
     */
    public function importUsers(): string
    {
        return AuthTablesSeeder::importUsers();
    }

    /**
     * @return string
     */
    public function fixMissingUserRoles(): string
    {
        $affected    = 0;
        $users       = User::all();
        $member_role = Role::findByName(Role::CLIENT);

        /**
         * @var User $user
         */
        foreach ($users as $user) {
            if ($user->roles()->count() == 0) {
                $user->roles()->attach($member_role->id);
                $affected++;
            }
        }

        return $affected . " users fixed.";
    }

    /**
     * @return string
     */
    public function cleanupUnlinkedTransactions(): string
    {
        $all_delete_targets = [];
        Transaction::query()->chunk(1000, function (Collection $transactions) use (&$all_delete_targets) {
            $affected_users = [];
            $current_delete_targets = [];

            /**
             * @var Transaction $transaction
             */
            foreach ($transactions as $transaction) {
                if (!is_object($transaction->item)) {
                    $current_delete_targets[] = $transaction->id;
                    $affected_users[$transaction->user_id] = $transaction->user;
                }
            }
            Transaction::whereIn('id', $current_delete_targets)->delete();
            /**
             * @var User $user
             */
            foreach ($affected_users as $id => $user) {
                $user->updateBalance();
            }
            $all_delete_targets = array_merge($current_delete_targets, $all_delete_targets);
        });

        return count($all_delete_targets) . " unlinked transactions deleted";
    }
}
