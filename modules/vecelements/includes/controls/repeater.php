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
 * Elementor repeater control.
 *
 * A base control for creating repeater control. Repeater control allows you to
 * build repeatable blocks of fields. You can create, for example, a set of
 * fields that will contain a title and a WYSIWYG text - the user will then be
 * able to add "rows", and each row will contain a title and a text. The data
 * can be wrapper in custom HTML tags, designed using CSS, and interact using JS
 * or external libraries.
 *
 * @since 1.0.0
 */
class ControlRepeater extends BaseDataControl
{
    /**
     * Get repeater control type.
     *
     * Retrieve the control type, in this case `repeater`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'repeater';
    }

    /**
     * Get repeater control default value.
     *
     * Retrieve the default value of the data control. Used to return the default
     * values while initializing the repeater control.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return [];
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
            'fields' => [],
            'title_field' => '',
            'prevent_empty' => true,
            'is_repeater' => true,
            'item_actions' => [
                'add' => true,
                'duplicate' => true,
                'remove' => true,
                'sort' => true,
            ],
        ];
    }

    /**
     * Get repeater control value.
     *
     * Retrieve the value of the repeater control from a specific Controls_Stack.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $control  Control
     * @param array $settings Controls_Stack settings
     *
     * @return mixed Control values.
     */
    public function getValue($control, $settings)
    {
        $value = parent::getValue($control, $settings);

        if (!empty($value)) {
            foreach ($value as &$item) {
                foreach ($control['fields'] as $field) {
                    $control_obj = Plugin::$instance->controls_manager->getControl($field['type']);

                    // Prior to 1.5.0 the fields may contains non-data controls.
                    if (!$control_obj instanceof BaseDataControl) {
                        continue;
                    }

                    $item[$field['name']] = $control_obj->getValue($field, $item);
                }
            }
        }

        return $value;
    }

    /**
     * Import repeater.
     *
     * Used as a wrapper method for inner controls while importing Elementor
     * template JSON file, and replacing the old data.
     *
     * @since 1.8.0
     * @access public
     *
     * @param array $settings     Control settings.
     * @param array $control_data Optional. Control data. Default is an empty array.
     *
     * @return array Control settings.
     */
    public function onImport($settings, $control_data = [])
    {
        if (empty($settings) || empty($control_data['fields'])) {
            return $settings;
        }

        $method = 'onImport';

        foreach ($settings as &$item) {
            foreach ($control_data['fields'] as $field) {
                if (empty($field['name']) || empty($item[$field['name']])) {
                    continue;
                }

                $control_obj = Plugin::$instance->controls_manager->getControl($field['type']);

                if (!$control_obj) {
                    continue;
                }

                if (method_exists($control_obj, $method)) {
                    $item[$field['name']] = $control_obj->{$method}($item[$field['name']], $field);
                }
            }
        }

        return $settings;
    }

    /**
     * Render repeater control output in the editor.
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
        <label>
            <span class="elementor-control-title">{{{ data.label }}}</span>
        </label>
        <# if ( itemActions.add && itemActions.add.product ) { #>
            <span class="elementor-control-loading">
                <i class="eicon-loading eicon-animation-spin"></i>
            </span>
        <# } #>
        <div class="elementor-repeater-fields-wrapper"></div>
        <# if ( itemActions.add && itemActions.add.product ) { #>
            <div class="elementor-control-input-wrapper">
                <select class="elementor-select2 elementor-repeater-select-product" type="select2"></select>
            </div>
        <# } else if ( itemActions.add ) { #>
            <div class="elementor-button-wrapper">
                <button class="elementor-button elementor-button-default elementor-repeater-add" type="button">
                    <i class="eicon-plus" aria-hidden="true"></i><?= __('Add Item') ?>
                </button>
            </div>
        <# } #>
        <?php
    }
}
