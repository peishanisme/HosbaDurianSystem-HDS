<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaService
{
    protected string $disk;

    public function __construct(string $disk = 'supabase')
    {
        $this->disk = $disk;
    }

    /**
     * Store a file (UploadedFile or raw contents).
     *
     * @param string $path               Path (e.g. "trees/file.jpg" or just "trees")
     * @return string|null               Stored path
     */
    public function put($file, string $path): ?string
    {
        return Storage::disk($this->disk)->put($path, $file);
        // if ($file instanceof TemporaryUploadedFile) {
        //     // Let Laravel handle uploads
        //     return $file->store($path, $this->disk);
        // }

        // if (is_string($file)) {
        //     // Raw contents (you must provide full path with extension)
        //     Storage::disk($this->disk)->put($path, $file);
        //     return $path;
        // }

        // return null;

    }

    /**
     * Retrieve file contents.
     */
    public function get(string $path): ?string
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->get($path);
        }
        return null;
    }

    /**
     * Delete a file.
     */
    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Get public URL for a file.
     */
    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
}
