<?php
declare(strict_types=1);

namespace App\Http\Controllers\Core\Admin;

use App\Http\Controllers\Controller;
use App\Models\ETC\Setting;
use App\Traits\Controllers\SetImage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class SettingController
 * @package App\Http\Controllers\Core\Admin
 */
class SettingController extends Controller
{
    use SetImage;

    public function view(Request $request): Factory|\Illuminate\Contracts\View\View|Application
    {
        $groups   = Setting::query()->orderBy('group')->get()->pluck('group', 'group')->all();
        $settings = [];
        foreach ($groups as $group) {
            $settings[$group] = Setting::where('group', $group)->orderBy('cardinality')->get();
        }

        return view('core-admin.settings', [
            'groups'   => $groups,
            'settings' => $settings,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $input    = $request->input();
        $settings = [];
        foreach ($input as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if (is_object($setting)) {
                $settings[$key] = $setting;
                $this->validate($request, [$key => $setting->validation_rules]);
            }
        }

        DB::beginTransaction();
        foreach ($settings as $key => $setting) {
            $setting->update(['value' => $input[$key]]);
        }
        DB::commit();

        return [
            'status'  => true,
            'message' => 'Changes saved successfully.'
        ];
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setImage(Request $request, Setting $setting): array
    {
        $arr                      = $this->doSetImage($request, $setting);
        $arr['data']['attribute'] = $setting->key();

        return $arr;
    }
}
