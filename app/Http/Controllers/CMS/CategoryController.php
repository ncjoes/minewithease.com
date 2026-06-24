<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\CMS\Category;
use App\Models\CMS\Faq;
use App\Models\CMS\Page;
use App\Models\CMS\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class CategoryController
 * @package App\Http\Controllers\CMS
 */
class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        return view('cms-admin.category.create', [
            'types' => [
                Faq::morphKey()  => 'FAQ',
                Post::morphKey() => 'Post',
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $categories = Category::withTrashed()->paginate(15);

        return view('cms-admin.category.manage', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return Factory|View
     */
    public function update(Request $request, Category $category)
    {
        return view('cms-admin.category.update', [
            'category' => $category,
            'types'    => [
                Faq::morphKey()  => 'FAQ',
                Post::morphKey() => 'Post',
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function doCreate(Request $request): array
    {
        $input = $request->validate([
            'type'        => 'required|in:' . Faq::morphKey() . ',' . Page::morphKey(),
            'title'       => 'required|max:255|unique:cms_categories,title',
            'description' => 'required|max:400',
            'cardinality' => 'required|numeric',
        ]);

        $input['slug']           = Category::makeUniqueSlug($input['title']);
        $input['show_in_menu']   = $request->has('show_in_menu');
        $input['show_in_footer'] = $request->has('show_in_footer');
        $input['use_index']      = $request->has('use_index');

        $category = Category::create($input);

        return [
            'status'   => true,
            'message'  => 'Category "' . $category->title . '" created.',
            'redirect' => $category->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Category $category): array
    {
        $input = $request->validate([
            'type'        => 'required|in:' . Faq::morphKey() . ',' . Page::morphKey(),
            'title'       => 'required|max:255|unique:cms_categories,title,' . $category->id,
            'description' => 'required|max:400',
            'cardinality' => 'required|numeric',
        ]);

        $input['show_in_menu']   = $request->has('show_in_menu');
        $input['show_in_footer'] = $request->has('show_in_footer');
        $input['use_index']      = $request->has('use_index');

        $category->update($input);

        return [
            'status'   => true,
            'message'  => 'Category updated.',
            'redirect' => route('cms.admin.category.manage'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request): array
    {
        $valid_actions = ['trash', 'recycle', 'delete'];
        $this->validate($request, [
            'action' => 'required|in:' . implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:cms_categories,id',
        ]);

        $input      = $request->input();
        $categories = Category::whereIn('id', $input['ids']);
        $affected   = 0;

        switch ($input['action']) {
            case 'trash':
                $affected = $categories->delete();
                break;
            case 'recycle':
                $affected = $categories->restore();
                break;
            case 'delete':
                $affected = $categories->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
