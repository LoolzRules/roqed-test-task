<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;
use October\Rain\Database\Builder;
use Illuminate\Support\Facades\Storage;

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

}
