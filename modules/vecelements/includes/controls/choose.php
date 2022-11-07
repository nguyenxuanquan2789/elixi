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
 * Elementor choose control.
 *
 * A base control for creating choose control. Displays radio buttons styled as
 * groups of buttons with icons for each option.
 *
 * @since 1.0.0
 */
class ControlChoose extends BaseDataControl
{
    /**
     * Get choose control type.
     *
     * Retrieve the control type, in this case `choose`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'choose';
    }

    /**
     * Render choose control output in the editor.
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
        $control_uid = $this->getControlUid('{{value}}');
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <div class="elementor-choices">
                    <# _.each( data.options, function( options, value ) { #>
                    <input id="<?= $control_uid ?>" type="radio"
                        name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
                    <label class="elementor-choices-label tooltip-target" for="<?= $control_uid ?>"
                        data-tooltip="{{ options.title }}" title="{{ options.title }}">
                        <i class="{{ options.icon }}" aria-hidden="true"></i>
                        <span class="elementor-screen-only">{{{ options.title }}}</span>
                    </label>
                    <# } ); #>
                </div>
            </div>
        </div>

        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get choose control default settings.
     *
     * Retrieve the default settings of the choose control. Used to return the
     * default settings while initializing the choose control.
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
            'options' => [],
            'toggle' => true,
        ];
    }
}
