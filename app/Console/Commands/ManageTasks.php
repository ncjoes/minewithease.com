<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Managers\BonusManager;
use App\Managers\DepositManager;
use App\Managers\PortfolioManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class ManageTasks
 * @package App\Console\Commands
 */
class ManageTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Cron Jobs';

    /**
     * Create a new command instance.
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
    public function handle(): void
    {
        $name = $this->argument('name');

        DB::beginTransaction();
        switch ($name) {
            case 'assign-interests' :
                if (!is_null($report = PortfolioManager::assignInterests())) {
                    $this->line($report);
                }
                if (!is_null($report = PortfolioManager::updateStates())) {
                    $this->line($report);
                }
                break;
            case 'release-bonuses' :
                if (!is_null($report = BonusManager::releaseDueBonuses())) {
                    $this->line($report);
                }
                break;
            case 'cancel-invoices' :
                if (!is_null($report = DepositManager::cancelOverdueDeposits())) {
                    $this->line($report);
                }
                break;
            case 'send-invoice-reminders' :
                if (!is_null($report = DepositManager::remindPendingDepositors())) {
                    $this->line($report);
                }
                break;
        }
        DB::commit();

        return;
    }
}
