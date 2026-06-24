<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Page;
use App\Traits\Controllers\SetImage;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PageController
 * @package App\Http\Controllers\CMS
 */
class PageController extends Controller
{
    use SetImage;

    /**
     * @param Request $request
     * @param Page $page
     * @return Factory|View
     */
    public static function view(Request $request, Page $page)
    {
        abort_unless($page->isPublished(), 404);

        return view('cms-public.page-view', [
            'page' => $page,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('cms-admin.page.create', []);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $pages = Page::withTrashed()->orderBy('title')->paginate(15);

        return view('cms-admin.page.manage', [
            'all_pages' => $pages,
        ]);
    }

    /**
     * @param Request $request
     * @param Page $page
     * @return Factory|View
     */
    public function update(Request $request, Page $page)
    {
        return view('cms-admin.page.update', [
            'page' => $page,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request): array
    {
        $input = $request->validate([
            'title'       => 'required|max:255',
            'summary'     => 'nullable|min:10|max:1024',
            'content'     => 'required|max:16000',
            'cardinality' => 'required|numeric',
        ]);

        $input['slug']           = Page::makeUniqueSlug($input['title']);
        $input['is_published']   = $request->has('published');
        $input['show_in_menu']   = $request->has('show_in_menu');
        $input['show_in_footer'] = $request->has('show_in_footer');
        $page                    = Page::create($input);

        return [
            'status'   => true,
            'message'  => 'Page "' . $page->title . '" created.',
            'redirect' => $page->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Page $page
     * @return array
     */
    public function doUpdate(Request $request, Page $page): array
    {
        $input = $request->validate([
            'title'       => 'required|max:255',
            'summary'     => 'nullable|min:10|max:1024',
            'content'     => 'required|max:16000',
            'cardinality' => 'required|numeric',
        ]);

        $input['slug']           = $page->system_defined ? $page->slug : Page::makeUniqueSlug($input['title']);
        $input['is_published']   = $request->has('published');
        $input['show_in_menu']   = $request->has('show_in_menu');
        $input['show_in_footer'] = $request->has('show_in_footer');

        $page->update(Arr::except($input, ['category', 'published']));

        return [
            'status'   => true,
            'message'  => 'Page "' . $page->title . '" updated.',
            'redirect' => $page->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Page $page
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request, Page $page)
    {
        return $this->doSetImage($request, $page);
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
            'ids.*'  => 'exists:cms_pages,id',
        ]);

        $input    = $request->input();
        $pages    = Page::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'trash':
                $affected = $pages->delete();
                break;
            case 'recycle':
                $affected = $pages->restore();
                break;
            case 'delete':
                $affected = $pages->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
