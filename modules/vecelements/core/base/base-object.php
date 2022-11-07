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
 * Base Object
 *
 * Base class that provides basic settings handling functionality.
 *
 * @since 2.3.0
 */
class CoreXBaseXBaseObject
{
    /**
     * Settings.
     *
     * Holds the object settings.
     *
     * @access private
     *
     * @var array
     */
    private $settings;

    /**
     * Get Settings.
     *
     * @since 2.3.0
     * @access public
     *
     * @param string $setting Optional. The key of the requested setting. Default is null.
     *
     * @return mixed An array of all settings, or a single value if `$setting` was specified.
     */
    final public function getSettings($setting = null)
    {
        $this->ensureSettings();

        return self::getItems($this->settings, $setting);
    }

    /**
     * Set settings.
     *
     * @since 2.3.0
     * @access public
     *
     * @param array|string $key   If key is an array, the settings are overwritten by that array. Otherwise, the
     *                            settings of the key will be set to the given `$value` param.
     *
     * @param mixed        $value Optional. Default is null.
     */
    final public function setSettings($key, $value = null)
    {
        $this->ensureSettings();

        if (is_array($key)) {
            $this->settings = $key;
        } else {
            $this->settings[$key] = $value;
        }
    }

    /**
     * Delete setting.
     *
     * Deletes the settings array or a specific key of the settings array if `$key` is specified.
     * @since 2.3.0
     * @access public
     *
     * @param string $key Optional. Default is null.
     */
    public function deleteSetting($key = null)
    {
        if ($key) {
            unset($this->settings[$key]);
        } else {
            $this->settings = [];
        }
    }

    /**
     * Get items.
     *
     * Utility method that receives an array with a needle and returns all the
     * items that match the needle. If needle is not defined the entire haystack
     * will be returned.
     *
     * @since 2.3.0
     * @access protected
     * @static
     *
     * @param array  $haystack An array of items.
     * @param string $needle   Optional. Needle. Default is null.
     *
     * @return mixed The whole haystack or the needle from the haystack when requested.
     */
    final protected static function getItems(array $haystack, $needle = null)
    {
        if ($needle) {
            return isset($haystack[$needle]) ? $haystack[$needle] : null;
        }

        return $haystack;
    }

    /**
     * Get init settings.
     *
     * Used to define the default/initial settings of the object. Inheriting classes may implement this method to define
     * their own default/initial settings.
     *
     * @since 2.3.0
     * @access protected
     *
     * @return array
     */
    protected function getInitSettings()
    {
        return [];
    }

    /**
     * Ensure settings.
     *
     * Ensures that the `$settings` member is initialized
     *
     * @since 2.3.0
     * @access private
     */
    private function ensureSettings()
    {
        if (null === $this->settings) {
            $this->settings = $this->getInitSettings();
        }
    }
}
