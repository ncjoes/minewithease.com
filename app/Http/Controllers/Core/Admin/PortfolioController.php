<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Managers\PortfolioManager;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PortfolioController
 * @package App\Http\Controllers\Core\Admin
 */
class PortfolioController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        $status   = $request->get('status', '*');
        $per_page = $request->get('per_page', 30);
        $package  = Package::find($request->get('package'));

        $packages = Package::activeOnly()->get();
        $results  = $status == '*' ? Portfolio::where('id', '<>', null) : Portfolio::findByStatus($status);
        $results  = is_object($package) ? $results->where('package_id', $package->id) : $results;
        $results  = strlen((string)$search) ? $results->where('uuid', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Portfolio::count();
        $result_count = $results->count();
        $results      = $results->orderBy('id', 'desc')->paginate($per_page);

        return view('core-admin.portfolio.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'packages'     => $packages,
            'statuses'     => [
                '*'                    => '',
                Portfolio::S_ACTIVE    => 'Active',
                Portfolio::S_COMPLETED => 'Completed',
                Portfolio::S_CLOSED    => 'Closed',
            ],
            'filter'       => [
                'search'   => $search,
                'status'   => $status,
                'package'  => is_object($package) ? $package->id : null,
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param Portfolio $portfolio
     * @return Factory|View
     */
    public function view(Request $request, Portfolio $portfolio)
    {
        return view('core-admin.portfolio.view', [
            '_user'        => $portfolio->user,
            'portfolio'    => $portfolio,
            'package'      => $portfolio->package,
            'transactions' => $portfolio->transactions,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request)
    {
        $valid_actions = ['close'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_portfolios,id',
        ]);

        $input        = $request->input();
        $items        = Portfolio::whereIn('id', $input['ids']);
        $transactions = [];

        switch ($input['action']) {
            case 'close':
                /**
                 * @var Portfolio $portfolio
                 */
                foreach ($items->get() as $portfolio) {
                    $transactions = array_merge($transactions, PortfolioManager::closeManually($portfolio));
                }

                $transactions = Arr::flatten($transactions);
                NotificationManager::sendTransactionNotices($transactions);
                break;
        }

        $affected = count($transactions);

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
