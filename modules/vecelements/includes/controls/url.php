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
 * Elementor URL control.
 *
 * A base control for creating url control. Displays a URL input with the
 * ability to set the target of the link to `_blank` to open in a new tab.
 *
 * @since 1.0.0
 */
class ControlUrl extends ControlBaseMultiple
{
    /**
     * Get url control type.
     *
     * Retrieve the control type, in this case `url`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'url';
    }

    /**
     * Get url control default values.
     *
     * Retrieve the default value of the url control. Used to return the default
     * values while initializing the url control.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Control default value.
     */
    public function getDefaultValue()
    {
        return [
            'url' => '',
            'is_external' => '',
            'nofollow' => '',
        ];
    }

    /**
     * Get url control default settings.
     *
     * Retrieve the default settings of the url control. Used to return the default
     * settings while initializing the url control.
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
            'show_external' => true,
            'placeholder' => __('Paste URL or type'),
            'autocomplete' => true,
            'dynamic' => [
                // 'categories' => [TagsModule::URL_CATEGORY],
                'property' => 'url',
            ],
        ];
    }

    /**
     * Render url control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.0.0
     * @access public
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    public function contentTemplate()
    {
        $control_uid = $this->getControlUid();

        $more_input_control_uid = $this->getControlUid('more-input');
        $is_external_control_uid = $this->getControlUid('is_external');
        $nofollow_control_uid = $this->getControlUid('nofollow');
        ?>
        <div class="elementor-control-field elementor-control-url-external-{{{ data.show_external ? 'show' : 'hide' }}}">
            <label for="<?= $control_uid ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <i class="elementor-control-url-autocomplete-spinner fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
                <input id="<?= $control_uid ?>" class="elementor-control-tag-area elementor-input" data-setting="url" placeholder="{{ data.placeholder }}">
                <label for="<?= $more_input_control_uid ?>" class="elementor-control-url-more tooltip-target" data-tooltip="<?= __('Link Options') ?>">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </label>
                <input id="<?= $more_input_control_uid ?>" type="checkbox" class="elementor-control-url-more-input">
                <div class="elementor-control-url-more-options">
                    <div class="elementor-control-url-option">
                        <input id="<?= $is_external_control_uid ?>" type="checkbox" class="elementor-control-url-option-input" data-setting="is_external">
                        <label for="<?= $is_external_control_uid ?>"><?= __('Open in new window') ?></label>
                    </div>
                    <div class="elementor-control-url-option">
                        <input id="<?= $nofollow_control_uid ?>" type="checkbox" class="elementor-control-url-option-input" data-setting="nofollow">
                        <label for="<?= $nofollow_control_uid ?>"><?= __('Add nofollow') ?></label>
                    </div>
                </div>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
