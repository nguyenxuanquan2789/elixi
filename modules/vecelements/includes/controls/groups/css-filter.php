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
 * Elementor CSS Filter control.
 *
 * A base control for applying css filters. Displays sliders to define the
 * values of different CSS filters including blur, brightens, contrast,
 * saturation and hue.
 *
 * @since 2.1.0
 */
class GroupControlCssFilter extends GroupControlBase
{
    /**
     * Prepare fields.
     *
     * Process css_filter control fields before adding them to `add_control()`.
     *
     * @since 2.1.0
     * @access protected
     *
     * @param array $fields CSS filter control fields.
     *
     * @return array Processed fields.
     */
    protected static $fields;

    /**
     * Get CSS filter control type.
     *
     * Retrieve the control type, in this case `css-filter`.
     *
     * @since 2.1.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function getType()
    {
        return 'css-filter';
    }

    /**
     * Init fields.
     *
     * Initialize CSS filter control fields.
     *
     * @since 2.1.0
     * @access protected
     *
     * @return array Control fields.
     */
    protected function initFields()
    {
        $controls = [];

        $controls['blur'] = [
            'label' => _x('Blur', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'required' => 'true',
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'size' => 0,
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'filter: brightness( {{brightness.SIZE}}% ) contrast( {{contrast.SIZE}}% ) saturate( {{saturate.SIZE}}% ) blur( {{blur.SIZE}}px ) hue-rotate( {{hue.SIZE}}deg )',
            ],
        ];

        $controls['brightness'] = [
            'label' => _x('Brightness', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['contrast'] = [
            'label' => _x('Contrast', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['saturate'] = [
            'label' => _x('Saturation', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'separator' => 'none',
        ];

        $controls['hue'] = [
            'label' => _x('Hue', 'Filter Control'),
            'type' => ControlsManager::SLIDER,
            'render_type' => 'ui',
            'required' => 'true',
            'default' => [
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 360,
                ],
            ],
            'separator' => 'none',
        ];

        return $controls;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the CSS filter control. Used to return the
     * default options while initializing the CSS filter control.
     *
     * @since 2.1.0
     * @access protected
     *
     * @return array Default CSS filter control options.
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => [
                'starter_name' => 'css_filter',
                'starter_title' => _x('CSS Filters', 'Filter Control'),
                'settings' => [
                    'render_type' => 'ui',
                ],
            ],
        ];
    }
}
