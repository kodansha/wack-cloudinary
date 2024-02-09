<?php

namespace WackCloudinary;

use Cloudinary\Api\Upload\UploadApi;

/**
 * Class CloudinaryUploadJob
 *
 * This class is responsible for async uploading files to Cloudinary.
 *
 * @package WackCloudinary
 */
final class CloudinaryUploadJob extends WP_Async_Request
{
    protected $prefix = 'wack-cloudinary';
    protected $action = 'cloudinary-upload-job';

    /**
     * Handle a dispatched request.
     *
     * It retrieves the file_path, public_id, folder, resource_type, type, eager and notification_url from the $_POST array and uploads the file to Cloudinary.
     *
     * - file_path: The path to the file to be uploaded
     * - public_id: The public ID of the file in Cloudinary
     * - folder: The folder in Cloudinary where the file will be uploaded
     * - resource_type: The type of resource to be uploaded. Possible values are 'image', 'raw', 'video', 'auto'
     * - type: The type of the file to be uploaded. Possible values are 'authenticated', 'upload', 'private'
     * - eager: The eager transformations to be performed on the file after upload
     * - notification_url: The URL to be notified when the upload and the eager-transformation is complete
     */
    protected function handle()
    {
        $file_path = $_POST['file_path'];
        $public_id = $_POST['public_id'];
        $folder = $_POST['folder'];
        $resource_type = $_POST['resource_type'];
        $type = $_POST['type'];
        $eager = $_POST['eager'];
        $notification_url = $_POST['notification_url'];

        if (empty($file_path) || empty($public_id)) {
            return;
        }

        if (empty($folder)) {
            $folder = PluginSettings::get()->rootFolder();
        } else {
            $folder = PluginSettings::get()->rootFolder() . '/' . $folder;
        }

        if (empty($resource_type)) {
            $resource_type = 'auto';
        }

        if (empty($type)) {
            $type = 'upload';
        }

        if (empty($eager)) {
            $eager = [];
        }

        if (empty($notification_url)) {
            $notification_url = null;
        }

        (new UploadApi())->upload($file_path, [
            'public_id' => $public_id,
            'folder' => $folder,
            'type' => $type,
            'resource_type' => $resource_type,
            'eager' => $eager,
            'eager_async' => true,
            'overwrite' => true,
            'invalidate' => true,
            'notification_url' => $notification_url,
            'eager_notification_url' => empty($eager) ? null : $notification_url
        ]);
    }
}
