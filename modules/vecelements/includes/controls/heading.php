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
 * Elementor heading control.
 *
 * A base control for creating heading control. Displays a text heading between
 * controls in the panel.
 *
 * @since 1.0.0
 */
class ControlHeading extends BaseUIControl
{
    /**
     * Get heading control type.
     *
     * Retrieve the control type, in this case `heading`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'heading';
    }

    /**
     * Get heading control default settings.
     *
     * Retrieve the default settings of the heading control. Used to return the
     * default settings while initializing the heading control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'label_block' => true,
        ];
    }

    /**
     * Render heading control output in the editor.
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
        <div class="elementor-control-field">
            <h3 class="elementor-control-title">{{ data.label }}</h3>
        </div>
        <?php
    }
}
