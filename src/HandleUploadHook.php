<?php

namespace WackCloudinary;

/**
 * Class HandleUploadHook
 *
 * @package WackCloudinary
 */
final class HandleUploadHook
{
    /**
     * Initialize the hooks
     */
    public function init()
    {
        add_filter('wp_handle_upload', [$this, 'handleUpload'], PHP_INT_MAX - 1, 2);
    }

    /**
     * Handles the upload of a file.
     *
     * This function is hooked to wp_handle_upload and is responsible for uploading the file to Cloudinary.
     *
     * @param array $upload Array of upload data.
     *
     * @return mixed Returns the unmodified $upload array.
     */
    public function handleUpload(array $upload, string $context)
    {
        $file_path = $upload['file'];
        $file_type = $upload['type'];

        // Get upload directory path from the file path
        $upload_dir_info = wp_get_upload_dir();
        $upload_basedir = $upload_dir_info['basedir'];
        $upload_directory = str_replace($upload_basedir . '/', '', dirname($file_path));

        // Get filename without ext from the file path, which is used as the public_id in Cloudinary
        $pathinfo = pathinfo($file_path);
        $filename_without_ext = $pathinfo['filename'];

        if (str_starts_with($file_type, 'video/')) {
            // Not implemented yet
        }

        if (str_starts_with($file_type, 'image/') || $file_type === 'application/pdf') {
            $cloudinaryUploadJob = new CloudinaryUploadJob();
            $cloudinaryUploadJob->data([
                'file_path' => $file_path,
                'public_id' => $filename_without_ext,
                'folder' => $upload_directory,
                'resource_type' => 'image',
                'type' => PluginSettings::get()->type(),
                'notification_url' => PluginSettings::get()->notificationUrl()
            ])->dispatch();
        }

        return $upload;
    }
}
