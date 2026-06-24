<?php

declare(strict_types=1);

namespace App\Models\Auth;

use App\Interfaces\HasImageAttributes;
use App\Models\Core\Account;
use App\Models\Core\Bonus;
use App\Models\Core\Card;
use App\Models\Core\Deposit;
use App\Models\Core\Package;
use App\Models\Core\Portfolio;
use App\Models\Core\Swap;
use App\Models\Core\Transaction;
use App\Models\Core\Withdrawal;
use App\Models\ETC\Country;
use App\Models\ETC\Currency;
use App\Models\ETC\Setting;
use App\Notifications\ResetPassword;
use App\Traits\Controllers\ResolveExchangeRate;
use App\Traits\Models\AuthByPermission;
use App\Traits\Models\AuthByRole;
use App\Traits\Models\FindByEmail;
use App\Traits\Models\FindByPhone;
use App\Traits\Models\FindByStatus;
use App\Traits\Models\FindByUUID;
use App\Traits\Models\HasImageAttribute;
use App\Traits\Models\HasName;
use App\Traits\Models\HasSettings;
use App\Traits\Models\ModelHelpers;
use Carbon\Carbon;
use Database\Factories\Auth\UserFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Base;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @property float balance
 * @property int id
 * @property User referrer
 * @property int status
 * @property Country country
 * @property string uuid
 * @property string name
 * @property float account_balance
 * @property Carbon email_verified_at
 * @property Carbon phone_verified_at
 * @property array account_settings
 * @property mixed password
 * @property Carbon profile_verified_at
 * @property mixed identification
 * @property string admin_url
 * @property Carbon updated_at
 * @property mixed first_name
 * @property mixed photo
 * @property mixed two_fa_secret
 * @property mixed email
 * @property array wallet_addresses
 * @property Carbon last_login
 * @package App\Models\Auth
 */

#[UseFactory(UserFactory::class)]
class User extends Base implements Authenticatable, MustVerifyEmail, HasImageAttributes
{
    use ModelHelpers;
    use Notifiable;
    use FindByStatus;
    use HasName;
    use FindByEmail;
    use FindByPhone;
    use Notifiable;
    use AuthByRole;
    use AuthByPermission;
    use HasImageAttribute;
    use FindByUUID;
    use SoftDeletes;
    use HasSettings;
    use ResolveExchangeRate;
    use HasFactory;

    const S_ACTIVATED = 1;
    const S_ON_TRIAL = 2;
    const S_DISABLED = 0;
    const S_DEACTIVATED = 9;

    protected static $defaultSettings = null;

    protected $fillable = [
        'country_id',
        'referrer_id',
        'status',
        'uuid',
        'email',
        'phone',
        'password',
        'first_name',
        'last_name',
        'photo',
        'identification',
        'account_settings',
        'last_login',
        'email_verified_at',
        'profile_verified_at',
        'phone_verified_at',
        'two_fa_secret',
    ];

    protected $table = 'auth_users';

    protected $hidden = [
        'password',
        'two_fa_secret',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'account_settings' => 'array',
        'email_verified_at' =>  'datetime',
        'phone_verified_at' =>  'datetime',
        'profile_verified_at' =>  'datetime',
        'last_login' =>  'datetime',
        'deleted_at'=>'datetime',
    ];


    /**
     * @return string
     */
    public static function generateUUID(): string
    {
        return self::makeUUID(3, 3);
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function imageDir($attr = 'photo'): string
    {
        return $attr == 'photo' ? 'public' . DS . 'users' : 'public' . DS . 'identifications';
    }

    /**
     * @param string $attribute
     * @return string
     */
    public static function defaultImage($attr = 'photo'): string
    {
        return $attr == 'photo' ? 'user.png' : 'identification.png';
    }

    /**
     * @return string
     */
    public function getPhotoUrlAttribute(): string
    {
        return asset($this->getImageUrl('photo'));
    }

    /**
     * @return string
     */
    public function getIdentificationUrlAttribute(): string
    {
        return asset($this->getImageUrl('identification'));
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsToMany
     */
    public function currencies(): BelongsToMany
    {
        return $this->country->currencies();
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function swaps()
    {
        return $this->hasMany(Swap::class);
    }

    /**
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(static::class, 'referrer_id');
    }

    /**
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(static::class, 'referrer_id');
    }

    public function getAffiliateRate($index = 0)
    {
        $arr = $this->getAffiliateRates();
        return array_key_exists($index, $arr) ? $arr[$index] : 0;
    }

    public function getAffiliateRates()
    {
        $rates = $this->getSetting('affiliate_rates', '');
        $arr = explode(',', $rates);
        array_walk($arr, function (&$item, $index) {
            $item = (int)trim($item);
        });

        return $arr;
    }

    public function isSpecialAffiliate(): bool
    {
        return !is_null($this->getSetting('affiliate_rates'));
    }

    public function setAffiliateRate(string $rates): bool
    {
        $settings = (array)$this->account_settings;
        $settings['affiliate_rates'] = $rates;

        return $this->update(['account_settings' => $settings]);
    }

    public function deposits(): HasManyThrough
    {
        return $this->hasManyThrough(Deposit::class, Account::class);
    }

    public function withdrawals(): HasManyThrough
    {
        return $this->hasManyThrough(Withdrawal::class, Account::class);
    }

    /**
     * @return HasMany
     */
    public function portfolios(): HasManyThrough
    {
        return $this->hasManyThrough(Portfolio::class, Account::class);
    }

    /**
     * @return HasMany
     */
    public function bonuses(): HasManyThrough
    {
        return $this->hasManyThrough(Bonus::class, Account::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Account::class)->orderByDesc('created_at');
    }

    /**
     * @return string
     */
    public function getTotalBalance()
    {
        return $this->accounts()->sum('balance');
    }

    public function getTotalBalanceStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->getTotalBalance(), $currency->symbol, $currency->minor_unit);
    }

    public function getActivePortfolios()
    {
        return $this->portfolios()->whereIn('status', [Portfolio::S_ACTIVE])->sum('amount');
    }

    public function getPendingWithdrawals()
    {
        return $this->withdrawals()->whereIn('status', [Withdrawal::S_PENDING])->sum('amount');
    }

    public function getRefBonusStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->bonuses()->sum('amount'), $currency->symbol, $currency->minor_unit);
    }

    public function getPortfolios(Package $package): HasManyThrough
    {
        return $this->portfolios()->where('package_id', $package->id);
    }

    public function getPackageInvestmentAmount(Package $package)
    {
        return $this->getPortfolios($package)->sum('amount');
    }

    public function getPackageInvestmentAmountStr(Package $package): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->getPackageInvestmentAmount($package), $currency->symbol, $currency->minor_unit);
    }

    public function getPackageInvestmentProfit(Package $package)
    {
        $profits = 0;
        $this->getPortfolios($package)->each(function (Portfolio $portfolio) use (&$profits) {
            $profits += $portfolio->transactions()->sum('amount');
        });

        return $profits;
    }

    public function getPackageInvestmentProfitStr(Package $package): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->getPackageInvestmentProfit($package), $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return string
     */
    public function getActivePortfoliosStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->getActivePortfolios(), $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return string
     */
    public function getPendingWithdrawalsStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->getPendingWithdrawals(), $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return string
     */
    public function getTotalDepositsStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->deposits()->where(['status' => Deposit::S_VERIFIED])->sum('amount'), $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return string
     */
    public function getTotalPortfoliosStr(): string
    {
        $currency = Currency::getDefault();
        return to_currency($this->portfolios()->sum('amount'), $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return string
     */
    public function getTotalWithdrawalsStr(): string
    {
        $currency = Currency::getDefault();
        $value = $this->withdrawals()
            ->whereIn('status', [Withdrawal::S_PENDING, Withdrawal::S_APPROVED, Withdrawal::S_PAID_OUT, Withdrawal::S_FAILED])
            ->sum('amount');
        return to_currency($value, $currency->symbol, $currency->minor_unit);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return ($this->status == self::S_ACTIVATED);
    }

    /**
     * @return bool
     */
    public function isDeactivated(): bool
    {
        return ($this->status == self::S_DEACTIVATED);
    }

    /**
     * @return bool
     */
    public function isOnTrial(): bool
    {
        return ($this->status == self::S_ON_TRIAL);
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        if (array_key_exists($this->status, self::states())) {
            return self::states()[$this->status] . ($this->needsVerification() ? ' | Needs-Verification' : '');
        }

        return 'Unknown';
    }

    /**
     * @return array
     */
    public static function states(): array
    {
        return [
            self::S_ON_TRIAL => 'On-Trial',
            self::S_ACTIVATED => 'Activated',
            self::S_DEACTIVATED => 'De-activated',
            self::S_DISABLED => 'Disabled',
        ];
    }

    /**
     * @return string
     */
    public function referralLink(): string
    {
        return route('auth.register', ['referrerAID' => $this->uuid]);
    }

    /**
     * @return string
     */
    public function referralLinkToHome(): string
    {
        return route('cms.home', ['referrerAID' => $this->uuid]);
    }

    /**
     * @param bool $with_middle_name
     *
     * @return string
     */
    public function name($with_middle_name = false): string
    {
        return $this->getFirstNameAttribute() . ' ' . $this->getLastNameAttribute();
    }

    /**
     * @inheritDoc
     */
    public function getFirstNameAttribute(): string
    {
        return ucwords((string)$this->attributes['first_name']);
    }

    /**
     * @inheritDoc
     */
    public function getLastNameAttribute(): string
    {
        return ucwords((string)$this->attributes['last_name']);
    }

    /**
     * @return string
     */
    public function getAdminUrlAttribute(): string
    {
        return route('core.admin.user.view', ['user' => $this->getRouteKey()]);
    }

    /**
     * @return bool
     */
    public function needsVerification(): bool
    {
        return !$this->hasVerifiedProfile() && $this->hasFilledProfileData();// && $this->hasFilledWalletAddresses();
    }

    /**
     * @return bool
     */
    public function hasVerifiedProfile(): bool
    {
        return !is_null($this->profile_verified_at);
    }

    /**
     * @return bool
     */
    public function hasFilledProfileData(): bool
    {
        return (!is_null($this->photo) and !is_null($this->identification));
    }

    /**
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * @return array|null
     */
    public static function defaultSettings(): array
    {
        $keys = [
            Setting::KEY_REQUIRE_EMAIL_VERIFICATION,
            Setting::KEY_REQUIRE_PHONE_VERIFICATION,
            Setting::KEY_REQUIRE_PROFILE_VERIFICATION,
            Setting::KEY_ENABLE_2FA,
            Setting::KEY_ENABLE_NOTIFICATIONS,
            Setting::KEY_ALLOW_REFERRALS,
            Setting::KEY_REQUIRE_BONUS_APPROVAL,
            Setting::KEY_ALLOW_INVESTING,
            Setting::KEY_AUTO_REINVEST,
            Setting::KEY_ALLOW_WITHDRAWALS,
            Setting::KEY_WITHDRAWAL_INTERVAL,
            Setting::KEY_WITHDRAWAL_LIMIT,
        ];

        return is_null(self::$defaultSettings)
            ? Setting::query()->whereIn('key', $keys)->orderBy('cardinality')->pluck('value', 'key')->all()
            : self::$defaultSettings;
    }

    /**
     * @inheritDoc
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     */
    public function notify($instance): void
    {
        app(Dispatcher::class)->send($this, $instance);
    }
}
