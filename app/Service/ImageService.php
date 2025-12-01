<?php

namespace App\Service;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected string $disk;
    protected string $folder;

    public function __construct(string $disk, string $folder = 'images')
    {
        $this->disk = $disk;
        $this->folder = $folder;
    }

    public function store(UploadedFile $file): string
    {
        $filename = uniqid() . '_' . trim($file->getClientOriginalName());
        $storedPath = $file->storeAs($this->folder, $filename, ['disk' => $this->disk]);

        return Storage::disk($this->disk)->url($storedPath);
    }

    public function delete(string $filePath): bool
    {
        $relativePath = str_replace(Storage::disk($this->disk)->url('/'), '', $filePath);
        return Storage::disk($this->disk)->delete($relativePath);
    }
}
