<?php
declare(strict_types=1);

namespace App\Models\ETC;

use App\Interfaces\HasImageAttributes;
use App\Models\Model;
use App\Traits\Models\HasImageAttribute;

/**
 * Class Setting
 * @property mixed form_type
 * @property mixed value
 * @package App\Models\ETC
 * @method static create(array $array)
 */
class Setting extends Model implements HasImageAttributes
{
    use HasImageAttribute;

    const KEY_SITE_LOGO_1      = 'header_logo';
    const KEY_SITE_LOGO_2      = 'footer_logo';
    const KEY_ORG_NAME         = 'org_name';
    const KEY_ORG_TAGLINE      = 'org_tagline';
    const KEY_ORG_DESCRIPTION  = 'org_description';
    const KEY_CONTACT_ADDRESS  = 'contact_address';
    const KEY_CONTACT_EMAIL    = 'contact_email';
    const KEY_FINANCE_EMAIL    = 'finance_email';
    const KEY_CONTACT_PHONE    = 'contact_phone';
    const KEY_HANDLE_TELEGRAM  = 'contact_telegram';
    const KEY_HANDLE_FACEBOOK  = 'contact_facebook';
    const KEY_HANDLE_TWITTER   = 'contact_twitter';
    const KEY_HANDLE_LINKEDIN  = 'contact_linkedin';
    const KEY_HANDLE_INSTAGRAM = 'contact_instagram';
    const KEY_HANDLE_YOUTUBE   = 'contact_youtube';

    const KEY_DEFAULT_CURRENCY          = 'default_currency';
    const KEY_ENABLE_TRANSLATION        = 'enable_translation';
    const KEY_LIVECHAT_SERVICE          = 'livechat_service';
    const KEY_LIVECHAT_API_KEY          = 'livechat_api_key';
    const KEY_GOOGLE_ANALYTIC_ID        = 'google_analytics_id';
    const KEY_ENABLE_BROADCASTING       = 'enable_broadcasting';
    const KEY_ENABLE_NOTIFICATIONS      = 'enable_notifications';
    const KEY_INVOICE_MAX_VALIDITY      = 'invoice_max_validity';
    const KEY_INVOICE_REMINDER_INTERVAL = 'invoice_reminder_interval';


    const KEY_REQUIRE_EMAIL_VERIFICATION   = 'require_email_verification';
    const KEY_REQUIRE_PHONE_VERIFICATION   = 'require_phone_verification';
    const KEY_REQUIRE_PROFILE_VERIFICATION = 'require_profile_verification';
    const KEY_TRIAL_AMOUNT                 = 'trial_amount';
    const KEY_ENABLE_2FA                   = 'enable_2fa';
    const KEY_ALLOW_REFERRALS              = 'allow_referrals';
    const KEY_REQUIRE_BONUS_APPROVAL       = 'require_bonus_approvals';
    const KEY_ALLOW_INVESTING              = 'allow_investing';
    const KEY_AUTO_REINVEST                = 'auto_reinvest';
    const KEY_ALLOW_WITHDRAWALS            = 'allow_withdrawal';
    const KEY_WITHDRAWAL_INTERVAL          = 'withdrawal_interval';
    const KEY_WITHDRAWAL_LIMIT             = 'withdrawal_limit';


    protected $primaryKey = 'key';

    protected $fillable = [
        'key',
        'group',
        'value',
        'default',

        /**
         * @example text,number,email,password,tel, textarea, select,boolean, options
         */
        'form_type',
        'form_label',
        'form_placeholder',

        /**
         * @example []; not empty for form_types(select,boolean,options)
         */
        'form_options',
        'required',
        'validation_rules',
        'description',
        'cardinality',
    ];

    protected $casts = [
        'form_options' => 'array',
        'required'     => 'boolean',
    ];
    protected $table = 'etc_settings';

    /**
     * @param $key
     * @param null $default
     * @return array|bool|false|mixed|string|null
     */
    public static function get($key, $default = null)
    {
        $obj = self::find($key);

        return is_object($obj) ? self::parseValue($obj) : $default;
    }

    /**
     * @param Setting $object
     * @param string $value
     * @param bool $forSet
     * @return array|bool|false|mixed|string
     */
    public static function parseValue(self $object, $value = '', $forSet = false)
    {
        $value = $forSet ? $value : $object->value;
        if ($object->form_type == 'boolean') {
            $value = (boolean)($forSet ? $value : $object->value);
        } elseif (in_array($object->form_type, ['options', 'json'])) {
            if ($forSet) {
                $value = is_json($value) ? $value : json_encode($value, JSON_FORCE_OBJECT);
            } else {
                $value = is_array($value) ? $value : json_decode($value, true);
            }
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        /**
         * @var self $obj
         */
        $obj = self::findOrFail($key);

        if (is_object($obj)) {
            $obj->update(['value' => static::parseValue($obj, $value, true)]);
        }
    }

    /**
     * @return array|bool|false|mixed|string
     */
    public function getValue()
    {
        return self::parseValue($this);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->attributes['key'];
    }

    public static function imageDir($attribute): string
    {
        return 'public' . DS . 'settings' . DS . $attribute;
    }

    public static function defaultImage($attribute): string
    {
        return 'brand.png';
    }

}
