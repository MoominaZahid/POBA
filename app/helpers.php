<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('media_url')) {
    /**
     * Generate URL for public media files using Laravel's default filesystem.
     * Safely handles null, empty paths, absolute HTTP/HTTPS URLs, and relative storage paths.
     *
     * @param string|null $path
     * @return string|null
     */
    function media_url(?string $path = ''): ?string
    {
        if ($path === null) {
            return null;
        }

        if ($path === '') {
            return rtrim(Storage::url(''), '/');
        }

        // Return as is if already a full HTTP/HTTPS URL (e.g. Unsplash, placehold.co)
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        return Storage::url($path);
    }
}

if (!function_exists('cnic_url')) {
    /**
     * Generate secure URL for sensitive CNIC document files.
     * Generates a temporary signed URL for S3 or standard storage URL for local disk.
     *
     * @param string|null $path
     * @param int $expirationMinutes
     * @return string|null
     */
    function cnic_url(?string $path = '', int $expirationMinutes = 15): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $disk = config('filesystems.default');

        if ($disk === 's3') {
            try {
                return Storage::temporaryUrl($path, now()->addMinutes($expirationMinutes));
            } catch (\Throwable $e) {
                return Storage::url($path);
            }
        }

        return Storage::url($path);
    }
}
