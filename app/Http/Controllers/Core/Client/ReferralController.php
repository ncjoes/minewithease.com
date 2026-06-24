<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Client;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ReferralController
 * @package App\Http\Controllers\Core\Member
 */
class ReferralController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function manage(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $referrals = $user->referrals()->orderByDesc('id')->paginate(10);
        $referral_link = $user->referralLink();

        return view('core-client.referral.manage', [
            'referrals'     => $referrals,
            'referral_link' => $referral_link,
        ]);
    }
}
