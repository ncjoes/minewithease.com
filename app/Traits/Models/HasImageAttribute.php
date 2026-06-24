<?php
declare(strict_types=1);

namespace App\Traits\Models;

use App\Models\Auth\User;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Trait HasImageAttribute
 * @package App\Traits\Models
 */
trait HasImageAttribute
{
    /**
     * Get or Create thumbnail
     *
     * @param $attribute
     * @param boolean $createNew Force to create new thumbnail
     * @param string $old Old URL
     * @return string URL of thumbnail. Thumbnail is created if it does not exist
     * else old image URL is returned if conversion failed
     * @throws Exception
     */
    public function getThumbnailUrl($attribute, bool $createNew = false, string $old = ''): string
    {
        $destination = $this->imageDir($attribute) . DS . 'small' . DS . $this->$attribute;
        //Return thumbnail if it exists
        if (!empty($this->$attribute) && Storage::exists($destination) && !$createNew) {
            return normalize_url(Storage::url(Str::replaceFirst('public', '', $destination)));
        }

        //Create new
        $file = $this->imageDir($attribute) . DS . $this->$attribute;
        if (!empty($this->$attribute) && Storage::exists($file)) {
            $max_size = 640;
            $jpeg_quality = 90;

            if (!empty($old)) {
                if (Storage::exists($old) || Storage::exists($old = $this->imageDir($attribute) . DS . 'small' . DS . $old)) {
                    Storage::delete($old);
                }
            }

            $local_file = storage_path('app'.DS.$file);
            $local_thumb = storage_path('app'.DS.$destination);
            if (config('filesystems.default') == 'local') {
                $url = $this->resizeImage($local_file, $local_thumb, $max_size, $jpeg_quality);
            } else {
                Storage::disk('local')->put($file, Storage::get($file));
                $url = $this->resizeImage($local_file, $local_thumb, $max_size, $jpeg_quality);
                Storage::put($destination, Storage::disk('local')->get($destination), 'public');
                Storage::disk('local')->delete([$file, $destination]);
            }

            return $url ?
                normalize_url(Storage::url(Str::replaceFirst('public', '', $destination))) :
                $this->getImageUrl($attribute);
        }

        return $this->defaultImageUrl($attribute);
    }

    /**
     * Proportionally resize image
     *
     * @param string $source Source image URL
     * @param string $destination Destination image URL
     * @param float $max_size Maximum size
     * @param int $quality Jpeg image quality
     *
     * @return boolean
     */
    private function resizeImage($source, $destination, $max_size, $quality): bool
    {
        $image_size_info = getimagesize($source); //get image size
        if ($image_size_info) {
            $image_width  = $image_size_info[0]; //image width
            $image_height = $image_size_info[1]; //image height
        } else {
            return false;
        }

        //return false if nothing to resize
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        }
        //do not resize if image is smaller than max size
        if ($image_width <= $max_size && $image_height <= $max_size) {
            return false;
        }

        //Construct a proportional size of new image
        $image_scale = min($max_size / $image_width, $max_size / $image_height);
        $new_width = ceil($image_scale * $image_width);
        $new_height = ceil($image_scale * $image_height);

        //Create a new true color image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        if ($this instanceof User) {
            //White background, no transparency
            $trans_colour = imagecolorallocate($new_image, 255, 255, 255);
            imagefill($new_image, 0, 0, $trans_colour);
        } else {
            //Retain transparency
            imagesavealpha($new_image, true);
            $trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $trans_colour);
        }

        //Copy and resize part of an image with resampling
        $source_resource = $this->getImageResource($source);
        if (imagecopyresampled($new_image, $source_resource, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            return $this->saveImage($new_image, $destination, $image_size_info['mime'], $quality);
        } else {
            return false;
        }
    }

    /**
     * @param $source
     *
     * @return bool|resource
     */
    private function getImageResource($source): bool
    {
        $sourceResource = false;
        $imageInfo      = is_file($source) ? getimagesize($source) : false;
        if ($imageInfo) {
            switch (strtolower($imageInfo['mime'])) {
                case 'image/png':
                    $sourceResource = imagecreatefrompng($source);
                    break;
                case 'image/gif':
                    $sourceResource = imagecreatefromgif($source);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    $sourceResource = imagecreatefromjpeg($source);
                    break;
            }
        }

        return $sourceResource;
    }

    /**
     * @param resource $source Image resource
     * @param string $destination Destination file
     * @param string $mime Source mime type
     * @param int $quality Jpeg quality (for jpeg images)
     *
     * @return boolean
     */
    private function saveImage($source, string $destination, string $mime, int $quality)
    {
        //Create directory
        $split    = explode(DS, str_replace('/', DS, $destination));
        $filename = array_pop($split); //Remove filename
        $dir      = implode(DS, $split);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //save resized image
        switch (strtolower($mime)) {
            case 'image/png':
                $ok = imagepng($source, $destination);
                break; //save png file
            case 'image/gif':
                $ok = imagegif($source, $destination);
                break; //save gif file
            case 'image/jpeg':
            case 'image/pjpeg': //Save jpg/jpeg file
                $ok = imagejpeg($source, $destination, $quality);
                break; //save jpeg file
            default:
                return false;
        }
        if ($ok) {
            return $destination;
        }
        unlink($destination);

        return false;
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getImageUrl($attribute): string
    {
        if (empty($this->$attribute) || !Storage::exists($this->imageDir($attribute) . DS . $this->$attribute)) {
            return $this->defaultImageUrl($attribute);
        }

        return config('filesystems.default') == 'local' ?
            Storage::url(Str::replaceFirst('public' . DS, '', $this->imageDir($attribute)) . '/' . $this->$attribute) :
            Storage::url($this->imageDir($attribute) . '/' . $this->$attribute);
    }

    /**
     * @param $attribute
     * @return string
     */
    public function defaultImageUrl(string $attribute): string
    {
        $img = $this->defaultImage($attribute);

        return asset('images/defaults/' . $img);
    }

    /**
     * @param $path
     * @param $attribute
     * @throws Exception
     */
    public function checkCropAndSave($path, $attribute)
    {
        $current = $this->imageDir($attribute) . DS . $this->$attribute;
        if (!empty($this->$attribute) && Storage::exists($current)) {
            Storage::delete($current);
            $this->deleteThumbnail($attribute);
        }

        $this->$attribute = $path;
        $crop = request()->input('crop', false);
        if ($crop) {
            $crop = json_decode($crop);
            $cropped = $this->cropImage($crop->x, $crop->y, $crop->width, $crop->height, $attribute);
            abort_unless($cropped, 500, 'Cropping failed');
        }
        $this->save();
    }

    /**
     * @param $attribute
     */
    public function deleteThumbnail($attribute)
    {
        if (!empty($this->$attribute) && Storage::exists($this->imageDir($attribute) . DS . 'thumbs' . DS . $this->$attribute)) {
            Storage::delete($this->imageDir($attribute) . DS . 'thumbs' . DS . $this->$attribute);
        }
    }

    /**
     * @param $x
     * @param $y
     * @param $width
     * @param $height
     * @param $attribute
     * @return bool
     * @throws Exception
     *
     */
    public function cropImage($x, $y, $width, $height, $attribute): bool
    {
        //return false if nothing to resize
        if ($width <= 0 || $height <= 0 || is_nan($x) || is_nan($y)) {
            return false;
        }

        $on_local_disk = config('filesystems.default') == 'local';

        $destination = $this->imageDir($attribute) . DS . $this->$attribute;
        if (!$on_local_disk) {
            Storage::disk('local')->put($destination, Storage::get($destination));
        }
        $file = storage_path('app'.DS.$destination);

        //Create a new true color image
        $new_image = imagecreatetruecolor($width, $height);
        //Retain transparency
        imagesavealpha($new_image, true);
        $trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagefill($new_image, 0, 0, $trans_colour);

        //Copy and resize part of an image with resampling
        $source = $this->getImageResource($file);
        if ($source) {
            if (imagecopyresampled($new_image, $source, 0, 0, $x, $y, $width, $height, $width, $height)) {
                $dest = storage_path('app'.DS.'RAM'.DS.$destination);
                if ($this->saveImage($new_image, $dest, getimagesize($file)['mime'], 50)) {
                    Storage::delete($destination);
                    if ($on_local_disk) {
                        $status = Storage::move('RAM'.DS.$destination, $destination);
                    } else {
                        Storage::put($destination, Storage::disk('local')->get('RAM'.DS.$destination), 'public');
                        $status = Storage::disk('local')->delete('RAM'.DS.$destination);
                    }

                    return $status;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * @param $attribute
     */
    public function deleteImage($attribute)
    {
        if (!empty($this->$attribute) && Storage::exists($this->imageDir($attribute) . DS . $this->$attribute)) {
            Storage::delete($this->imageDir($attribute) . DS . $this->$attribute);
            $this->update([$attribute => null]);
        }
    }
}
