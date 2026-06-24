<?php
declare(strict_types=1);

namespace App\Traits\Controllers;

use App\Interfaces\HasImageAttributes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Trait SetImage
 * @package App\Traits\Controllers
 */
trait SetImage
{
    /**
     * @param Request $request
     * @param \App\Interfaces\HasImageAttributes $model
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function doSetImage(Request $request, HasImageAttributes $model): array
    {
        abort_unless(method_exists($model, 'checkCropAndSave'), 403);
        $class = get_class($model);

        $this->validate($request, [
            'attribute' => 'required|alpha_dash',
            'image'     => 'file|image|mimes:jpeg,jpg,png',//max:1024||dimensions:min_width=942,min_height=290'
        ]);
        $attribute     = $request->input('attribute');
        $uploaded_file = $request->file('image');

        $path = is_object($uploaded_file) ? normalize_path($uploaded_file->storePubliclyAs(
            $model->imageDir($attribute), $model->getRouteKey() . '_' . Str::random(2) . '.' . $uploaded_file->extension()
        )) : null;
        if ($path) {
            $model->deleteImage($attribute);
            $exact_path = str_replace($model->imageDir($attribute) . DS, '', $path);
            $model->checkCropAndSave($exact_path, $attribute);
            $url = $model->getImageUrl($attribute);

            return [
                'status'  => true,
                'data'    => ['url' => $url, 'attribute' => $attribute],
                'message' => 'Image Updated Successfully',
            ];
        }

        return ['status' => false, 'message' => 'Error Uploading Image! Try again shortly.'];
    }
}
