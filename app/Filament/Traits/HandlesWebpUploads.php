<?php

namespace App\Filament\Traits;

use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

trait HandlesWebpUploads
{
    /**
     * Configures a FileUpload component to process and save images as WebP.
     *
     * @param  FileUpload  $component The FileUpload component instance.
     * @param  int  $quality The quality of the resulting WebP image (0-100).
     * @return FileUpload The configured FileUpload component.
     */
    public static function processImageUpload(FileUpload $component, int $quality = 75): FileUpload
    {
        return $component
            ->saveUploadedFileUsing(function (FileUpload $component, UploadedFile $file) use ($quality) {
                // Use the GD driver for image manipulation.
                $manager = ImageManager::gd();

                // Generate a unique filename with a .webp extension.
                $fileName = Str::random(20) . '.webp';

                // Get the configured directory from the component.
                $directory = $component->getDirectory();
                $path = ($directory ? $directory . '/' : '') . $fileName;

                // Read the uploaded file, encode it to WebP format, and get the content.
                $encodedImage = $manager->read($file->getRealPath())->toWebp($quality);

                // Get the configured disk and store the file.
                $component->getDisk()->put($path, (string) $encodedImage);

                // Return the relative path to be stored in the database.
                return $path;
            });
    }
}
