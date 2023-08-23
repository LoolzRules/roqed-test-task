<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Imagick;
use October\Rain\Database\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * UserFile Model
 *
 * @mixin Builder
 * @mixin Eloquent
 *
 * @property integer     $id
 * @property string      $title
 * @property string      $slug
 * @property string      $path
 * @property string      $extension
 * @property string|null $preview_path
 * @property integer     $size
 *
 * Computed attributes
 * @property bool        $has_preview
 * @property string      $file_link
 * @property string      $preview_link
 */
class UserFile extends Model
{
    protected $table = 'user_files';

    protected $visible = ['size', 'slug', 'title', 'extension'];

    public function getHasPreviewAttribute(): string
    {
        return in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function getFileLinkAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getPreviewLinkAttribute(): string|null
    {
        return isset($this->preview_path)
            ? Storage::url($this->preview_path)
            : null;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['file_link'] = $this->file_link;
        $array['preview_link'] = $this->preview_link;
        return $array;
    }

    public static function uploadFile(UploadedFile $file, string|null $file_name = null): UserFile
    {
        $slug = Str::uuid();

        $file_model = new self;
        $file_model->slug         = $slug;
        $file_model->title        = $file_name ?? $slug;
        $file_model->extension    = $file->getClientOriginalExtension();
        $file_model->size         = $file->getSize();
        $file_model->path         = $file_model->storeFileOnDisk($file);
        $file_model->preview_path = $file_model->storePreviewOnDisk();
        $file_model->save();

        return $file_model;
    }

    public function updateFile(UploadedFile|null $file, string|null $file_name = null)
    {
        $this->title        = $file_name ?? $this->title;

        if ($file) {
            $this->deleteFilesFromDisk();
            $this->extension    = $file->getClientOriginalExtension();
            $this->size         = $file->getSize();
            $this->path         = $this->storeFileOnDisk($file);
            $this->preview_path = $this->storePreviewOnDisk();
        }

        $this->save();
    }

    public function deleteFile()
    {
        $this->deleteFilesFromDisk();
        return $this->delete();
    }

    private function storeFileOnDisk(UploadedFile $file): string|null
    {
        $path = $file->storeAs('public/uploads', $this->slug);
        return $path ?: null;
    }

    private function storePreviewOnDisk(): string|null
    {
        if ($this->has_preview) {
            $path_to_read = Storage::path($this->path);
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

            return $path;
        }
        return null;
    }

    private function deleteFilesFromDisk(): void
    {
        if (Storage::exists($this->path)) {
            Storage::delete($this->path);
        }
        if ($this->has_preview && $this->preview_path && Storage::exists($this->preview_path)) {
            Storage::delete($this->preview_path);
        }
    }
}
