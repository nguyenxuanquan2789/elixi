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
 * Elementor text shadow control.
 *
 * A base control for creating text shadows control. Displays input fields for
 * horizontal shadow, vertical shadow, shadow blur and shadow color.
 *
 * @since 1.6.0
 */
class ControlTextShadow extends ControlBaseMultiple
{
    /**
     * Get text shadow control type.
     *
     * Retrieve the control type, in this case `text_shadow`.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'text_shadow';
    }

    /**
     * Get text shadow control default values.
     *
     * Retrieve the default value of the text shadow control. Used to return the
     * default values while initializing the text shadow control.
     *
     * @since 1.6.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return [
            'horizontal' => 0,
            'vertical' => 0,
            'blur' => 10,
            'color' => 'rgba(0,0,0,0.3)',
        ];
    }

    /**
     * Get text shadow control sliders.
     *
     * Retrieve the sliders of the text shadow control. Sliders are used while
     * rendering the control output in the editor.
     *
     * @since 1.6.0
     * @access public
     *
     * @return array Control sliders.
     */
    public function getSliders()
    {
        return [
            'blur' => [
                'label' => __('Blur'),
                'min' => 0,
                'max' => 100,
            ],
            'horizontal' => [
                'label' => __('Horizontal'),
                'min' => -100,
                'max' => 100,
            ],
            'vertical' => [
                'label' => __('Vertical'),
                'min' => -100,
                'max' => 100,
            ],
        ];
    }

    /**
     * Render text shadow control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.6.0
     * @access public
     */
    public function contentTemplate()
    {
        ?>
        <# var defaultColorValue = data.default.color ? ' data-default-color=' + data.default.color : ''; #>
        <div class="elementor-control-field">
            <label class="elementor-control-title"><?= __('Color') ?></label>
            <div class="elementor-control-input-wrapper">
                <input data-setting="color" class="elementor-shadow-color-picker" type="text"
                    placeholder="<?= esc_attr__('Hex/rgba') ?>" data-alpha="true"{{{ defaultColorValue }}}>
            </div>
        </div>
        <?php foreach ($this->getSliders() as $slider_name => $slider) : ?>
            <?php $control_uid = $this->getControlUid($slider_name) ?>
            <div class="elementor-shadow-slider elementor-control-type-slider">
                <label for="<?= esc_attr($control_uid) ?>" class="elementor-control-title">
                    <?= $slider['label'] ?>
                </label>
                <div class="elementor-control-input-wrapper">
                    <div class="elementor-slider" data-input="<?= esc_attr($slider_name) ?>"></div>
                    <div class="elementor-slider-input">
                        <input id="<?= esc_attr($control_uid) ?>" data-setting="<?= esc_attr($slider_name) ?>"
                            type="number" min="<?= esc_attr($slider['min']) ?>" max="<?= esc_attr($slider['max']) ?>">
                    </div>
                </div>
            </div>
        <?php endforeach;
    }
}
