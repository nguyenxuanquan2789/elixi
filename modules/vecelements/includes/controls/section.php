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
 * Elementor section control.
 *
 * A base control for creating section control. Displays a header that
 * functions as a toggle to show or hide a set of controls.
 *
 * Note: Do not use it directly, instead use `$widget->startControlsSection()`
 * and `$widget->endControlsSection()` to wrap a set of controls.
 *
 * @since 1.0.0
 */
class ControlSection extends BaseUIControl
{
    /**
     * Get section control type.
     *
     * Retrieve the control type, in this case `section`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'section';
    }

    /**
     * Render section control output in the editor.
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
        <div class="elementor-panel-heading">
            <div class="elementor-panel-heading-toggle elementor-section-toggle" data-collapse_id="{{ data.name }}">
                <i class="fa" aria-hidden="true"></i>
            </div>
            <div class="elementor-panel-heading-title elementor-section-title">{{{ data.label }}}</div>
        </div>
        <?php
    }

    /**
     * Get repeater control default settings.
     *
     * Retrieve the default settings of the repeater control. Used to return the
     * default settings while initializing the repeater control.
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
