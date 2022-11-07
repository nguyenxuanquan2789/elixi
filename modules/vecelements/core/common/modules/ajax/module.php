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

use VEC\CoreXBaseXModule as BaseModule;
use VEC\CoreXUtilsXExceptions as Exceptions;

/**
 * Elementor ajax manager.
 *
 * Elementor ajax manager handler class is responsible for handling Elementor
 * ajax requests, ajax responses and registering actions applied on them.
 *
 * @since 2.0.0
 */
class CoreXCommonXModulesXAjaxXModule extends BaseModule
{
    const NONCE_KEY = 'elementor_ajax';

    /**
     * Ajax actions.
     *
     * Holds all the register ajax action.
     *
     * @since 2.0.0
     * @access private
     *
     * @var array
     */
    private $ajax_actions = [];

    /**
     * Ajax requests.
     *
     * Holds all the register ajax requests.
     *
     * @since 2.0.0
     * @access private
     *
     * @var array
     */
    private $requests = [];

    /**
     * Ajax response data.
     *
     * Holds all the response data for all the ajax requests.
     *
     * @since 2.0.0
     * @access private
     *
     * @var array
     */
    private $response_data = [];

    /**
     * Current ajax action ID.
     *
     * Holds all the ID for the current ajax action.
     *
     * @since 2.0.0
     * @access private
     *
     * @var string|null
     */
    private $current_action_id = null;

    /**
     * Ajax manager constructor.
     *
     * Initializing Elementor ajax manager.
     *
     * @since 2.0.0
     * @access public
     */
    public function __construct()
    {
        add_action('wp_ajax_elementor_ajax', [$this, 'handle_ajax_request']);
    }

    /**
     * Get module name.
     *
     * Retrieve the module name.
     *
     * @since  1.7.0
     * @access public
     *
     * @return string Module name.
     */
    public function getName()
    {
        return 'ajax';
    }

    /**
     * Register ajax action.
     *
     * Add new actions for a specific ajax request and the callback function to
     * be handle the response.
     *
     * @since 2.0.0
     * @access public
     *
     * @param string   $tag      Ajax request name/tag.
     * @param callable $callback The callback function.
     */
    public function registerAjaxAction($tag, $callback)
    {
        if (!did_action('elementor/ajax/register_actions')) {
            _doing_it_wrong(__METHOD__, esc_html(sprintf('Use `%s` hook to register ajax action.', 'elementor/ajax/register_actions')), '2.0.0');
        }

        if (is_array($callback) && isset($callback[1]) && strpos($callback[1], '_') !== false) {
            $callback[1] = \Tools::toCamelCase($callback[1]);
        }

        $this->ajax_actions[$tag] = compact('tag', 'callback');
    }

    /**
     * Handle ajax request.
     *
     * Verify ajax nonce, and run all the registered actions for this request.
     *
     * Fired by `wp_ajax_elementor_ajax` action.
     *
     * @since 2.0.0
     * @access public
     */
    public function handleAjaxRequest()
    {
        if (!$this->verifyRequestNonce()) {
            $this->addResponseData(false, __('Token Expired.'))
                ->sendError(Exceptions::UNAUTHORIZED);
        }

        $editor_post_id = 0;

        if (!empty($_REQUEST['editor_post_id'])) {
            $editor_post_id = absint($_REQUEST['editor_post_id']);

            Plugin::$instance->db->switchToPost($editor_post_id);
        }

        /**
         * Register ajax actions.
         *
         * Fires when an ajax request is received and verified.
         *
         * Used to register new ajax action handles.
         *
         * @since 2.0.0
         *
         * @param self $this An instance of ajax manager.
         */
        do_action('elementor/ajax/register_actions', $this);

        $this->requests = json_decode(${'_POST'}['actions'], true);

        foreach ($this->requests as $id => $action_data) {
            $this->current_action_id = $id;

            if (!isset($this->ajax_actions[$action_data['action']])) {
                $this->addResponseData(false, __('Action not found.'), Exceptions::BAD_REQUEST);

                continue;
            }

            if ($editor_post_id) {
                $action_data['data']['editor_post_id'] = $editor_post_id;
            }

            // try {
            $results = call_user_func($this->ajax_actions[$action_data['action']]['callback'], $action_data['data'], $this);

            if (false === $results) {
                $this->addResponseData(false);
            } else {
                $this->addResponseData(true, $results);
            }
            // } catch (\Exception $e) {
            //     $this->addResponseData(false, $e->getMessage(), $e->getCode());
            // }
        }

        $this->current_action_id = null;

        $this->sendSuccess();
    }

    /**
     * Get current action data.
     *
     * Retrieve the data for the current ajax request.
     *
     * @since 2.0.1
     * @access public
     *
     * @return bool|mixed Ajax request data if action exist, False otherwise.
     */
    public function getCurrentActionData()
    {
        if (!$this->current_action_id) {
            return false;
        }

        return $this->requests[$this->current_action_id];
    }

    /**
     * Create nonce.
     *
     * Creates a cryptographic token to
     * give the user an access to Elementor ajax actions.
     *
     * @since 2.3.0
     * @access public
     *
     * @return string The nonce token.
     */
    public function createNonce()
    {
        return wp_create_nonce(self::NONCE_KEY);
    }

    /**
     * Verify request nonce.
     *
     * Whether the request nonce verified or not.
     *
     * @since 2.3.0
     * @access public
     *
     * @return bool True if request nonce verified, False otherwise.
     */
    public function verifyRequestNonce()
    {
        return !empty($_REQUEST['_nonce']) && wp_verify_nonce($_REQUEST['_nonce'], self::NONCE_KEY);
    }

    protected function getInitSettings()
    {
        return [
            'url' => Helper::getAjaxLink(),
            'nonce' => $this->createNonce(),
        ];
    }

    /**
     * Ajax success response.
     *
     * Send a JSON response data back to the ajax request, indicating success.
     *
     * @since 2.0.0
     * @access private
     */
    private function sendSuccess()
    {
        $response = [
            'success' => true,
            'data' => [
                'responses' => $this->response_data,
            ],
        ];

        die(json_encode($response));
    }

    /**
     * Ajax failure response.
     *
     * Send a JSON response data back to the ajax request, indicating failure.
     *
     * @since 2.0.0
     * @access private
     *
     * @param null $code
     */
    private function sendError($code = null)
    {
        wp_send_json_error([
            'responses' => $this->response_data,
        ], $code);
    }

    /**
     * Add response data.
     *
     * Add new response data to the array of all the ajax requests.
     *
     * @since 2.0.0
     * @access private
     *
     * @param bool  $success True if the requests returned successfully, False otherwise.
     * @param mixed $data    Optional. Response data. Default is null.
     * @param int   $code    Optional. Response code. Default is 200.
     *
     * @return Module An instance of ajax manager.
     */
    private function addResponseData($success, $data = null, $code = 200)
    {
        $this->response_data[$this->current_action_id] = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
        ];

        return $this;
    }
}
