<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Package;
use App\Traits\Controllers\SetImage;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PackageController
 * @package App\Http\Controllers\Core\Admin
 */
class PackageController extends Controller
{
    use SetImage;

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('core-admin.package.create');
    }

    /**
     * @param Request $request
     * @param Package $package
     * @return Factory|View
     */
    public function update(Request $request, Package $package)
    {
        return view('core-admin.package.update', [
            'package' => $package,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $search   = (string)$request->get('search', '');
        $status   = (boolean)$request->get('status', true);
        $per_page = $request->get('per_page', 30);

        $results = Package::where('is_active', $status);
        $results = strlen((string)$search) ? $results->where('name', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Package::count();
        $result_count = $results->count();
        $results      = $results->orderBy('min_amount')->paginate($per_page);

        return view('core-admin.package.manage', [
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
     */
    public function doCreate(Request $request): array
    {
        $input = $request->validate([
            'name'                        => 'required|max:55',
            'description'                 => 'required|max:400',
            'min_amount'                  => 'required|numeric|min:0',
            'max_amount'                  => 'required|numeric|min:0',
            'split_amount'                => 'required|numeric|min:0',
            //'service_charge_rate'         => 'required|numeric|min:0',
            'referral_bonus_rate'         => 'required|string',
            'referral_bonus_count'        => 'required|numeric|min:-1',
            'referral_bonus_release_time' => 'required|numeric|min:0',
            'min_interest_rate'           => 'required|numeric|min:0',
            'max_interest_rate'           => 'required|numeric|min:0',
            'interest_interval'           => 'required|numeric|min:0',
            'min_duration'                => 'required|numeric|min:0',
            'max_duration'                => 'required|numeric|min:0',
        ]);

        $input['is_active'] = $request->has('is_active');

        $package = Package::create($input);

        return [

            'status'   => true,
            'message'  => 'Investment package created.',
            'redirect' => $package->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Package $package
     * @return array
     */
    public function doUpdate(Request $request, Package $package): array
    {
        $input = $request->validate([
            'name'                        => 'required|max:55',
            'description'                 => 'required|max:400',
            'min_amount'                  => 'required|numeric|min:0',
            'max_amount'                  => 'required|numeric|min:0',
            'split_amount'                => 'required|numeric|min:0',
            'referral_bonus_rate'         => 'required|string',
            'referral_bonus_count'        => 'required|numeric|min:-1',
            'referral_bonus_release_time' => 'required|numeric|min:0',
            'min_interest_rate'           => 'required|numeric|min:0',
            'max_interest_rate'           => 'required|numeric|min:0',
            'interest_interval'           => 'required|numeric|min:0',
            'min_duration'                => 'required|numeric|min:0',
            'max_duration'                => 'required|numeric|min:0',
            //'service_charge_rate'         => 'required|numeric|min:0',
        ]);

        $input['is_active'] = $request->has('is_active');

        $package->update($input);

        return [

            'status'   => true,
            'message'  => 'Investment package created.',
            'redirect' => $package->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Package $package
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request, Package $package): array
    {
        return $this->doSetImage($request, $package);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $valid_actions = ['activate', 'deactivate', 'delete'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:core_packages,id',
        ]);

        $input    = $request->input();
        $packages = Package::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'activate':
                $affected = $packages->update(['is_active' => true]);
                break;
            case 'deactivate':
                $affected = $packages->update(['is_active' => false]);
                break;
            case 'delete':
                {
                    /**
                     * @var Package $package
                     */
                    foreach ($packages->get() as $package) {
                        if ($package->portfolios()->count()) {
                            return ['status' => false, 'message' => 'Can not delete a package with portfolios'];
                        }
                    }
                    $affected = $packages->delete();
                }
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
