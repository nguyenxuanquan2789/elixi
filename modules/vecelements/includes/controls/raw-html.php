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
 * Elementor raw HTML control.
 *
 * A base control for creating raw HTML control. Displays HTML markup between
 * controls in the panel.
 *
 * @since 1.0.0
 */
class ControlRawHtml extends BaseUIControl
{
    /**
     * Get raw html control type.
     *
     * Retrieve the control type, in this case `raw_html`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'raw_html';
    }

    /**
     * Render raw html control output in the editor.
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
        ?>
        <# if ( data.label ) { #>
            <span class="elementor-control-title">{{{ data.label }}}</span>
        <# } #>
        <div class="elementor-control-raw-html {{ data.content_classes }}">{{{ data.raw }}}</div>
        <?php
    }

    /**
     * Get raw html control default settings.
     *
     * Retrieve the default settings of the raw html control. Used to return the
     * default settings while initializing the raw html control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'raw' => '',
            'content_classes' => '',
        ];
    }
}
