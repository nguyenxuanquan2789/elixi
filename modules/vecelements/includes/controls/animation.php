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
 * Elementor animation control.
 *
 * A base control for creating entrance animation control. Displays a select box
 * with the available entrance animation effects @see ControlAnimation::getAnimations() .
 *
 * @since 1.0.0
 */
class ControlAnimation extends BaseDataControl
{
    /**
     * Get control type.
     *
     * Retrieve the animation control type.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Control type.
     */
    public function getType()
    {
        return 'animation';
    }

    /**
     * Retrieve default control settings.
     *
     * Get the default settings of the control. Used to return the default
     * settings while initializing the control.
     *
     * @since 2.5.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        $default_settings = parent::getDefaultSettings();

        $default_settings['label_block'] = true;
        $default_settings['render_type'] = 'none';

        return $default_settings;
    }

    /**
     * Get animations list.
     *
     * Retrieve the list of all the available animations.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array Control type.
     */
    public static function getAnimations()
    {
        $animations = [
            'Fading' => [
                'fadeIn' => 'Fade In',
                'fadeInDown' => 'Fade In Down',
                'fadeInLeft' => 'Fade In Left',
                'fadeInRight' => 'Fade In Right',
                'fadeInUp' => 'Fade In Up',
                'fadeInUpLight' => 'Fade In Up Light',
                'fadeInDownLight' => 'Fade In Down Light',
                'fadeInRightLight' => 'Fade In Right Light',
                'fadeInLeftLight' => 'Fade In Left Light',
                'fadeInRightSmall' => 'Fade In Right Small',
                'fadeInLeftSmall' => 'Fade In Left Small',
            ],
            'Zooming' => [
                'zoomIn' => 'Zoom In',
                'zoomOut' => 'Zoom Out', 
                'zoomInDown' => 'Zoom In Down',
                'zoomInLeft' => 'Zoom In Left',
                'zoomInRight' => 'Zoom In Right',
                'zoomInUp' => 'Zoom In Up',
            ],
            'Bouncing' => [
                'bounceIn' => 'Bounce In',
                'bounceInDown' => 'Bounce In Down',
                'bounceInLeft' => 'Bounce In Left',
                'bounceInRight' => 'Bounce In Right',
                'bounceInUp' => 'Bounce In Up',
            ],
            'Sliding' => [
                'slideInDown' => 'Slide In Down',
                'slideInLeft' => 'Slide In Left',
                'slideInRight' => 'Slide In Right',
                'slideInUp' => 'Slide In Up',
            ],
            'Rotating' => [
                'rotateIn' => 'Rotate In',
                'rotateInDownLeft' => 'Rotate In Down Left',
                'rotateInDownRight' => 'Rotate In Down Right',
                'rotateInUpLeft' => 'Rotate In Up Left',
                'rotateInUpRight' => 'Rotate In Up Right',
            ],
            'Attention Seekers' => [
                'bounce' => 'Bounce',
                'flash' => 'Flash',
                'pulse' => 'Pulse',
                'rubberBand' => 'Rubber Band',
                'shake' => 'Shake',
                'headShake' => 'Head Shake',
                'swing' => 'Swing',
                'tada' => 'Tada',
                'wobble' => 'Wobble',
                'jello' => 'Jello',
            ],
            'Light Speed' => [
                'lightSpeedIn' => 'Light Speed In',
            ],
            'Specials' => [
                'rollIn' => 'Roll In',
            ],
        ];

        /**
         * Element appearance animations list.
         *
         * @since 2.4.0
         *
         * @param array $additional_animations Additional Animations array.
         */
        $additional_animations = apply_filters('elementor/controls/animations/additional_animations', []);

        return array_merge($animations, $additional_animations);
    }

    /**
     * Render animations control template.
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
                <select id="<?= $control_uid ?>" data-setting="{{ data.name }}">
                    <option value=""><?= __('Default') ?></option>
                    <option value="none"><?= __('None') ?></option>
                    <?php foreach (self::getAnimations() as $animations_group_name => $animations_group) : ?>
                        <optgroup label="<?= $animations_group_name ?>">
                            <?php foreach ($animations_group as $animation_name => $animation_title) : ?>
                                <option value="<?= $animation_name ?>"><?= $animation_title ?></option>
                            <?php endforeach ?>
                        </optgroup>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
