<?php

namespace WackCloudinary;

/**
 * Class BasicAuthHook
 *
 * See: https://github.com/deliciousbrains/wp-background-processing?tab=readme-ov-file#basicauth
 *
 * @package WackCloudinary
 */
final class BasicAuthHook
{
    /**
     * Initialize the hooks
     */
    public function init()
    {
        add_filter('http_request_args', [$this, 'httpRequestArgs'], 10, 2);
    }

    /**
     * Add basic auth to the HTTP request
     *
     * @param array $parsed_args An array of HTTP request arguments.
     * @param string $url The request URL.
     *
     * @return array The modified array of HTTP request arguments.
     */
    public function httpRequestArgs(array $parsed_args, string $url)
    {
        $basicAuth = PluginSettings::get()->basicAuth();

        if (!$basicAuth) {
            return $parsed_args;
        }

        $parsed_args['headers']['Authorization'] = 'Basic ' . base64_encode($basicAuth['username'] . ':' . $basicAuth['password']);

        return $parsed_args;
    }
}
