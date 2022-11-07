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
 * Elementor tabs control.
 *
 * A base control for creating tabs control. Displays a tabs header for `tab`
 * controls.
 *
 * Note: Do not use it directly, instead use: `$widget->startControlsTabs()`
 * and in the end `$widget->endControlsTabs()`.
 *
 * @since 1.0.0
 */
class ControlTabs extends BaseUIControl
{
    /**
     * Get tabs control type.
     *
     * Retrieve the control type, in this case `tabs`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'tabs';
    }

    /**
     * Render tabs control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     */
    public function contentTemplate()
    {
    }

    /**
     * Get tabs control default settings.
     *
     * Retrieve the default settings of the tabs control. Used to return the
     * default settings while initializing the tabs control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'separator' => 'none',
        ];
    }
}
