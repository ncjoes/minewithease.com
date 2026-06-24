<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Channel;
use App\Models\ETC\Currency;
use App\Traits\Controllers\SetImage;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class ChannelController
 * @package App\Http\Controllers\Core\Admin
 */
class ChannelController extends Controller
{
    use SetImage;

    private static $validationRules = [];

    public function __construct()
    {
        $currencies            = Currency::activeOnly()->pluck('id')->all();
        self::$validationRules = [
            'currency'       => 'required|in:' . implode(',', $currencies),
            'name'           => 'required|max:55',
            'website'        => 'nullable|url',
            'min_amount'     => 'required|numeric|min:0',
            'max_amount'     => 'required|numeric|min:0',
            'split_amount'   => 'required|numeric|min:0',
            'description'    => 'required|max:400',
            'payment_wallet' => 'required|max:256',
        ];
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $currencies = Currency::activeOnly()->orderBy('name')->get();

        return view('core-admin.channel.create', [
            'currencies' => $currencies,
        ]);
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return Factory|View
     */
    public function update(Request $request, Channel $channel)
    {
        $currencies = Currency::activeOnly()->orderBy('name')->get();

        return view('core-admin.channel.update', [
            'currencies' => $currencies,
            'channel'    => $channel,
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
        $currency = Currency::findByColumn('alpha_code', $request->get('currency'))->first();

        $currencies = Currency::activeOnly()->get();
        $results    = Channel::where('is_active', $status);
        $results    = is_object($currency) ? $results->where('currency_id', $currency->id) : $results;
        $results    = strlen((string)$search) ? $results->where('name', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Channel::count();
        $result_count = $results->count();
        $results      = $results->orderByDesc('rank')->paginate($per_page);

        return view('core-admin.channel.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'currencies'   => $currencies,
            'statuses'     => [
                1 => 'Yes',
                0 => 'No',
            ],
            'filter'       => [
                'search'   => $search,
                'status'   => $status,
                'currency' => is_object($currency) ? $currency->alpha_code : '',
                'per_page' => $per_page,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request): array
    {
        $this->validate($request, self::$validationRules);

        $channel = Channel::create($this->parseFields($request));

        return [
            'status'   => true,
            'message'  => 'Payment channel created.',
            'redirect' => $channel->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @return array|string|null
     */
    private function parseFields(Request $request)
    {
        $input                = $request->input();
        $input['currency_id'] = $input['currency'];
        $input['is_active']   = $request->has('is_active');
        $input['for_inflow']  = $request->has('for_inflow');
        $input['for_outflow'] = $request->has('for_outflow');

        return $input;
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Channel $channel): array
    {
        $this->validate($request, self::$validationRules);

        $channel->update($this->parseFields($request));

        return [
            'status'   => true,
            'message'  => 'Payment channel updated.',
            'redirect' => $channel->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Channel $channel
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request, Channel $channel)
    {
        return $this->doSetImage($request, $channel);
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
            'ids.*'  => 'exists:core_channels,id',
        ]);

        $input    = $request->input();
        $channels = Channel::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'activate':
                $affected = $channels->update(['is_active' => true]);
                break;
            case 'deactivate':
                $affected = $channels->update(['is_active' => false]);
                break;
            case 'delete':
                $affected = $channels->delete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
