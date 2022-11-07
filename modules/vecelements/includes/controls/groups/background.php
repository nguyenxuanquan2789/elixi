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
 * Elementor background control.
 *
 * A base control for creating background control. Displays input fields to define
 * the background color, background image, background gradient or background video.
 *
 * @since 1.2.2
 */
class GroupControlBackground extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the background control fields.
     *
     * @since 1.2.2
     * @access protected
     * @static
     *
     * @var array Background control fields.
     */
    protected static $fields;

    /**
     * Background Types.
     *
     * Holds all the available background types.
     *
     * @since 1.2.2
     * @access private
     * @static
     *
     * @var array
     */
    private static $background_types;

    /**
     * Get background control type.
     *
     * Retrieve the control type, in this case `background`.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function getType()
    {
        return 'background';
    }

    /**
     * Get background control types.
     *
     * Retrieve available background types.
     *
     * @since 1.2.2
     * @access public
     * @static
     *
     * @return array Available background types.
     */
    public static function getBackgroundTypes()
    {
        if (null === self::$background_types) {
            self::$background_types = self::getDefaultBackgroundTypes();
        }

        return self::$background_types;
    }

    /**
     * Get Default background types.
     *
     * Retrieve background control initial types.
     *
     * @since 2.0.0
     * @access private
     * @static
     *
     * @return array Default background types.
     */
    private static function getDefaultBackgroundTypes()
    {
        return [
            'classic' => [
                'title' => _x('Classic', 'Background Control'),
                'icon' => 'fa fa-paint-brush',
            ],
            'gradient' => [
                'title' => _x('Gradient', 'Background Control'),
                'icon' => 'fa fa-barcode',
            ],
            'video' => [
                'title' => _x('Background Video', 'Background Control'),
                'icon' => 'fa fa-video-camera',
            ],
        ];
    }

    /**
     * Init fields.
     *
     * Initialize background control fields.
     *
     * @since 1.2.2
     * @access public
     *
     * @return array Control fields.
     */
    public function initFields()
    {
        $fields = [];

        $fields['background'] = [
            'label' => _x('Background Type', 'Background Control'),
            'type' => ControlsManager::CHOOSE,
            'label_block' => false,
            'render_type' => 'ui',
        ];

        $fields['color'] = [
            'label' => _x('Color', 'Background Control'),
            'type' => ControlsManager::COLOR,
            'default' => '',
            'title' => _x('Background Color', 'Background Control'),
            'selectors' => [
                '{{SELECTOR}}' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'background' => ['classic', 'gradient'],
            ],
        ];

        $fields['color_stop'] = [
            'label' => _x('Location', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'size_units' => ['%'],
            'default' => [
                'unit' => '%',
                'size' => 0,
            ],
            'render_type' => 'ui',
            'condition' => [
                'background' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b'] = [
            'label' => _x('Second Color', 'Background Control'),
            'type' => ControlsManager::COLOR,
            'default' => '#f2295b',
            'render_type' => 'ui',
            'condition' => [
                'background' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['color_b_stop'] = [
            'label' => _x('Location', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'size_units' => ['%'],
            'default' => [
                'unit' => '%',
                'size' => 100,
            ],
            'render_type' => 'ui',
            'condition' => [
                'background' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_type'] = [
            'label' => _x('Type', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'options' => [
                'linear' => _x('Linear', 'Background Control'),
                'radial' => _x('Radial', 'Background Control'),
            ],
            'default' => 'linear',
            'render_type' => 'ui',
            'condition' => [
                'background' => ['gradient'],
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_angle'] = [
            'label' => _x('Angle', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'size_units' => ['deg'],
            'default' => [
                'unit' => 'deg',
                'size' => 180,
            ],
            'range' => [
                'deg' => [
                    'step' => 10,
                ],
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'background' => ['gradient'],
                'gradient_type' => 'linear',
            ],
            'of_type' => 'gradient',
        ];

        $fields['gradient_position'] = [
            'label' => _x('Position', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'options' => [
                'center center' => _x('Center Center', 'Background Control'),
                'center left' => _x('Center Left', 'Background Control'),
                'center right' => _x('Center Right', 'Background Control'),
                'top center' => _x('Top Center', 'Background Control'),
                'top left' => _x('Top Left', 'Background Control'),
                'top right' => _x('Top Right', 'Background Control'),
                'bottom center' => _x('Bottom Center', 'Background Control'),
                'bottom left' => _x('Bottom Left', 'Background Control'),
                'bottom right' => _x('Bottom Right', 'Background Control'),
            ],
            'default' => 'center center',
            'selectors' => [
                '{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
            ],
            'condition' => [
                'background' => ['gradient'],
                'gradient_type' => 'radial',
            ],
            'of_type' => 'gradient',
        ];

        $fields['image'] = [
            'label' => _x('Image', 'Background Control'),
            'type' => ControlsManager::MEDIA,
            'dynamic' => [
                'active' => true,
            ],
            'responsive' => true,
            'title' => _x('Background Image', 'Background Control'),
            'selectors' => [
                '{{SELECTOR}}' => 'background-image: url("{{URL}}");',
            ],
            'render_type' => 'template',
            'condition' => [
                'background' => ['classic'],
            ],
        ];

        $fields['position'] = [
            'label' => _x('Position', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'responsive' => true,
            'options' => [
                '' => _x('Default', 'Background Control'),
                'top left' => _x('Top Left', 'Background Control'),
                'top center' => _x('Top Center', 'Background Control'),
                'top right' => _x('Top Right', 'Background Control'),
                'center left' => _x('Center Left', 'Background Control'),
                'center center' => _x('Center Center', 'Background Control'),
                'center right' => _x('Center Right', 'Background Control'),
                'bottom left' => _x('Bottom Left', 'Background Control'),
                'bottom center' => _x('Bottom Center', 'Background Control'),
                'bottom right' => _x('Bottom Right', 'Background Control'),
                'initial' => _x('Custom', 'Background Control'),

            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-position: {{VALUE}};',
            ],
            'condition' => [
                'background' => ['classic'],
                'image[url]!' => '',
            ],
        ];

        $fields['xpos'] = [
            'label' => _x('X Position', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'responsive' => true,
            'size_units' => ['px', 'em', '%', 'vw'],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'tablet_default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'mobile_default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
            ],
            'condition' => [
                'background' => ['classic'],
                'position' => ['initial'],
                'image[url]!' => '',
            ],
            'required' => true,
            'device_args' => [
                ControlsStack::RESPONSIVE_TABLET => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_tablet.SIZE}}{{ypos_tablet.UNIT}}',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'position_tablet' => ['initial'],
                    ],
                ],
                ControlsStack::RESPONSIVE_MOBILE => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos_mobile.SIZE}}{{ypos_mobile.UNIT}}',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'position_mobile' => ['initial'],
                    ],
                ],
            ],
        ];

        $fields['ypos'] = [
            'label' => _x('Y Position', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'responsive' => true,
            'size_units' => ['px', 'em', '%', 'vh'],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'tablet_default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'mobile_default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vh' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'background' => ['classic'],
                'position' => ['initial'],
                'image[url]!' => '',
            ],
            'required' => true,
            'device_args' => [
                ControlsStack::RESPONSIVE_TABLET => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-position: {{xpos_tablet.SIZE}}{{xpos_tablet.UNIT}} {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'position_tablet' => ['initial'],
                    ],
                ],
                ControlsStack::RESPONSIVE_MOBILE => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-position: {{xpos_mobile.SIZE}}{{xpos_mobile.UNIT}} {{SIZE}}{{UNIT}}',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'position_mobile' => ['initial'],
                    ],
                ],
            ],
        ];

        $fields['attachment'] = [
            'label' => _x('Attachment', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'options' => [
                '' => _x('Default', 'Background Control'),
                'scroll' => _x('Scroll', 'Background Control'),
                'fixed' => _x('Fixed', 'Background Control'),
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-attachment: {{VALUE}};',
            ],
            'condition' => [
                'background' => ['classic'],
                'image[url]!' => '',
            ],
        ];

        $fields['attachment_alert'] = [
            'type' => ControlsManager::RAW_HTML,
            'content_classes' => 'elementor-control-field-description',
            'raw' => __('Note: Attachment Fixed works only on desktop.'),
            'separator' => 'none',
            'condition' => [
                'background' => ['classic'],
                'image[url]!' => '',
                'attachment' => 'fixed',
            ],
        ];

        $fields['repeat'] = [
            'label' => _x('Repeat', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'default' => '',
            'responsive' => true,
            'options' => [
                '' => _x('Default', 'Background Control'),
                'no-repeat' => _x('No-repeat', 'Background Control'),
                'repeat' => _x('Repeat', 'Background Control'),
                'repeat-x' => _x('Repeat-x', 'Background Control'),
                'repeat-y' => _x('Repeat-y', 'Background Control'),
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-repeat: {{VALUE}};',
            ],
            'condition' => [
                'background' => ['classic'],
                'image[url]!' => '',
            ],
        ];

        $fields['size'] = [
            'label' => _x('Size', 'Background Control'),
            'type' => ControlsManager::SELECT,
            'responsive' => true,
            'default' => '',
            'options' => [
                '' => _x('Default', 'Background Control'),
                'auto' => _x('Auto', 'Background Control'),
                'cover' => _x('Cover', 'Background Control'),
                'contain' => _x('Contain', 'Background Control'),
                'initial' => _x('Custom', 'Background Control'),
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background-size: {{VALUE}};',
            ],
            'condition' => [
                'background' => ['classic'],
                'image[url]!' => '',
            ],
        ];

        $fields['bg_width'] = [
            'label' => _x('Width', 'Background Control'),
            'type' => ControlsManager::SLIDER,
            'responsive' => true,
            'size_units' => ['px', 'em', '%', 'vw'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'size' => 100,
                'unit' => '%',
            ],
            'required' => true,
            'selectors' => [
                '{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',

            ],
            'condition' => [
                'background' => ['classic'],
                'size' => ['initial'],
                'image[url]!' => '',
            ],
            'device_args' => [
                ControlsStack::RESPONSIVE_TABLET => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'size_tablet' => ['initial'],
                    ],
                ],
                ControlsStack::RESPONSIVE_MOBILE => [
                    'selectors' => [
                        '{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',
                    ],
                    'condition' => [
                        'background' => ['classic'],
                        'size_mobile' => ['initial'],
                    ],
                ],
            ],
        ];

        $fields['video_link'] = [
            'label' => _x('Video Link', 'Background Control'),
            'type' => ControlsManager::TEXT,
            'placeholder' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
            'description' => __('YouTube link or video file (mp4 is recommended).'),
            'label_block' => true,
            'default' => '',
            'condition' => [
                'background' => ['video'],
            ],
            'of_type' => 'video',
        ];

        $fields['video_start'] = [
            'label' => __('Start Time'),
            'type' => ControlsManager::NUMBER,
            'description' => __('Specify a start time (in seconds)'),
            'placeholder' => 10,
            'condition' => [
                'background' => ['video'],
            ],
            'of_type' => 'video',
        ];

        $fields['video_end'] = [
            'label' => __('End Time'),
            'type' => ControlsManager::NUMBER,
            'description' => __('Specify an end time (in seconds)'),
            'placeholder' => 70,
            'condition' => [
                'background' => ['video'],
            ],
            'of_type' => 'video',
        ];

        $fields['video_fallback'] = [
            'label' => _x('Background Fallback', 'Background Control'),
            'description' => __('This cover image will replace the background video on mobile and tablet devices.'),
            'type' => ControlsManager::MEDIA,
            'label_block' => true,
            'condition' => [
                'background' => ['video'],
            ],
            'selectors' => [
                '{{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
            ],
            'of_type' => 'video',
        ];

        return $fields;
    }

    /**
     * Get child default args.
     *
     * Retrieve the default arguments for all the child controls for a specific group
     * control.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Default arguments for all the child controls.
     */
    protected function getChildDefaultArgs()
    {
        return [
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}}:not(.elementor-motion-effects-element-type-background), {{WRAPPER}} > .elementor-motion-effects-container > .elementor-motion-effects-layer',
        ];
    }

    /**
     * Filter fields.
     *
     * Filter which controls to display, using `include`, `exclude`, `condition`
     * and `of_type` arguments.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Control fields.
     */
    protected function filterFields()
    {
        $fields = parent::filterFields();

        $args = $this->getArgs();

        foreach ($fields as &$field) {
            if (isset($field['of_type']) && !in_array($field['of_type'], $args['types'])) {
                unset($field);
            }
        }

        return $fields;
    }

    /**
     * Prepare fields.
     *
     * Process background control fields before adding them to `add_control()`.
     *
     * @since 1.2.2
     * @access protected
     *
     * @param array $fields Background control fields.
     *
     * @return array Processed fields.
     */
    protected function prepareFields($fields)
    {
        $args = $this->getArgs();

        $background_types = self::getBackgroundTypes();

        $choose_types = [];

        foreach ($args['types'] as $type) {
            if (isset($background_types[$type])) {
                $choose_types[$type] = $background_types[$type];
            }
        }

        $fields['background']['options'] = $choose_types;

        return parent::prepareFields($fields);
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the background control. Used to return the
     * default options while initializing the background control.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Default background control options.
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => false,
        ];
    }
}
