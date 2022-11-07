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
 * Elementor number control.
 *
 * A base control for creating a number control. Displays a simple number input.
 *
 * @since 1.0.0
 */
class ControlNumber extends BaseDataControl
{
    /**
     * Get number control type.
     *
     * Retrieve the control type, in this case `number`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'number';
    }

    /**
     * Get number control default settings.
     *
     * Retrieve the default settings of the number control. Used to return the
     * default settings while initializing the number control.
     *
     * @since 1.5.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'min' => '',
            'max' => '',
            'step' => '',
            'placeholder' => '',
            'title' => '',
        ];
    }

    /**
     * Render number control output in the editor.
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
        $control_uid = $this->getControlUid();
        ?>
        <div class="elementor-control-field">
            <label for="<?= $control_uid ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input id="<?= $control_uid ?>" class="tooltip-target" data-setting="{{ data.name }}"
                    type="number" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}"
                    placeholder="{{ data.placeholder }}" data-tooltip="{{ data.title }}" title="{{ data.title }}">
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
