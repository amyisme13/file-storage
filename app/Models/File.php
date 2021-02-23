<?php

namespace App\Models;

use App\Support\Aws\S3Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class File extends Model
{
    use HasFactory, HasSlug, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'size'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'job_id', 'user_id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url', 'thumbnail_url'];

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

    /**
     * Get the url from path
     *
     * @return string|null
     */
    public function getUrlAttribute()
    {
        if (is_null($this->path)) {
            return null;
        }

        return Storage::disk('s3')->temporaryUrl($this->path, now()->addHour());
    }

    /**
     * Get the thumbnail url from thumbnail
     *
     * @return string|null
     */
    public function getThumbnailUrlAttribute()
    {
        if (is_null($this->thumbnail)) {
            return null;
        }

        return Storage::disk('s3')->temporaryUrl(
            $this->thumbnail,
            now()->addHour()
        );
    }

    /**
     * Return the user that upload the file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
