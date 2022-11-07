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
 * Elementor border control.
 *
 * A base control for creating border control. Displays input fields to define
 * border type, border width and border color.
 *
 * @since 1.0.0
 */
class GroupControlBorder extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the border control fields.
     *
     * @since 1.0.0
     * @access protected
     * @static
     *
     * @var array Border control fields.
     */
    protected static $fields;

    /**
     * Get border control type.
     *
     * Retrieve the control type, in this case `border`.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function getType()
    {
        return 'border';
    }

    /**
     * Init fields.
     *
     * Initialize border control fields.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Control fields.
     */
    protected function initFields()
    {
        $fields = [];

        $fields['border'] = [
            'label' => _x('Border Type', 'Border Control'),
            'type' => ControlsManager::SELECT,
            'options' => [
                '' => __('None'),
                'solid' => _x('Solid', 'Border Control'),
                'double' => _x('Double', 'Border Control'),
                'dotted' => _x('Dotted', 'Border Control'),
                'dashed' => _x('Dashed', 'Border Control'),
                'groove' => _x('Groove', 'Border Control'),
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'border-style: {{VALUE}};',
            ],
        ];

        $fields['width'] = [
            'label' => _x('Width', 'Border Control'),
            'type' => ControlsManager::DIMENSIONS,
            'selectors' => [
                '{{SELECTOR}}' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'border!' => '',
            ],
            'responsive' => true,
        ];

        $fields['color'] = [
            'label' => _x('Color', 'Border Control'),
            'type' => ControlsManager::COLOR,
            'default' => '',
            'selectors' => [
                '{{SELECTOR}}' => 'border-color: {{VALUE}};',
            ],
            'condition' => [
                'border!' => '',
            ],
        ];

        return $fields;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the border control. Used to return the
     * default options while initializing the border control.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Default border control options.
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => false,
        ];
    }
}
