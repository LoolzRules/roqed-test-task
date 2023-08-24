<?php

namespace App\Services;

use App\Models\UserFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;

class UserFileService
{
    public static function uploadFile(UploadedFile $file, string|null $file_name = null): UserFile
    {
        $slug = Str::uuid();

        $file_model = new UserFile();
        $file_model->slug         = $slug;
        $file_model->title        = $file_name ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_model->extension    = $file->getClientOriginalExtension();
        $file_model->size         = $file->getSize();
        self::storeFileOnDisk($file_model, $file);
        self::storePreviewOnDisk($file_model);
        $file_model->save();

        return $file_model;
    }

    public static function updateFile(UserFile $model, UploadedFile|null $file, string|null $file_name = null)
    {
        $model->title        = $file_name ?? $model->title;

        if ($file) {
            self::deleteFilesFromDisk($model);
            $model->extension    = $file->getClientOriginalExtension();
            $model->size         = $file->getSize();
            self::storeFileOnDisk($model, $file);
            self::storePreviewOnDisk($model);
        }

        $model->save();
    }

    public static function deleteFile(UserFile $model)
    {
        self::deleteFilesFromDisk($model);
        return $model->delete();
    }

    public static function storeFileOnDisk(UserFile $model, UploadedFile $file): void
    {
        $path = $file->storeAs('public/uploads', $model->slug);
        $model->path = $path ?: null;
    }

    public static function storePreviewOnDisk(UserFile $model)
    {
        if ($model->has_preview) {
            $path_to_read = Storage::path($model->path);
            $path_to_write = preg_replace('/\/uploads\//', '/previews/', $path_to_read);

            try {
                $thumb = new Imagick($path_to_read);
                $thumb->setImageFormat('jpg');
                $thumb->setImageCompressionQuality(85);

                $thumb->scaleImage(100,100,true);
                //            $thumb->resizeImage(100,100,Imagick::FILTER_CUBIC,1);

                $thumb->writeImage($path_to_write);
                $thumb->destroy();
                $path = substr($path_to_write, strlen(Storage::path('')));
            } catch (\ImagickException $e) {
                $path = null;
            }

            $model->preview_path = $path;
            return;
        }
        $model->preview_path = null;
    }

    public static function deleteFilesFromDisk(UserFile $model): void
    {
        if (Storage::exists($model->path)) {
            Storage::delete($model->path);
        }
        if ($model->has_preview && $model->preview_path && Storage::exists($model->preview_path)) {
            Storage::delete($model->preview_path);
        }
    }
}
