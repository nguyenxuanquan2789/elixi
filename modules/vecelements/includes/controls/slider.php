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
 * Elementor slider control.
 *
 * A base control for creating slider control. Displays a draggable range slider.
 * The slider control can optionally have a number of unit types (`size_units`)
 * for the user to choose from. The control also accepts a range argument that
 * allows you to set the `min`, `max` and `step` values per unit type.
 *
 * @since 1.0.0
 */
class ControlSlider extends ControlBaseUnits
{
    /**
     * Get slider control type.
     *
     * Retrieve the control type, in this case `slider`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'slider';
    }

    /**
     * Get slider control default values.
     *
     * Retrieve the default value of the slider control. Used to return the default
     * values while initializing the slider control.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return array_merge(parent::getDefaultValue(), [
            'size' => '',
            'sizes' => [],
        ]);
    }

    /**
     * Get slider control default settings.
     *
     * Retrieve the default settings of the slider control. Used to return the
     * default settings while initializing the slider control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return array_merge(parent::getDefaultSettings(), [
            'label_block' => true,
            'labels' => [],
            'scales' => 0,
            'handles' => 'default',
        ]);
    }

    /**
     * Render slider control output in the editor.
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
            <?php $this->printUnitsTemplate() ?>
            <div class="elementor-control-input-wrapper elementor-clearfix">
                <# if ( isMultiple && ( data.labels.length || data.scales ) ) { #>
                    <div class="elementor-slider__extra">
                        <# if ( data.labels.length ) { #>
                            <div class="elementor-slider__labels">
                                <# jQuery.each( data.labels, ( index, label ) => { #>
                                    <div class="elementor-slider__label">{{{ label }}}</div>
                                <# } ); #>
                            </div>
                        <# } if ( data.scales ) { #>
                            <div class="elementor-slider__scales">
                                <# for ( var i = 0; i < data.scales; i++ ) { #>
                                    <div class="elementor-slider__scale"></div>
                                <# } #>
                            </div>
                        <# } #>
                    </div>
                <# } #>
                <div class="elementor-slider"></div>
                <# if ( ! isMultiple ) { #>
                    <div class="elementor-slider-input">
                        <input id="<?= $control_uid ?>" type="number"
                            min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}" data-setting="size">
                    </div>
                <# } #>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
