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
 * Base App
 *
 * Base app utility class that provides shared functionality of apps.
 *
 * @since 2.3.0
 */
abstract class CoreXBaseXApp extends CoreXBaseXModule
{
    /**
     * Print config.
     *
     * Used to print the app and its components settings as a JavaScript object.
     *
     * @since 2.3.0
     * @access protected
     */
    final protected function printConfig()
    {
        $name = $this->getName();

        $js_var = 'frontend' === $name ? 'ceFrontendConfig' : 'elementor' . call_user_func('ucfirst', $name) . 'Config';
        $config = $this->getSettings() + $this->getComponentsConfig();
        wp_localize_script('elementor-' . $name, $js_var, $config);
    }

    /**
     * Get components config.
     *
     * Retrieves the app components settings.
     *
     * @since 2.3.0
     * @access private
     *
     * @return array
     */
    private function getComponentsConfig()
    {
        $settings = [];

        foreach ($this->getComponents() as $id => $instance) {
            $settings[$id] = $instance->getSettings();
        }

        return $settings;
    }
}
