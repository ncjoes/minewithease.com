<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Slide;
use App\Traits\Controllers\SetImage;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class SlideController
 * @package App\Http\Controllers\CMS
 */
class SlideController extends Controller
{
    use SetImage;

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('cms-admin.slide.create');
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $slides = Slide::withTrashed()->orderBy('cardinality')->get();

        return view('cms-admin.slide.manage', [
            'slides' => $slides,
        ]);
    }

    /**
     * @param Request $request
     * @param Slide $slide
     * @return Factory|View
     */
    public function update(Request $request, Slide $slide)
    {
        return view('cms-admin.slide.update', [
            'slide' => $slide,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request)
    {
        $this->validate($request, $v_rules = [
            'title'        => 'required|max:60',
            'description'  => 'nullable|max:180',
            'action_url'   => 'nullable|url',
            'action_label' => 'nullable|max:25',
            'cardinality'  => 'required|numeric',
        ]);

        $input = $request->input();
        /**
         * @var Slide $slide
         */
        $slide = Slide::create(Arr::only($input, array_keys($v_rules)));

        return [
            'status'   => true,
            'message'  => 'New slide created.',
            'redirect' => $slide->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Slide $slide
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Slide $slide)
    {
        $this->validate($request, $v_rules = [
            'title'        => 'required|max:60',
            'description'  => 'nullable|max:180',
            'action_url'   => 'nullable|url',
            'action_label' => 'nullable|max:25',
            'cardinality'  => 'required|numeric',
        ]);

        $input = $request->input();

        $slide->update(Arr::only($input, array_keys($v_rules)));

        return [
            'status'   => true,
            'message'  => 'Slide updates updated.',
            'redirect' => route('cms.admin.slide.manage'),
        ];
    }

    /**
     * @param Request $request
     * @param Slide $slide
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request, Slide $slide)
    {
        return $this->doSetImage($request, $slide);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request)
    {
        $valid_actions = ['trash', 'recycle', 'delete'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:cms_slides,id',
        ]);

        $input    = $request->input();
        $slides   = Slide::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'trash':
                $affected = $slides->delete();
                break;
            case 'recycle':
                $affected = $slides->restore();
                break;
            case 'delete':
                $affected = $slides->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
