<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait UploadFile
{

    /**
     * Upload file using storeAs() function. Recommend for saving file in storage's symlink folder.
     *
     * @param  Illuminate\Support\Facades\Request::File $file - the uploaded file (e.g., $request->thumbnail)
     * @param  string $where - path you want to put the file at (e.g., "public/photos")
     * @param  string $identifier - custom identifier, adding custom string in front of the filename, required if this function is called twice in the same code to prevent generating duplicate filenames. (example, adding "thumbnail" will make generated file name like "thumbnail_filename_time().jpg")
     * @return array [$path, $savedFileName]
     */
    public function uploadFile($file, $where, $identifier = null)
    {
        $find_format = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'jfif', 'JPG', 'JPEG', 'PNG', 'GIF', 'SVG', 'JFIF'];
        $extension   = !in_array($file->getClientOriginalExtension(), $find_format) ? $file->getClientOriginalExtension() : 'webp';
        $filename    = null;

        for ($i = 1; $i <= rand(50, 100); $i++) {

            $number     = random_int(0, 36);
            $character  = base_convert($number, 10, 36);
            $filename  .= $character;
        }

        if ($identifier) {

            $savedFileName = $identifier . '_' . $filename  . '.' . $extension;
        } else {

            $savedFileName = $filename  . '.' . $extension;
        }

        // DirectoryTrait::createDirectory($where);
        $this->createDirectory($where);

        // dd(storage_path("app{$where}/{$savedFileName}"));

        if (in_array($extension, ['webp'])) {

            $new_file = Webp::make($file)->quality(80);
            $path     = $new_file->save(storage_path("app{$where}/{$savedFileName}"));

        } else {

            $path = $file->storeAs($where, $savedFileName);
        }

        return [$path, $savedFileName];
    }

    /**
     * Upload file from URL using storeAs() function. Recommend for saving file in storage's symlink folder.
     *
     * @param  Illuminate\Support\Facades\Request::File $file - the uploaded file (e.g., $request->thumbnail)
     * @param  string $where - path you want to put the file at (e.g., "partner/bg_photo")
     * @param  string $identifier - custom identifier, adding custom string in front of the filename, required if this function is called twice in the same code to prevent generating duplicate filenames. (example, adding "thumbnail" will make generated file name like "thumbnail_filename_time().jpg")
     * @return array [$path, $savedFileName]
     */
    public function uploadImageFromUrl($url, $where, $identifier = null)
    {
        $filename  = null;

        for ($i = 1; $i <= rand(50, 100); $i++) {

            $number     = random_int(0, 36);
            $character  = base_convert($number, 10, 36);
            $filename  .= $character;
        }

        if ($identifier) {

            $savedFileName = $identifier . '_' . $filename  . '.webp';
        } else {

            $savedFileName = $filename  . '.' . '.webp';
        }

        $image_file = file_get_contents($url);

        if (!$image_file) {
            return false;
        }

        // DirectoryTrait::createDirectory($where);
        // UploadFile::createDirectory($where);

        $is_saved   = Storage::put("$where/$savedFileName", $image_file);

        return [$is_saved, $savedFileName];
    }

    /**
     * Creating Directory if not exist using File::isDirectory() & File::makeDirectory() function. this function will creating the folder only if the folder does not exist.
     *
     * @param  string $path - path of the folder, it will use public_path("storage/$path") (e.g., "public/photos")
     * @param  string $identifier - custom identifier, adding custom string in front of the filename, required if this function is called twice in the same code to prevent generating duplicate filenames. (example, adding "thumbnail" will make generated file name like "thumbnail_filename_time().jpg")
     * @return array [$path, $savedFileName]
     */
    private function createDirectory($path)
    {
        $path = storage_path("app{$path}");

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }

    public function multipleFileUpload(array $old_images, array $new_images, $main_folder)
    {
        $images = $currentUrls = $newPaths = $uploadFiles =[];

        foreach ($new_images as $item) {
            if (is_string($item)) {
                $relative = ltrim(Str::after(parse_url($item, PHP_URL_PATH), '/storage/'), '/');
                $currentUrls[] = asset('storage/'. $relative);
            }

            if ($item instanceof TemporaryUploadedFile || $item instanceof UploadedFile) {
                $url_image = $this->uploadFile($item, "/public/$main_folder");
                $newPaths[] = "/$main_folder/". $url_image[1];
            }
        }

        $toDelete = array_diff($old_images, $currentUrls);

        foreach ($toDelete as $oldPath) {
            $relativePath = ltrim(Str::after(parse_url($oldPath, PHP_URL_PATH), '/storage/'), '/');
            Storage::disk('public')->delete($relativePath);
        }

        $uploadFiles = array_merge($currentUrls, $newPaths);

        foreach ($uploadFiles as $key => $file) {
            $images[] = '/'.ltrim(Str::after(parse_url($file, PHP_URL_PATH), '/storage/'), '/');
        }

        return $images;
    }

    public function singleFileUpload($old_image, $new_image, $main_folder)
    {
        $image = $currentUrl = $newPath = $uploadFile = null;

        $url_image = $this->uploadFile($new_image, "/public/$main_folder");
        $newPath = "/$main_folder/". $url_image[1];

        $relativePath = ltrim(Str::after(parse_url($old_image, PHP_URL_PATH), '/storage/'), '/');
        Storage::disk('public')->delete($relativePath);

        $image = '/'.ltrim(Str::after(parse_url($newPath, PHP_URL_PATH), '/storage/'), '/');
        return $image;
    }
}
