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

// use VEC\ModulesXDynamicTagsXModule as TagsModule;

/**
 * Elementor textarea control.
 *
 * A base control for creating textarea control. Displays a classic textarea.
 *
 * @since 1.0.0
 */
class ControlTextarea extends BaseDataControl
{
    /**
     * Get textarea control type.
     *
     * Retrieve the control type, in this case `textarea`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'textarea';
    }

    /**
     * Get textarea control default settings.
     *
     * Retrieve the default settings of the textarea control. Used to return the
     * default settings while initializing the textarea control.
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
            'rows' => 5,
            'placeholder' => '',
            // 'dynamic' => [
            //     'categories' => [TagsModule::TEXT_CATEGORY],
            // ],
        ];
    }

    /**
     * Render textarea control output in the editor.
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
                <textarea id="<?= $control_uid ?>" class="elementor-control-tag-area" rows="{{ data.rows }}"
                    data-setting="{{ data.name }}" placeholder="{{ data.placeholder }}"></textarea>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
