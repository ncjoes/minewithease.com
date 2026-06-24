<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SettingsTableSeeder
 */
class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $existing = app()->isLocal() ? [] : Setting::pluck('value', 'key')->all();
        Setting::query()->delete();

        $app_url = config('app.url');

        //Setting Groups
        $GROUP_LOGOS          = '0_Website_Logos';
        $GROUP_COMPANY_INFO   = '1_company_information';
        $GROUP_SYSTEM_CONFIG  = '2_system_configurations';
        $GROUP_ACCOUNT_CONFIG = '3_default_account_settings';

        /**
         * @group Website Logos.
         */
        Setting::create([
            'group'            => $GROUP_LOGOS,
            'key'              => $attr = Setting::KEY_SITE_LOGO_1,
            'value'            => null,
            'default'          => null,
            'form_type'        => 'image',
            'form_options'     => [
                'width'  => $w = 324,
                'height' => $h = 128,
            ],
            'form_label'       => $attr . ' (' . $w . 'x' . $h . ' px)',
            'form_placeholder' => '',
            'required'         => true,
            'validation_rules' => '',
            'cardinality'      => 1,
        ]);

        Setting::create([
            'group'            => $GROUP_LOGOS,
            'key'              => $attr = Setting::KEY_SITE_LOGO_2,
            'value'            => null,
            'default'          => null,
            'form_type'        => 'image',
            'form_options'     => [
                'width'  => $w = 324,
                'height' => $h = 324,
            ],
            'form_label'       => $attr . ' (' . $w . 'x' . $h . ' px)',
            'form_placeholder' => '',
            'required'         => true,
            'validation_rules' => '',
            'cardinality'      => 2,
        ]);

        /**
         * @group Company/Org. information
         */
        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_ORG_NAME,
            'value'            => env('APP_NAME'),
            'default'          => env('APP_NAME'),
            'form_type'        => 'text', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Org. Name',
            'form_placeholder' => env('APP_NAME'),
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|max:64',
            'cardinality'      => 1,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_ORG_TAGLINE,
            'value'            => env('APP_DESCRIPTION'),
            'default'          => env('APP_DESCRIPTION'),
            'form_type'        => 'text', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Org. Tagline',
            'form_placeholder' => '...',
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|max:255',
            'cardinality'      => 2,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_ORG_DESCRIPTION,
            'value'            => env('APP_DESCRIPTION'),
            'default'          => env('APP_DESCRIPTION'),
            'form_type'        => 'textarea', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Org. Description',
            'form_placeholder' => '...',
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|max:255',
            'cardinality'      => 3,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_CONTACT_ADDRESS,
            'value'            => $addr = fake()->address,
            'default'          => 'Somewhere in USA',
            'form_type'        => 'text',
            'form_label'       => 'Contact Address',
            'form_placeholder' => $addr,
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|max:255',
            'cardinality'      => 4,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_CONTACT_EMAIL,
            'value'            => 'info@domain.test',
            'default'          => 'info@domain.test',
            'form_type'        => 'email', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Contact Email',
            'form_placeholder' => 'info@domain.test',
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|max:255|email',
            'cardinality'      => 5,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_FINANCE_EMAIL,
            'value'            => 'info@domain.test',
            'default'          => 'info@domain.test',
            'form_type'        => 'email', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Finance Email',
            'form_placeholder' => 'info@domain.test',
            'form_options'     => [],
            'description'      => 'Email address for receiving notifications on financial activities.',
            'required'         => true,
            'validation_rules' => 'required|max:255|email',
            'cardinality'      => 6,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_CONTACT_PHONE,
            'value'            => '+44#########',
            'default'          => '+44#########',
            'form_type'        => 'tel',
            'form_label'       => 'Contact Phone',
            'form_placeholder' => '+44#########',
            'form_options'     => [],
            'required'         => true,
            'validation_rules' => 'required|string',
            'cardinality'      => 7,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_TELEGRAM,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'Telegram Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 8,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_FACEBOOK,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'Facebook Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 9,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_INSTAGRAM,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'Instagram Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 10,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_LINKEDIN,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'LinkedIn Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 11,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_TWITTER,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'Twitter Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 11,
        ]);

        Setting::create([
            'group'            => $GROUP_COMPANY_INFO,
            'key'              => Setting::KEY_HANDLE_YOUTUBE,
            'value'            => $app_url,
            'default'          => $app_url,
            'form_type'        => 'text',
            'form_label'       => 'Youtube Handle',
            'form_placeholder' => $app_url,
            'form_options'     => [],
            'required'         => false,
            'validation_rules' => 'nullable|url',
            'cardinality'      => 12,
        ]);


        /**
         * @group System Config
         */
        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_DEFAULT_CURRENCY,
            'value'            => Currency::DEFAULT,
            'default'          => Currency::DEFAULT,
            'form_type'        => 'select', //text, textarea, select
            'form_label'       => 'Default Currency',
            'form_placeholder' => '',
            'form_options'     => Currency::pluck('name', 'alpha_code')->all(),
            'description'      => 'This is the base for Investment Packages and exchange rate conversions.' .
                'NB: changing the value of this setting after initial setup may lead to unpredictable behaviors and inaccuracies',
            'required'         => true,
            'validation_rules' => 'required|exists:etc_currencies,alpha_code',
            'cardinality'      => 0,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_ENABLE_TRANSLATION,
            'value'            => (int)!app()->isLocal(),
            'default'          => 1,
            'form_type'        => 'boolean', //input, textarea, select
            'form_label'       => 'Enable Translation',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 1,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_LIVECHAT_SERVICE,
            'value'            => '',
            'default'          => '',
            'form_type'        => 'select', //input, textarea, select, boolean, options
            'form_label'       => 'Livechat Service',
            'form_placeholder' => '',
            'form_options'     => $services = [
                ''         => 'None (disable livechat)',
                'livechat' => 'Livechat.com  (web and mobile app support)',
                'tawk-to'  => 'Tawk.to (web and mobile app support)',
                'jivochat' => 'jivochat.com (web and mobile app support)',
            ],
            'required'         => false,
            'validation_rules' => 'nullable|in:' . implode(',', array_keys($services)),
            'cardinality'      => 2,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_LIVECHAT_API_KEY,
            'value'            => '',
            'default'          => '',
            'form_type'        => 'text', //text, textarea, select
            'form_label'       => 'Livechat API Key',
            'form_placeholder' => '',
            'required'         => false,
            'validation_rules' => 'nullable|max:255',
            'cardinality'      => 3,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_GOOGLE_ANALYTIC_ID,
            'value'            => '',
            'default'          => '',
            'form_type'        => 'text', //text, textarea, select
            'form_label'       => 'Google Analytics API Key',
            'form_placeholder' => 'UA-...',
            'required'         => false,
            'validation_rules' => 'nullable|max:255',
            'cardinality'      => 4,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_ENABLE_BROADCASTING,
            'value'            => (int)!app()->isLocal(),
            'default'          => 1,
            'form_type'        => 'boolean', //input, textarea, select
            'form_label'       => 'Enable Broadcasting',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 5,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_ENABLE_NOTIFICATIONS,
            'value'            => (int)!app()->isLocal(),
            'default'          => 1,
            'form_type'        => 'boolean', //input, textarea, select
            'form_label'       => 'Enable Notifications',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 6,
            'admin_only'       => false,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_INVOICE_MAX_VALIDITY,
            'value'            => 45,
            'default'          => 45,
            'form_type'        => 'number', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Pending Invoice Validity (in minutes)',
            'form_placeholder' => '45',
            'required'         => true,
            'validation_rules' => 'required|numeric|min:1',
            'cardinality'      => 7,
        ]);

        Setting::create([
            'group'            => $GROUP_SYSTEM_CONFIG,
            'key'              => Setting::KEY_INVOICE_REMINDER_INTERVAL,
            'value'            => 30,
            'default'          => 30,
            'form_type'        => 'number', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Pending Invoice Reminder Interval (in minutes)',
            'form_placeholder' => '30',
            'required'         => true,
            'validation_rules' => 'required|numeric|min:1',
            'cardinality'      => 8,
        ]);


        /**
         * @group Account Config
         */
        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_REQUIRE_EMAIL_VERIFICATION,
            'value'            => (int)!app()->isLocal(),
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Require Email Verification',
            'form_placeholder' => '',
            'form_options'     => [0 => 'No', 1 => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 0,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_REQUIRE_PHONE_VERIFICATION,
            'value'            => 0,
            'default'          => 0,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Require Phone Verification',
            'form_placeholder' => '',
            'form_options'     => [0 => 'No', 1 => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 1,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_REQUIRE_PROFILE_VERIFICATION,
            'value'            => 1,
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Require Profile Verification (KYC)',
            'form_placeholder' => '',
            'form_options'     => [0 => 'No', 1 => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 2,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_TRIAL_AMOUNT,
            'value'            => 0,
            'default'          => 0,
            'form_type'        => 'number', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Trial Account Starting Balance',
            'form_placeholder' => '10000',
            'required'         => true,
            'validation_rules' => 'required|numeric|min:0',
            'cardinality'      => 3,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_ENABLE_2FA,
            'value'            => 0,
            'default'          => 0,
            'form_type'        => 'boolean', //input, textarea, select
            'form_label'       => 'Enable 2 Factor Authentication',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 4,
            'admin_only'       => false,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_ALLOW_REFERRALS,
            'value'            => 1,
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Allow Referrals',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 5,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_REQUIRE_BONUS_APPROVAL,
            'value'            => 0,
            'default'          => 0,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Require Bonus Approvals',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 6,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_ALLOW_INVESTING,
            'value'            => 1,
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Allow Investing',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 7,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_AUTO_REINVEST,
            'value'            => (int)app()->isLocal(),
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Auto Reinvest Capital',
            'form_placeholder' => '',
            'description'      => 'This is only applicable when client is allowed to invest.',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 8,
            'admin_only'       => false,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_ALLOW_WITHDRAWALS,
            'value'            => 1,
            'default'          => 1,
            'form_type'        => 'boolean', //text, textarea, select
            'form_label'       => 'Allow Withdrawals',
            'form_placeholder' => '',
            'form_options'     => ['0' => 'No', '1' => 'Yes'],
            'required'         => true,
            'validation_rules' => 'required|in:0,1',
            'cardinality'      => 9,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_WITHDRAWAL_INTERVAL,
            'value'            => 28,
            'default'          => 28,
            'form_type'        => 'number', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Days b/w Completed Withdrawals',
            'form_placeholder' => '28 days',
            'required'         => true,
            'validation_rules' => 'required|numeric|min:0',
            'cardinality'      => 10,
        ]);

        Setting::create([
            'group'            => $GROUP_ACCOUNT_CONFIG,
            'key'              => Setting::KEY_WITHDRAWAL_LIMIT,
            'value'            => 100,
            'default'          => 100,
            'form_type'        => 'number', //text, textarea, checkbox (1||0), select
            'form_label'       => 'Max Amount per Withdrawal',
            'form_placeholder' => '$100',
            'required'         => true,
            'validation_rules' => 'required|numeric|min:0',
            'cardinality'      => 11,
        ]);

        foreach ($existing as $key => $value) {
            Setting::set($key, $value);
        }

        DB::commit();
    }
}
