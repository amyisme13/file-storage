<?php

namespace App\Models;

use App\Support\Aws\S3Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File as FacadesFile;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class File extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'size'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(80);
    }

    /**
     * Get the presigned PutObject url.
     *
     * @return string
     */
    public function getPutUrlAttribute()
    {
        return S3Client::create()->getPresignedUrl(
            $this->path,
            now()->addHours(2),
            ['ContentType' => $this->type],
            'PutObject'
        );
    }

    /**
     * Get the file extension from name.
     *
     * @return string
     */
    public function getExtensionAttribute()
    {
        return FacadesFile::extension($this->name);
    }

    /**
     * Get whether file has been uploaded.
     *
     * @return string
     */
    public function getUploadedAttribute()
    {
        return !is_null($this->uploaded_at);
    }
}
