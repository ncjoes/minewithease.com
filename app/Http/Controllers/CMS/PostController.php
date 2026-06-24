<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\CMS\Category;
use App\Models\CMS\Post;
use App\Traits\Controllers\SetImage;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Class PostController
 * @property mixed users
 * @package App\Http\Controllers\CMS
 */
class PostController extends Controller
{
    use SetImage;

    /**
     * @param Request $request
     * @param string|null $category_slug
     * @return Factory|View
     */
    public function index(Request $request, $category_slug = null)
    {
        $category = Category::findBySlug($category_slug);
        /**
         * @var Category $category
         */
        if (is_object($category)) {
            $posts = $category->publishedPosts()->orderByDesc('published_at')->paginate(6);
        } elseif (is_null($category_slug)) {
            $posts = Post::recent(6, 6);
        } else {
            abort(404);
        }
        $post_categories = Category::where('type', Post::morphKey())->orderBy('cardinality')->get();

        return view('cms-public.post-index', [
            'posts' => $posts,
            'category' => $category,
            'post_categories' => $post_categories,
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Factory|View
     */
    public function view(Request $request, Post $post)
    {
        abort_unless($post->isPublished(), 404);

        $category = $post->category;
        $recent_posts = Post::where('id', '!=', $post->id)->where('is_published', true)->orderByDesc('published_at')->limit(2)->get();

        return view('cms-public.post-view', [
            'post' => $post,
            'category' => $category,
            'recent_posts' => $recent_posts,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $editor_role = Role::findByName(Role::EDITOR);
        $editors     = $editor_role->users;
        $categories  = Category::findByColumn('type', Category::TYPE_POST)->get();

        return view('cms-admin.post.create', [
            'editors'    => $editors,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $posts = Post::withTrashed()->orderByDesc('id')->get();

        return view('cms-admin.post.manage', [
            'posts' => $posts,
        ]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Factory|View
     */
    public function update(Request $request, Post $post)
    {
        $editor_role = Role::findByName(Role::EDITOR);
        $editors     = $editor_role->users;
        $categories  = Category::findByColumn('type', Category::TYPE_POST)->get();

        return view('cms-admin.post.update', [
            'post'       => $post,
            'editors'    => $editors,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request): array
    {
        $this->validate($request, [
            'title'        => 'required|max:255',
            'slug'         => 'required|max:180|alpha_dash|unique:cms_posts,slug',
            'summary'      => 'nullable|min:10|max:1024',
            'content'      => 'required|max:16000',
            'authors'      => 'required|array|min:1',
            'authors.*'    => 'exists:auth_users,id',
            'categories'   => 'required|array|min:1',
            'categories.*' => 'exists:cms_categories,id',
            'published_at' => 'required|date',
        ]);

        $input                 = $request->input();
        $input['published_at'] = Carbon::parse($input['published_at']);
        /**
         * @var Post $post
         */
        $post = Post::create(Arr::except($input, ['authors', 'categories']));
        $post->categories()->attach($input['categories']);
        $post->authors()->attach($input['authors']);

        return [
            'status'   => true,
            'message'  => 'Post "' . $post->title . '" created.',
            'redirect' => $post->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Post $post): array
    {
        $this->validate($request, [
            'title'        => 'required|max:255',
            'slug'         => 'required|max:180|alpha_dash|unique:cms_posts,slug,' . $post->id,
            'summary'      => 'nullable|min:10|max:1024',
            'content'      => 'required|max:16000',
            'authors'      => 'required|array|min:1',
            'authors.*'    => 'exists:auth_users,id',
            'categories'   => 'required|array|min:1',
            'categories.*' => 'exists:cms_categories,id',
            'published_at' => 'required|date',
        ]);

        $input                 = $request->input();
        $input['published_at'] = Carbon::parse($input['published_at']);
        $input['is_published'] = $request->has('published');

        $post->update(Arr::except($input, ['authors', 'categories']));
        $post->categories()->sync($input['categories']);
        $post->authors()->sync($input['authors']);

        return [
            'status'   => true,
            'message'  => 'Post "' . $post->title . '" updated.',
            'redirect' => $post->edit_url,
        ];
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return array
     * @throws Exception
     */
    public function setImage(Request $request, Post $post)
    {
        return $this->doSetImage($request, $post);
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
            'ids.*'  => 'exists:cms_posts,id',
        ]);

        $input    = $request->input();
        $posts    = Post::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'trash':
                $affected = $posts->delete();
                break;
            case 'recycle':
                $affected = $posts->restore();
                break;
            case 'delete':
                $affected = $posts->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected . ' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
