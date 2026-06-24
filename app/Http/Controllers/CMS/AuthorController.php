<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Auth\Role;
use App\Models\CMS\Author;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Tightenco\Collect\Support\Arr;

/**
 * Class AuthorController
 * @package App\Http\Controllers\CMS
 */
class AuthorController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $roles = Role::all();

        return view('cms.author.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        $author_role = Role::findByName(Role::EDITOR);
        $authors = Author::withTrashed()->whereIn('id', $author_role->users()->pluck('id'))->get();

        return view('cms.author.manage', [
            'authors' => $authors,
        ]);
    }

    /**
     * @param Request $request
     * @param Author $author
     * @return Factory|View
     */
    public function update(Request $request, Author $author)
    {
        $roles = Role::all();

        return view('cms.author.update', [
            'roles'  => $roles,
            'author' => $author,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doCreate(Request $request)
    {
        $email = $request->has('email') ? $request->input('email') : null;
        $arr = ['email' => normalize_email($email)];
        $this->validate($request->merge($arr), [
            'email'      => 'required|email|unique:auth_users,email',
            'phone'      => 'required|unique:auth_users,phone',
            'first_name' => 'required|alpha',
            'last_name'  => 'required|alpha',
            'roles'      => 'required|array',
            'roles.*'    => 'exists:auth_roles,id'
        ]);

        $tm_pswd              = Str::random('6');
        $input                = $request->input();
        $names                = $input['first_name'].'-'.$input['last_name'];
        $input['slug']        = Author::makeUniqueSlug($names);
        $input['status']      = Author::S_PENDING;
        $input['password']    = bcrypt($tm_pswd);
        $input['email_token'] = $input['phone_token'] = [];

        /**
         * @var Author $author
         */
        $author = Author::create(Arr::except($input, ['roles']));
        $author->roles()->attach($input['roles']);

        //ToDo -- send welcome mail to new author with their default password

        return [
            'status'   => true,
            'message'  => $names.' has been invited successfully.',
            'redirect' => route('cms.admin.author.manage'),
        ];
    }

    /**
     * @param Request $request
     * @param Author $author
     * @return array
     * @throws ValidationException
     */
    public function doUpdate(Request $request, Author $author)
    {
        $email = $request->has('email') ? $request->input('email') : null;
        $arr = ['email' => normalize_email($email)];
        $this->validate($request->merge($arr), [
            'email'      => 'required|email|unique:auth_users,email,'.$author->id,
            'phone'      => 'required|unique:auth_users,phone,'.$author->id,
            'first_name' => 'required|alpha',
            'last_name'  => 'required|alpha',
            'biography'  => 'required|max:2000',
            'roles'      => 'required|array',
            'roles.*'    => 'exists:auth_roles,id'
        ]);

        $input = $request->input();

        $author_role = Role::findByName(Role::EDITOR);
        if (!in_array($author_role->id, $input['roles']) and $author_role->users()->count() < 2) {
            return [
                'status'  => false,
                'message' => 'You can not remove the only remaining content editor.'
            ];
        }

        $names = $input['first_name'].'-'.$input['last_name'];
        $new_slug = Author::makeUniqueSlug($names);
        if ($new_slug != $author->slug) {
            $input['slug'] = $new_slug;
            //ToDo -- create view page for authors
            //ToDo -- create redirect when the view url changes
        }

        $author->update(Arr::except($input, ['roles']));
        $author->roles()->sync($input['roles']);

        //ToDo -- send welcome mail to new author with their default password

        return [
            'status'   => true,
            'message'  => $names.' has been updated successfully.',
            'redirect' => route('cms.admin.author.manage'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function doManage(Request $request)
    {
        $valid_actions = ['resign', 'reinstate', 'trash', 'recycle', 'delete'];
        $this->validate($request, [
            'action' => 'required|in:'.implode(',', $valid_actions),
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:auth_users,id',
        ]);

        $input = $request->input();
        $authors = Author::whereIn('id', $input['ids']);
        $affected = 0;

        switch ($input['action']) {
            case 'resign':
                $affected = $authors->update(['status' => Author::S_DEACTIVATED]);
                break;
            case 'reinstate':
                $affected = $authors->update(['status' => Author::S_ACTIVATED]);
                break;
            case 'trash':
                $affected = $authors->delete();
                break;
            case 'recycle':
                $affected = $authors->restore();
                break;
            case 'delete':
                $affected = $authors->forceDelete();
                break;
        }

        return [
            'mode'     => 'info',
            'message'  => $affected.' records affected.',
            'redirect' => redirect()->back()->getTargetUrl(),
        ];
    }
}
