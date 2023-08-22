<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use October\Rain\Database\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
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
 * @property File        $file
 */
class UserFile extends Model
{
    protected $table = 'user_files';

    protected $visible = ['slug', 'title', 'path', 'extension', 'preview_path'];

    public function getHasPreviewAttribute(): string
    {
        return in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif']);
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
        $file_model->preview_path = $file_model->storePreviewOnDisk($file);
        $file_model->save();

        return $file_model;
    }

    public function updateFile(UploadedFile $file, string|null $file_name = null)
    {
        $this->deleteFileFromDisk();

        $this->title        = $file_name ?? $this->title;
        $this->extension    = $file->getClientOriginalExtension();
        $this->size         = $file->getSize();
        $this->path         = $this->storeFileOnDisk($file);
        $this->preview_path = $this->storePreviewOnDisk($file);
        $this->save();
    }

    public function deleteFile()
    {
        $this->deleteFileFromDisk();
        return $this->delete();
    }

    private function storeFileOnDisk(UploadedFile $file): string|null
    {
        $path = $file->storeAs('public/uploads', $this->slug);
        return $path ?: null;
    }

    private function storePreviewOnDisk(UploadedFile $file): string|null
    {
        if ($this->has_preview) {
            $path = $file->storeAs('public/previews', $this->slug);
            return $path ?: null;
        }
        return null;
    }

    private function deleteFileFromDisk(): void
    {
        Storage::delete($this->path);
        if ($this->has_preview) {
            Storage::delete($this->preview_path);
        }
    }

    public function getFileAttribute()
    {

    }
}
