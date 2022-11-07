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
 * Elementor structure control.
 *
 * A base control for creating structure control. A private control for section
 * columns structure.
 *
 * @since 1.0.0
 */
class ControlStructure extends BaseDataControl
{
    /**
     * Get structure control type.
     *
     * Retrieve the control type, in this case `structure`.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'structure';
    }

    /**
     * Render structure control output in the editor.
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
        $preset_control_uid = $this->getControlUid('{{ preset.key }}');
        ?>
        <# var morePresets = getMorePresets(); #>
        <div class="elementor-control-field">
            <div class="elementor-control-input-wrapper">
                <div class="elementor-control-structure-title"><?= __('Structure') ?></div>
                <# var currentPreset = elementor.presetsFactory.getPresetByStructure( data.controlValue ); #>
                <div class="elementor-control-structure-preset elementor-control-structure-current-preset">
                    {{{ elementor.presetsFactory.getPresetSVG( currentPreset.preset, 233, 72, 5 ).outerHTML }}}
                </div>
                <div class="elementor-control-structure-reset">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    <?= __('Reset Structure') ?>
                </div>
            <# if ( morePresets.length > 1 ) { #>
                <div class="elementor-control-structure-more-presets-title"><?= __('More Structures') ?></div>
                <div class="elementor-control-structure-more-presets">
                <# _.each( morePresets, function( preset ) { #>
                    <div class="elementor-control-structure-preset-wrapper">
                        <input id="<?= $preset_control_uid ?>" type="radio" data-setting="structure"
                            name="elementor-control-structure-preset-{{ data._cid }}" value="{{ preset.key }}">
                        <label for="<?= $preset_control_uid ?>" class="elementor-control-structure-preset">
                            {{{ elementor.presetsFactory.getPresetSVG( preset.preset, 102, 42 ).outerHTML }}}
                        </label>
                        <div class="elementor-control-structure-preset-title">{{{ preset.preset.join( ', ' ) }}}</div>
                    </div>
                <# } ); #>
                </div>
            <# } #>
            </div>
        </div>

        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * Get structure control default settings.
     *
     * Retrieve the default settings of the structure control. Used to return the
     * default settings while initializing the structure control.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [
            'separator' => 'none',
            'label_block' => true,
        ];
    }
}
