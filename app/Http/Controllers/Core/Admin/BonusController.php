<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Bonus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PortfolioController
 * @package App\Http\Controllers\Core\Admin
 */
class BonusController extends Controller
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

        $results = ($status == '*' ? Bonus::query() : Bonus::findByStatus($status));
        $results = strlen((string)$search) ? $results->where('description', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Bonus::query()->count();
        $result_count = $results->count();
        $results      = $results->orderBy('id', 'desc')->paginate($per_page);

        return view('core-admin.bonus.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'statuses'     => ['*' => '',] + Bonus::states(),
            'filter'       => [
                'search'   => $search,
                'status'   => $status,
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $valid_actions = ['approve', 'cancel'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_bonuses,id',
        ]);

        $input        = $request->input();
        $items        = Bonus::findByStatus(Bonus::S_PENDING)->whereIn('id', $input['ids']);
        $affected     = 0;
        $transactions = [];

        switch ($input['action']) {
            case 'approve':
                $affected = $items->update(['status' => Bonus::S_APPROVED]);
                break;
            case 'cancel':
                $affected = $items->update(['status' => Bonus::S_CANCELED]);
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
