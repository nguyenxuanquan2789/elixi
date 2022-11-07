<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or die;

/**
 * Elementor API.
 *
 * Elementor API handler class is responsible for communicating with Elementor
 * remote servers retrieving templates data and to send uninstall feedback.
 *
 * @since 1.0.0
 */
class Api
{
    /**
     * Elementor library option key.
     */
    const LIBRARY_OPTION_KEY = 'elementor_remote_info_library';

    /**
     * Elementor feed option key.
     */
    const FEED_OPTION_KEY = 'elementor_remote_info_feed_data';

    /**
     * API info URL.
     *
     * Holds the URL of the info API.
     *
     * @access public
     * @static
     *
     * @var string API info URL.
     */
    public static $api_info_url = 'http://pagebuilder.webshopworks.com/?api=2&info';

    // private static $api_feedback_url = 'http://pagebuilder.webshopworks.com/?api=2&feedback';

    /**
     * API get template content URL.
     *
     * Holds the URL of the template content API.
     *
     * @access private
     * @static
     *
     * @var string API get template content URL.
     */
    private static $api_get_template_content_url = 'http://pagebuilder.webshopworks.com/?api=2&template=%d';

    /**
     * Get info data.
     *
     * This function notifies the user of upgrade notices, new templates and contributors.
     *
     * @since 2.0.0
     * @access private
     * @static
     *
     * @param bool $force_update Optional. Whether to force the data retrieval or
     *                                     not. Default is false.
     *
     * @return array|false Info data, or false.
     */
    private static function getInfoData($force_update = false)
    {
        $cache_key = 'elementor_remote_info_api_data_' . _VEC_VERSION_;

        $info_data = get_transient($cache_key);

        if ($force_update || false === $info_data) {
            $timeout = ($force_update) ? 25 : 8;

            $response = wp_remote_get(self::$api_info_url, [
                'timeout' => $timeout,
                'body' => [
                    // Which API version is used.
                    'api_version' => _VEC_VERSION_,
                    // Which language to return.
                    'site_lang' => get_locale(),
                ],
            ]);

            if (empty($response)) {
                set_transient($cache_key, [], 2 * HOUR_IN_SECONDS);

                return false;
            }

            $info_data = json_decode($response, true);

            if (empty($info_data) || !is_array($info_data)) {
                set_transient($cache_key, [], 2 * HOUR_IN_SECONDS);

                return false;
            }

            if (isset($info_data['library'], $info_data['templates'])) {
                $info_data['library']['templates'] = &$info_data['templates'];
                update_post_meta(0, self::LIBRARY_OPTION_KEY, $info_data['library']);

                unset($info_data['library'], $info_data['templates']);
            }

            if (isset($info_data['feed'])) {
                update_post_meta(0, self::FEED_OPTION_KEY, $info_data['feed']);

                unset($info_data['feed']);
            }

            set_transient($cache_key, $info_data, 12 * HOUR_IN_SECONDS);
        }

        return $info_data;
    }

    /**
     * Get upgrade notice.
     *
     * Retrieve the upgrade notice if one exists, or false otherwise.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array|false Upgrade notice, or false none exist.
     */
    public static function getUpgradeNotice()
    {
        $data = self::getInfoData();

        if (empty($data['upgrade_notice'])) {
            return false;
        }

        return $data['upgrade_notice'];
    }

    /**
     * Get templates data.
     *
     * Retrieve the templates data from a remote server.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @param bool $force_update Optional. Whether to force the data update or
     *                                     not. Default is false.
     *
     * @return array The templates data.
     */
    public static function getLibraryData($force_update = false)
    {
        self::getInfoData($force_update);

        $library_data = get_post_meta(0, self::LIBRARY_OPTION_KEY, true);

        if (empty($library_data)) {
            return [];
        }

        return $library_data;
    }

    /**
     * Get feed data.
     *
     * Retrieve the feed info data from remote elementor server.
     *
     * @since 1.9.0
     * @access public
     * @static
     *
     * @param bool $force_update Optional. Whether to force the data update or
     *                                     not. Default is false.
     *
     * @return array Feed data.
     */
    public static function getFeedData($force_update = false)
    {
        self::getInfoData($force_update);

        $feed = get_post_meta(0, self::FEED_OPTION_KEY, true);

        if (empty($feed)) {
            return [];
        }

        return $feed;
    }

    /**
     * Get template content.
     *
     * Retrieve the templates content received from a remote server.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param int $template_id The template ID.
     *
     * @return array The template content.
     */
    public static function getTemplateContent($template_id)
    {
        $url = sprintf(self::$api_get_template_content_url, $template_id);

        $body_args = [
            // Which API version is used.
            'api_version' => _VEC_VERSION_,
            // Which language to return.
            'site_lang' => get_locale(),
        ];

        /**
         * API: Template body args.
         *
         * Filters the body arguments send with the GET request when fetching the content.
         *
         * @since 1.0.0
         *
         * @param array $body_args Body arguments.
         */
        $body_args = apply_filters('elementor/api/get_templates/body_args', $body_args);

        $response = wp_remote_get($url, [
            'timeout' => 40,
            'body' => $body_args,
        ]);

        if (empty($response)) {
            return $response;
        }

        $template_content = json_decode($response, true);
        /**
         * API: Template content.
         *
         * Filters the remote template content.
         *
         * @since 2.5.0
         *
         * @param array $template_content Template content.
         */
        $template_content = apply_filters('elementor/api/get_templates/content', $template_content);

        if (isset($template_content['error'])) {
            return new WPError('response_error', $template_content['error']);
        }

        if (empty($template_content['data']) && empty($template_content['content'])) {
            return new WPError('template_data_error', 'An invalid data was returned.');
        }

        return $template_content;
    }

    // public static function sendFeedback($feedback_key, $feedback_text)

    /**
     * Ajax reset API data.
     *
     * Reset Elementor library API data using an ajax call.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function ajaxResetApiData()
    {
        check_ajax_referer('elementor_reset_library', '_nonce');

        self::getInfoData(true);

        wp_send_json_success();
    }

    /**
     * Init.
     *
     * Initialize Elementor API.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function init()
    {
        add_action('wp_ajax_elementor_reset_library', [__CLASS__, 'ajax_reset_api_data']);
    }
}
