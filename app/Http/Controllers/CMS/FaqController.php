<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Category;
use App\Models\CMS\Faq;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class FaqController
 * @package App\Http\Controllers\CMS
 */
class FaqController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::findByColumn('type', Faq::morphKey())->orderByDesc('cardinality')->get();

        return view('cms-public.faq-index', [
            'faq_categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $categories = Category::findByColumn('type', Faq::morphKey())->get();

        return view('cms-admin.faq.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $faqs = Faq::withTrashed()->join('cms_categories', 'cms_faqs.category_id', '=', 'cms_categories.id')
                   ->select('cms_faqs.*', 'cms_categories.cardinality')
                   ->orderByDesc('cms_categories.cardinality')->orderByDesc('cms_faqs.cardinality')->paginate(20);

        return view('cms-admin.faq.manage', [
            'faqs' => $faqs,
        ]);
    }

    /**
     * @param Request $request
     * @param Faq $faq
     * @return Factory|View
     */
    public function update(Request $request, Faq $faq)
    {
        $categories = Category::findByColumn('type', Faq::morphKey())->get();

        return view('cms-admin.faq.update', [
            'faq'        => $faq,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request)
    {
        $this->validate($request, [
            'question'    => 'required|max:400',
            'answer'      => 'required|max:1000',
            'cardinality' => 'required|numeric',
        ]);

        $input                 = $request->input();
        $input['is_published'] = $request->has('published');
        $faq                   = Faq::create($input);

        return [
            'status'   => true,
            'message'  => 'FAQ added successfully.',
            'redirect' => route('cms.admin.faq.manage'),
        ];
    }

    /**
     * @param Request $request
     * @param Faq $faq
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Faq $faq)
    {
        $this->validate($request, [
            'question'    => 'required|max:400',
            'answer'      => 'required|max:1000',
            'cardinality' => 'required|numeric',
        ]);

        $input                 = $request->input();
        $input['is_published'] = $request->has('published');
        $faq->update($input);

        return [
            'status'   => true,
            'message'  => 'FAQ updated.',
            'redirect' => route('cms.admin.faq.manage'),
        ];
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
            'ids.*'  => 'exists:cms_faqs,id',
        ]);

        $input    = $request->input();
        $faqs     = Faq::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'trash':
                $affected = $faqs->delete();
                break;
            case 'recycle':
                $affected = $faqs->restore();
                break;
            case 'delete':
                $affected = $faqs->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
