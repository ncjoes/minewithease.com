<?php


namespace App\Http\Controllers\CMS;


use App\Http\Controllers\Controller;
use App\Models\CMS\Testimony;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TestimonyController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $statuses = Testimony::statuses();

        $search   = $request->get('search', '');
        $status   = $request->get('status', '');
        $per_page = $request->get('per_page', 50);

        $results = Testimony::query();
        $results = in_array($status, array_keys($statuses)) ? $results->where('status', $status) : $results;
        $results = strlen((string)$search) ? $results->where('name', 'LIKE', '%' . $search . '%') : $results;

        $net_count    = Testimony::count();
        $result_count = $results->count();
        $results      = $results->orderByDesc('id')->paginate($per_page);

        return view('cms-admin.testimony.manage', [
            'results'      => $results,
            'net_count'    => $net_count,
            'result_count' => $result_count,
            'statuses'     => $statuses,
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
        $valid_actions = array_keys(Testimony::statuses());
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:cms_testimonies,id',
        ]);

        $input    = $request->input();
        $items    = Testimony::whereIn('id', $input['ids']);
        $affected = 0;

        DB::beginTransaction();
        switch ($input['action']) {
            case Testimony::S_PUBLISHED:
                $affected = $items->update(['status' => Testimony::S_PUBLISHED]);
                break;
            case Testimony::S_PENDING:
                $affected = $items->update(['status' => Testimony::S_PENDING]);
                break;
        }
        DB::commit();

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => back()->getTargetUrl(),
        ];
    }
}