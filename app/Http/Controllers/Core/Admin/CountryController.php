<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class CountryController
 * @package App\Http\Controllers\Core\Admin
 */
class CountryController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        $status   = (boolean)$request->get('status', true);
        $per_page = $request->get('per_page', 30);

        $results = Country::where('is_active', $status);
        $results = strlen((string)$search) ? $results->where('name', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Country::count();
        $result_count = $results->count();
        $results      = $results->orderBy('id', 'desc')->paginate($per_page);

        return view('core-admin.country.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'statuses'     => [
                1 => 'Yes',
                0 => 'No',
            ],
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
    public function doManage(Request $request)
    {
        $valid_actions = ['activate', 'deactivate'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:etc_countries,id',
        ]);

        $input     = $request->input();
        $countries = Country::whereIn('id', $input['ids']);
        $affected  = 0;

        DB::beginTransaction();
        switch ($input['action']) {
            case 'activate':
                $affected = $countries->update(['is_active' => true]);
                /**
                 * @var Country $country
                 */
                foreach ($countries->get() as $country) {
                    Currency::whereIn('id', $country->currencies()->pluck('etc_currencies.id')->all())
                            ->update(['is_active' => true]);
                }
                break;
            case 'deactivate':
                $affected = $countries->update(['is_active' => false]);
                /**
                 * @var Country $country
                 */
                foreach ($countries->get() as $country) {
                    Currency::whereIn('id', $country->currencies()->pluck('etc_currencies.id')->all())
                            ->update(['is_active' => false]);
                }
                break;
        }
        DB::commit();

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
