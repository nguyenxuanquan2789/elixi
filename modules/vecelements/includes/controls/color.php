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
 * Elementor color control.
 *
 * A base control for creating color control. Displays a color picker field with
 * an alpha slider. Includes a customizable color palette that can be preset by
 * the user. Accepts a `scheme` argument that allows you to set a value from the
 * active color scheme as the default value returned by the control.
 *
 * @since 1.0.0
 */
class ControlColor extends BaseDataControl
{
    /**
     * Get color control type.
     *
     * Retrieve the control type, in this case `color`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'color';
    }

    /**
     * Enqueue color control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the color
     * control.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueue()
    {
        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        wp_register_script(
            'wp-color-picker-alpha',
            _VEC_ASSETS_URL_ . 'lib/wp-color-picker/wp-color-picker-alpha' . $suffix . '.js',
            [
                'wp-color-picker',
            ],
            '2.0.1',
            true
        );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker-alpha');
    }

    /**
     * Render color control output in the editor.
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
        <#
        var defaultValue = data.default ? ' data-default-color=' + data.default : '',
            dataAlpha = data.alpha ? ' data-alpha=true' : '';
        #>
        <div class="elementor-control-field">
            <label class="elementor-control-title">
                <# if ( data.label ) { #>
                    {{{ data.label }}}
                <# } #>
                <# if ( data.description ) { #>
                    <span class="elementor-control-field-description">{{{ data.description }}}</span>
                <# } #>
            </label>
            <div class="elementor-control-input-wrapper">
                <input data-setting="{{ name }}" type="text" placeholder="<?= esc_attr__('Hex/rgba') ?>"
                    {{ defaultValue }}{{ dataAlpha }}>
            </div>
        </div>
        <?php
    }

    /**
     * Get color control default settings.
     *
     * Retrieve the default settings of the color control. Used to return the default
     * settings while initializing the color control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'alpha' => true,
            'scheme' => '',
        ];
    }
}
