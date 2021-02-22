<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\StoreFile;
use App\Http\Resources\File as FileResource;
use App\Http\Resources\FileCollection;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\FileCollection
     */
    public function index()
    {
        $files = File::paginate();
        return new FileCollection($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\File\StoreFile  $request
     * @return \App\Http\Resources\File
     */
    public function store(StoreFile $request)
    {
        $file = File::create($request->validated());

        $path = "uploads/{$file->slug}.{$file->extension}";

        $file->path = Storage::disk('s3')->path($path);
        $file->save();
        $file->append('put_url');

        return new FileResource($file);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \App\Http\Resources\File
     */
    public function show(File $file)
    {
        return new FileResource($file);
    }

    /**
     * Validate and mark the specified file uploaded.
     *
     * @param  \App\Models\File  $file
     * @return \App\Http\Resources\File
     */
    public function update(File $file)
    {
        if ($file->uploaded) {
            return response()->json(
                ['message' => 'File has already been uploaded.'],
                403
            );
        }

        if (!Storage::disk('s3')->exists($file->path)) {
            return response()->json(
                ['message' => 'File not yet uploaded'],
                400
            );
        }

        if (Str::is('image/*', $file->type)) {
            // TODO: dispatch make thumbnail job
        } elseif (Str::is('video/*', $file->type)) {
            // TODO: dispatch convert video job
        } else {
            $file->processed_at = now();
        }

        $file->uploaded_at = now();
        $file->save();

        return new FileResource($file);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        $file->delete();

        return response()->noContent();
    }
}
