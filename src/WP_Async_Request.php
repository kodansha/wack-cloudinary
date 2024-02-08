<?php

// phpcs:ignoreFile -- This is a copy of the original file from the WP Background Processing plugin

namespace WackCloudinary;

/**
 * WP Async Request
 *
 * To prevent collisions with other projects using this same library, packages the namespaced library class files into this plugin.
 *
 * Library URI: https://github.com/deliciousbrains/wp-background-processing/blob/93ba7781eedd0faaea73f4e1e0b8a7694b7f28b6/classes/wp-async-request.php
 * Author: Delicious Brains Inc.
 * Author URI: https://deliciousbrains.com/
 * License: GNU General Public License v2.0
 * License URI: https://github.com/deliciousbrains/wp-background-processing/commit/126d7945dd3d39f39cb6488ca08fe1fb66cb351a
 *
 * @package WackCloudinary
 */

/**
 * Abstract WP_Async_Request class.
 *
 * @abstract
 */
abstract class WP_Async_Request
{
    /**
     * Prefix
     *
     * (default value: 'wp')
     *
     * @var    string
     * @access protected
     */
    protected $prefix = 'wp';

    /**
     * Action
     *
     * (default value: 'async_request')
     *
     * @var    string
     * @access protected
     */
    protected $action = 'async_request';

    /**
     * Identifier
     *
     * @var    mixed
     * @access protected
     */
    protected $identifier;

    /**
     * Data
     *
     * (default value: array())
     *
     * @var    array
     * @access protected
     */
    protected $data = [];

    /**
     * Initiate new async request.
     */
    public function __construct()
    {
        $this->identifier = $this->prefix . '_' . $this->action;

        add_action('wp_ajax_' . $this->identifier, [ $this, 'maybe_handle' ]);
        add_action('wp_ajax_nopriv_' . $this->identifier, [ $this, 'maybe_handle' ]);
    }

    /**
     * Set data used during the request.
     *
     * @param array $data Data.
     *
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Dispatch the async request.
     *
     * @return array|WP_Error|false HTTP Response array, WP_Error on failure, or false if not attempted.
     */
    public function dispatch()
    {
        $url  = add_query_arg($this->get_query_args(), $this->get_query_url());
        $args = $this->get_post_args();

        return wp_remote_post(esc_url_raw($url), $args);
    }

    /**
     * Get query args.
     *
     * @return array
     */
    protected function get_query_args()
    {
        if (property_exists($this, 'query_args')) {
            return $this->query_args;
        }

        $args = [
            'action' => $this->identifier,
            'nonce'  => wp_create_nonce($this->identifier),
        ];

        /**
         * Filters the post arguments used during an async request.
         *
         * @param array $url
         */
        return apply_filters($this->identifier . '_query_args', $args);
    }

    /**
     * Get query URL.
     *
     * @return string
     */
    protected function get_query_url()
    {
        if (property_exists($this, 'query_url')) {
            return $this->query_url;
        }

        $url = admin_url('admin-ajax.php');

        /**
         * Filters the post arguments used during an async request.
         *
         * @param string $url
         */
        return apply_filters($this->identifier . '_query_url', $url);
    }

    /**
     * Get post args.
     *
     * @return array
     */
    protected function get_post_args()
    {
        if (property_exists($this, 'post_args')) {
            return $this->post_args;
        }

        $args = [
            'timeout'   => 5,
            'blocking'  => false,
            'body'      => $this->data,
            'cookies'   => $_COOKIE, // Passing cookies ensures request is performed as initiating user.
            'sslverify' => apply_filters('https_local_ssl_verify', false), // Local requests, fine to pass false.
        ];

        /**
         * Filters the post arguments used during an async request.
         *
         * @param array $args
         */
        return apply_filters($this->identifier . '_post_args', $args);
    }

    /**
     * Maybe handle a dispatched request.
     *
     * Check for correct nonce and pass to handler.
     *
     * @return void|mixed
     */
    public function maybe_handle()
    {
        // Don't lock up other requests while processing.
        session_write_close();

        check_ajax_referer($this->identifier, 'nonce');

        $this->handle();

        return $this->maybe_wp_die();
    }

    /**
     * Should the process exit with wp_die?
     *
     * @param mixed $return What to return if filter says don't die, default is null.
     *
     * @return void|mixed
     */
    protected function maybe_wp_die($return = null)
    {
        /**
         * Should wp_die be used?
         *
         * @return bool
         */
        if (apply_filters($this->identifier . '_wp_die', true)) {
            wp_die();
        }

        return $return;
    }

    /**
     * Handle a dispatched request.
     *
     * Override this method to perform any actions required
     * during the async request.
     */
    abstract protected function handle();
}
