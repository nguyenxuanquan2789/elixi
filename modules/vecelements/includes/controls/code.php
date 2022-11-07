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
 * Elementor code control.
 *
 * A base control for creating code control. Displays a code editor textarea.
 * Based on Ace editor (@see https://ace.c9.io/).
 *
 * @since 1.0.0
 */
class ControlCode extends BaseDataControl
{
    /**
     * Get code control type.
     *
     * Retrieve the control type, in this case `code`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'code';
    }

    /**
     * Get code control default settings.
     *
     * Retrieve the default settings of the code control. Used to return the default
     * settings while initializing the code control.
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
            'language' => 'html', // html/css
            'rows' => 10,
        ];
    }

    /**
     * Render code control output in the editor.
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
                <textarea id="<?= $control_uid ?>" class="elementor-input-style elementor-code-editor"
                    rows="{{ data.rows }}" data-setting="{{ data.name }}"></textarea>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
