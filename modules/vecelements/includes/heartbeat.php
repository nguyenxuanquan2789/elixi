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
 * Elementor heartbeat.
 *
 * Elementor heartbeat handler class is responsible for initializing Elementor
 * heartbeat. The class communicates with Heartbeat API while working with
 * Elementor.
 *
 * @since 1.0.0
 */
class Heartbeat
{
    /**
     * Heartbeat received.
     *
     * Locks the Heartbeat response received when editing with Elementor.
     *
     * Fired by `heartbeat_received` filter.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $response The Heartbeat response.
     * @param array $data     The POST data sent.
     *
     * @return array Heartbeat response received.
     */
    public function heartbeatReceived($response, $data)
    {
        if (isset($data['elementor_post_lock']['post_ID'])) {
            $post_id = $data['elementor_post_lock']['post_ID'];
            $locked_user = Plugin::$instance->editor->getLockedUser($post_id);

            if (!$locked_user || !empty($data['elementor_force_post_lock'])) {
                Plugin::$instance->editor->lockPost($post_id);
            } else {
                $response['locked_user'] = $locked_user->display_name;
            }

            /** @var Core\Common\Modules\Ajax\Module $ajax */
            $ajax = Plugin::$instance->common->getComponent('ajax');

            $response['elementorNonce'] = $ajax->createNonce();
        }
        return $response;
    }

    /**
     * Refresh nonces.
     *
     * Filter the nonces to send to the editor when editing with Elementor. Used
     * to refresh the nonce when the nonce expires while editing. This way the
     * user doesn't need to log-in again as Elementor fetches the new nonce from
     * the server using ajax.
     *
     * Fired by `wp_refresh_nonces` filter.
     *
     * @since 1.8.0
     * @access public
     *
     * @param array $response The no-priv Heartbeat response object or array.
     * @param array $data     The POST data sent.
     *
     * @return array Refreshed nonces.
     */
    public function refreshNonces($response, $data)
    {
        if (isset($data['elementor_post_lock']['post_ID'])) {
            /** @var Core\Common\Modules\Ajax\Module $ajax */
            $ajax = Plugin::$instance->common->getComponent('ajax');

            $response['elementor-refresh-nonces'] = [
                'elementorNonce' => $ajax->createNonce(),
                'heartbeatNonce' => wp_create_nonce('heartbeat-nonce'),
            ];
        }

        return $response;
    }

    /**
     * Heartbeat constructor.
     *
     * Initializing Elementor heartbeat.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        add_filter('heartbeat_received', [$this, 'heartbeat_received'], 10, 2);
        add_filter('wp_refresh_nonces', [$this, 'refresh_nonces'], 30, 2);
    }
}
