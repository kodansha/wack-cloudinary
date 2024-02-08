<?php

namespace WackCloudinary;

/**
 * Class PluginSettings
 *
 * @package WackCloudinary
 */
final class PluginSettings
{
    private string|null $root_folder;
    private string $type;
    private string|null $notification_url;
    private array|null $basic_auth;

    final private function __construct()
    {
        $this->root_folder = self::getRootFolderFromConstant();
        $this->type = self::getTypeFromConstant();
        $this->notification_url = self::getNotificationUrlFromConstant();
        $this->basic_auth = self::getBasicAuthFromConstant();
    }

    /**
     * Get the root folder
     *
     * @return string|null The root folder, null if not set
     */
    public function rootFolder(): string|null
    {
        return $this->root_folder;
    }

    /**
     * Get the type
     *
     * @return string The type, possible values are 'authenticated', 'upload', 'private'
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the notification URL
     *
     * @return string|null The notification URL, null if not set
     */
    public function notificationUrl(): string|null
    {
        return $this->notification_url;
    }

    /**
     * Get the basic auth
     *
     * @return array|null The basic auth, null if not set or invalid
     */
    public function basicAuth(): array|null
    {
        return $this->basic_auth;
    }

    /**
     * Get instance
     *
     * @return PluginSettings
     */
    public static function get(): PluginSettings
    {
        return new self();
    }

    /**
     * Get the root folder from the 'WACK_CLOUDINARY_SETTINGS' constant.
     */
    public static function getRootFolderFromConstant(): string|null
    {
        if (!isset(Constants::settingsConstant()['root_folder'])) {
            return null;
        }

        return Constants::settingsConstant()['root_folder'];
    }

    /**
     * Get the type from the 'WACK_CLOUDINARY_SETTINGS' constant.
     *
     * @return string The type, possible values are 'authenticated', 'upload', 'private', default is 'upload'
     */
    public static function getTypeFromConstant(): string
    {
        if (!isset(Constants::settingsConstant()['type'])) {
            return 'upload';
        }

        if (!in_array(Constants::settingsConstant()['type'], ['authenticated', 'upload', 'private'])) {
            return 'upload';
        }

        return Constants::settingsConstant()['type'];
    }

    /**
     * Get the notification URL from the 'WACK_CLOUDINARY_SETTINGS' constant.
     *
     * @return string|null The notification URL, null if not set
     */
    public static function getNotificationUrlFromConstant(): string|null
    {
        if (!isset(Constants::settingsConstant()['notification_url'])) {
            return null;
        }

        return Constants::settingsConstant()['notification_url'];
    }

    /**
     * Get the basic auth from the 'WACK_CLOUDINARY_SETTINGS' constant.
     *
     * @return array|null The basic auth, null if not set or invalid
     */
    public static function getBasicAuthFromConstant(): array|null
    {
        if (!isset(Constants::settingsConstant()['basic_auth'])) {
            return null;
        }

        if (!is_array(Constants::settingsConstant()['basic_auth'])) {
            return null;
        }

        if (
            empty(Constants::settingsConstant()['basic_auth']['username'])
            || empty(Constants::settingsConstant()['basic_auth']['password'])
        ) {
            return null;
        }

        return Constants::settingsConstant()['basic_auth'];
    }
}
