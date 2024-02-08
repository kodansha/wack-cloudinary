# WACK Cloudinary

**WACK Cloudinary** is a WordPress plugin to upload the media files to Cloudinary.

It hooks into the WordPress [`wp_handle_upload`](https://developer.wordpress.org/reference/hooks/wp_handle_upload/)
and when a media file is uploaded, it also uploads the file to Cloudinary.

It is created with the intention of being used with the WACK Stack, but it can also be used with other WordPress installations.

> [!IMPORTANT]
> This plugin does **NOT** replaces neither the media URLs in the content nor the URLs in the media library. This means you have to manually replace the URLs when displaying the media to users.

## Installation

- Requires PHP 8.1 or later
- Requires WordPress 6.0 or later
- Requires Composer

### Using Composer

```bash
composer require kodansha/wack-cloudinary
```

> [!NOTE]
> This plugin is not available on the WordPress.org plugin repository.
> For the moment, the only way to install it is to use Composer.

## How to use

### Pre-requisites

You need to set up the Cloudinary account and [set the credentials in the environment variables](https://cloudinary.com/documentation/php_integration#setting_the_cloudinary_url_environment_variable).

```shell
export CLOUDINARY_URL=cloudinary://<api_key>:<api_secret>@<cloud_name>
```

> [!TIP]
> It strongly recommended to use the `.env` file to set the environment variables, especially in the local development environment.

### Configuration

You might want to define settings through the `WACK_CLOUDINARY_SETTINGS` constant:

```php
define('WACK_CLOUDINARY_SETTINGS', [
    // Optional: The type of the Cloudinary media access. Possible values are 'authenticated', 'upload', 'private'
    // Default: 'upload'
    'type' => 'private',

    // Optional: The Cloudinary root folder to upload the media files.
    // Default: none
    'root_folder' => 'my-root-folder',

    // Optional: The notification URL endpoint to receive the Cloudinary notifications.
    // Default: none
    'notification_url' => 'https://example.com/cloudinary-notification',

    // Optional: The username and password for the basic authentication.
    // If your WordPress is behind the basic authentication, you need to set this to work the async requests.
    // Default: none
    'basic_auth' => [
        'username' => 'example-user',
        'password' => 'example-password',
    ]
]);
```

### Upload media files

After setting the above pre-requisites and configuration, when you upload a media file to the WordPres, the file is also uploaded to Cloudinary.

## TODO

- Support video upload
- Support eager transformation
- Support upload presets
