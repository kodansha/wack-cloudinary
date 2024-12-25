<?php

/**
 * Plugin Name: WACK Cloudinary
 * Plugin URI: https://packagist.org/packages/kodansha/wack-cloudinary
 * Description: Uploads the uploaded media files to Cloudinary.
 * Version: 0.0.1
 * Author: KODANSHAtech LLC.
 * Author URI: https://github.com/kodansha
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

// Don't do anything if called directly.
if (!defined('ABSPATH') || !defined('WPINC')) {
    die();
}

// Autoloader
if (is_readable(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Initialize plugin
 */
function wack_cloudinary_init()
{
    // This is mandatory to register action otherwise the async request will get 400 Bad Request
    new WackCloudinary\CloudinaryUploadJob();

    (new WackCloudinary\HandleUploadHook())->init();
    (new WackCloudinary\BasicAuthHook())->init();
}

add_action('plugins_loaded', 'wack_cloudinary_init', PHP_INT_MAX - 1);
