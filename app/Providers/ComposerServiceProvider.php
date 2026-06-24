<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\CMS\Category;
use App\Models\CMS\Page;
use App\Models\CMS\Post;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider
 * @package App\Providers
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.public', 'layouts.core.client', '*'], function ($view) {
            $pages = Page::published();
            $pageCategories = Category::where(['type' => Page::morphKey()])->orderBy('cardinality')->get();
            $postCategories = Category::where(['type' => Post::morphKey()])->orderBy('cardinality')->get();

            $view->with([
                'pages' => $pages,
                'pageCategories' => $pageCategories,
                'postCategories' => $postCategories,
            ]);
        });

        View::composer(['*'], function ($view) {
            $default_currency = Currency::getDefault();

            $view->with([
                //Org Details
                'org_name' => Setting::get(Setting::KEY_ORG_NAME),
                'org_tagline' => Setting::get(Setting::KEY_ORG_TAGLINE),
                'org_description' => Setting::get(Setting::KEY_ORG_DESCRIPTION),

                //Logos
                'headerLogoSetting' => Setting::find(Setting::KEY_SITE_LOGO_1),
                'footerLogoSetting' => Setting::find(Setting::KEY_SITE_LOGO_2),

                //Contact Details
                'contact_email' => Setting::get(Setting::KEY_CONTACT_EMAIL),
                'contact_phone' => Setting::get(Setting::KEY_CONTACT_PHONE),
                'contact_address' => Setting::get(Setting::KEY_CONTACT_ADDRESS),

                //Social Media Handles
                'contact_telegram' => Setting::get(Setting::KEY_HANDLE_TELEGRAM),
                'contact_facebook' => Setting::get(Setting::KEY_HANDLE_FACEBOOK),
                'contact_twitter' => Setting::get(Setting::KEY_HANDLE_TWITTER),
                'contact_youtube' => Setting::get(Setting::KEY_HANDLE_YOUTUBE),
                'contact_linkedIn' => Setting::get(Setting::KEY_HANDLE_LINKEDIN),
                'contact_instagram' => Setting::get(Setting::KEY_HANDLE_INSTAGRAM),

                //Misc.
                'livechat_service' => Setting::get(Setting::KEY_LIVECHAT_SERVICE),
                'livechat_key' => Setting::get(Setting::KEY_LIVECHAT_API_KEY),
                'g_analytics_id' => Setting::get(Setting::KEY_GOOGLE_ANALYTIC_ID),
                'enable_translation' => Setting::get(Setting::KEY_ENABLE_TRANSLATION, false),
                'DCS' => is_object($default_currency) ? $default_currency->symbol : '',
                'NOW' => Carbon::now(),
            ]);

            if (!Auth::guest()) {
                $user = request()->user();
                $twoFA_enabled = $user->getSetting(Setting::KEY_ENABLE_2FA);

                $view->with([
                    'user' => $user,
                    'is2fa_enabled' => $twoFA_enabled,
                ]);
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//
    }
}
