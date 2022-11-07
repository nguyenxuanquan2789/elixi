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
 * Elementor popover toggle control.
 *
 * A base control for creating a popover toggle control. By default displays a toggle
 * button to open and close a popover.
 *
 * @since 1.9.0
 */
class ControlPopoverToggle extends BaseDataControl
{
    /**
     * Get popover toggle control type.
     *
     * Retrieve the control type, in this case `popover_toggle`.
     *
     * @since 1.9.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'popover_toggle';
    }

    /**
     * Get popover toggle control default settings.
     *
     * Retrieve the default settings of the popover toggle control. Used to
     * return the default settings while initializing the popover toggle
     * control.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'return_value' => 'yes',
        ];
    }

    /**
     * Render popover toggle control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.9.0
     * @access public
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid();
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input id="<?= $control_uid ?>-custom" class="elementor-control-popover-toggle-toggle" type="radio"
                    name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ data.return_value }}">
                <label class="elementor-control-popover-toggle-toggle-label" for="<?= $control_uid ?>-custom">
                    <i class="eicon-edit" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?= __('Edit') ?></span>
                </label>
                <input id="<?= $control_uid ?>-default" type="radio"
                    name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="">
                <label class="elementor-control-popover-toggle-reset-label tooltip-target"
                    for="<?= $control_uid ?>-default" data-tooltip="<?= __('Back to default') ?>" data-tooltip-pos="s">
                    <i class="fa fa-repeat" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?= __('Back to default') ?></span>
                </label>
            </div>
        </div>
        <?php
    }
}
