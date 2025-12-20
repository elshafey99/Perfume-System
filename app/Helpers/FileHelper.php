<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class FileHelper
{
    /**
     * Allowed file extensions for upload.
     */
    protected static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];

    /**
     * Allowed MIME types for upload validation.
     */
    protected static array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    /**
     * Maximum allowed file size (10 MB).
     */
    protected static int $maxSize = 10 * 1024 * 1024;

    /**
     * Clean and normalize a given path.
     * Removes extra slashes and trims spaces.
     */
    protected static function cleanPath(string $path): string
    {
        return trim(preg_replace('/[\/\\\\]+/', '/', $path), '/ ');
    }

    /**
     * Upload an image or safe file to the public directory.
     * Automatically deletes the old file if provided.
     *
     * @param UploadedFile|string|null $file
     * @param string $folder Directory inside /public (e.g. "uploads/products")
     * @param string|null $oldPath Optional path to old file to delete
     * @return string|null
     */
    public static function uploadImage($file, string $folder, ?string $oldPath = null): ?string
    {
        if (empty($file)) {
            return null;
        }

        $folder = self::cleanPath($folder);
        $uploadPath = public_path($folder);

        // Ensure the target directory exists
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);

            // Create a .htaccess file to block PHP execution in upload folders
            file_put_contents(
                $uploadPath . '/.htaccess',
                "<FilesMatch \"\\.php$\">\nDeny from all\n</FilesMatch>"
            );
        }

        // Handle Livewire temporary file uploads
        if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $file = new UploadedFile(
                $file->getRealPath(),
                $file->getClientOriginalName(),
                $file->getMimeType(),
                $file->getError(),
                true
            );
        }

        if (!$file instanceof UploadedFile) {
            throw new RuntimeException('Invalid file input.');
        }

        // Validate file size
        if ($file->getSize() > self::$maxSize) {
            throw new RuntimeException('File size exceeds the allowed limit (5MB).');
        }

        // Validate extension and MIME type
        $ext = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        if (!in_array($ext, self::$allowedExtensions, true) || !in_array($mime, self::$allowedMimeTypes, true)) {
            throw new RuntimeException('Unsupported or unsafe file type.');
        }

        // Double-check actual content for images
        if (str_starts_with($mime, 'image/') && !@getimagesize($file->getRealPath())) {
            throw new RuntimeException('Invalid image file.');
        }

        // Generate a unique, safe file name
        $safeName = time() . '_' . Str::uuid()->toString() . '.' . $ext;

        // Move the file to the public directory
        $file->move($uploadPath, $safeName);

        // Delete old file if exists
        if (!empty($oldPath)) {
            self::delete($oldPath);
        }

        // Return the relative file path
        return "{$folder}/{$safeName}";
    }

    /**
     * Upload any file type (same logic as uploadImage).
     *
     * @param mixed $file
     * @param string $folder
     * @param string|null $oldPath
     * @return string|null
     */
    public static function uploadFile($file, string $folder, ?string $oldPath = null): ?string
    {
        return self::uploadImage($file, $folder, $oldPath);
    }

    /**
     * Delete a file from the public directory if it exists.
     *
     * @param string|null $path
     * @return bool
     */
    public static function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = public_path(self::cleanPath($path));

        if (File::exists($fullPath)) {
            return File::delete($fullPath);
        }

        return false;
    }

    /**
     * Replace an existing file with a new one.
     * Automatically deletes the old file if provided.
     *
     * @param mixed $newFile
     * @param string|null $oldPath
     * @param string $folder
     * @return string|null
     */
    public static function updateFile($newFile, ?string $oldPath, string $folder): ?string
    {
        return self::uploadImage($newFile, $folder, $oldPath);
    }

    /**
     * Generate a public URL for a given relative file path.
     *
     * @param string|null $path
     * @return string|null
     */
    public static function url(?string $path): ?string
    {
        if (empty($path)) return null;

        return asset(self::cleanPath($path));
    }
}
