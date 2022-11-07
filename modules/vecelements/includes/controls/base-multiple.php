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
 * Elementor control base multiple.
 *
 * An abstract class for creating new controls in the panel that return
 * more than a single value. Each value of the multi-value control will
 * be returned as an item in a `key => value` array.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class ControlBaseMultiple extends BaseDataControl
{
    /**
     * Get multiple control default value.
     *
     * Retrieve the default value of the multiple control. Used to return the default
     * values while initializing the multiple control.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return [];
    }

    /**
     * Get multiple control value.
     *
     * Retrieve the value of the multiple control from a specific Controls_Stack settings.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $control  Control
     * @param array $settings Settings
     *
     * @return mixed Control values.
     */
    public function getValue($control, $settings)
    {
        $value = parent::getValue($control, $settings);

        if (empty($control['default'])) {
            $control['default'] = [];
        }

        if (!is_array($value)) {
            $value = [];
        }

        $control['default'] = array_merge(
            $this->getDefaultValue(),
            $control['default']
        );

        return array_merge(
            $control['default'],
            $value
        );
    }

    /**
     * Get multiple control style value.
     *
     * Retrieve the style of the control. Used when adding CSS rules to the control
     * while extracting CSS from the `selectors` data argument.
     *
     * @since 1.0.5
     * @since 2.3.3 New `$control_data` parameter added.
     * @access public
     *
     * @param string $css_property  CSS property.
     * @param array $control_value Control value.
     * @param array  $control_data Control Data.
     *
     * @return array Control style value.
     */
    public function getStyleValue($css_property, $control_value, array $control_data)
    {
        return $control_value[call_user_func('strtolower', $css_property)];
    }
}
