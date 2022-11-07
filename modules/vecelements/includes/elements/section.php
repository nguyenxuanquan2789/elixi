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
 * Elementor section element.
 *
 * Elementor section handler class is responsible for initializing the section
 * element.
 *
 * @since 1.0.0
 */
class ElementSection extends ElementBase
{
    /**
     * Section edit tools.
     *
     * Holds the section edit tools.
     *
     * @since 1.0.0
     * @access protected
     * @static
     *
     * @var array Section edit tools.
     */
    protected static $_edit_tools;

    /**
     * Section predefined columns presets.
     *
     * Holds the predefined columns width for each columns count available by
     * default by Elementor. Default is an empty array.
     *
     * Note that when the user creates a section he can define custom sizes for
     * the columns. But Elementor sets default values for predefined columns.
     *
     * For example two columns 50% width each one, or three columns 33.33% each
     * one. This property hold the data for those preset values.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var array Section presets.
     */
    private static $presets = [];

    /**
     * Get element type.
     *
     * Retrieve the element type, in this case `section`.
     *
     * @since 2.1.0
     * @access public
     * @static
     *
     * @return string The type.
     */
    public static function getType()
    {
        return 'section';
    }

    /**
     * Get section name.
     *
     * Retrieve the section name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Section name.
     */
    public function getName()
    {
        return 'section';
    }

    /**
     * Get section title.
     *
     * Retrieve the section title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Section title.
     */
    public function getTitle()
    {
        return __('Section');
    }

    /**
     * Get section icon.
     *
     * Retrieve the section icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Section icon.
     */
    public function getIcon()
    {
        return 'eicon-columns';
    }

    /**
     * Get presets.
     *
     * Retrieve a specific preset columns for a given columns count, or a list
     * of all the preset if no parameters passed.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param int $columns_count Optional. Columns count. Default is null.
     * @param int $preset_index  Optional. Preset index. Default is null.
     *
     * @return array Section presets.
     */
    public static function getPresets($columns_count = null, $preset_index = null)
    {
        if (!self::$presets) {
            self::initPresets();
        }

        $presets = self::$presets;

        if (null !== $columns_count) {
            $presets = $presets[$columns_count];
        }

        if (null !== $preset_index) {
            $presets = $presets[$preset_index];
        }

        return $presets;
    }

    /**
     * Initialize presets.
     *
     * Initializing the section presets and set the number of columns the
     * section can have by default. For example a column can have two columns
     * 50% width each one, or three columns 33.33% each one.
     *
     * Note that Elementor sections have default section presets but the user
     * can set custom number of columns and define custom sizes for each column.

     * @since 1.0.0
     * @access public
     * @static
     */
    public static function initPresets()
    {
        $additional_presets = [
            2 => [
                [
                    'preset' => [33, 66],
                ],
                [
                    'preset' => [66, 33],
                ],
            ],
            3 => [
                [
                    'preset' => [25, 25, 50],
                ],
                [
                    'preset' => [50, 25, 25],
                ],
                [
                    'preset' => [25, 50, 25],
                ],
                [
                    'preset' => [16, 66, 16],
                ],
            ],
        ];

        foreach (range(1, 10) as $columns_count) {
            self::$presets[$columns_count] = [
                [
                    'preset' => [],
                ],
            ];

            $preset_unit = floor(1 / $columns_count * 100);

            for ($i = 0; $i < $columns_count; $i++) {
                self::$presets[$columns_count][0]['preset'][] = $preset_unit;
            }

            if (!empty($additional_presets[$columns_count])) {
                self::$presets[$columns_count] = array_merge(self::$presets[$columns_count], $additional_presets[$columns_count]);
            }

            foreach (self::$presets[$columns_count] as $preset_index => &$preset) {
                $preset['key'] = $columns_count . $preset_index;
            }
        }
    }

    /**
     * Get default edit tools.
     *
     * Retrieve the section default edit tools. Used to set initial tools.
     *
     * @since 1.0.0
     * @access protected
     * @static
     *
     * @return array Default section edit tools.
     */
    protected static function getDefaultEditTools()
    {
        $section_label = __('Section');

        $edit_tools = [
            'add' => [
                /* translators: %s: Section label */
                'title' => sprintf(__('Add %s'), $section_label),
                'icon' => 'plus',
            ],
            'edit' => [
                /* translators: %s: Section label */
                'title' => sprintf(__('Edit %s'), $section_label),
                'icon' => 'handle',
            ],
        ];

        if (self::isEditButtonsEnabled()) {
            $edit_tools += [
                'duplicate' => [
                    /* translators: %s: Section label */
                    'title' => sprintf(__('Duplicate %s'), $section_label),
                    'icon' => 'clone',
                ],
            ];
        }

        $edit_tools += [
            'remove' => [
                /* translators: %s: Section label */
                'title' => sprintf(__('Delete %s'), $section_label),
                'icon' => 'close',
            ],
        ];

        return $edit_tools;
    }

    /**
     * Get initial config.
     *
     * Retrieve the current section initial configuration.
     *
     * Adds more configuration on top of the controls list, the tabs assigned to
     * the control, element name, type, icon and more. This method also adds
     * section presets.
     *
     * @since 1.0.10
     * @access protected
     *
     * @return array The initial config.
     */
    protected function _getInitialConfig()
    {
        $config = parent::_getInitialConfig();

        $config['presets'] = self::getPresets();
        $config['controls'] = $this->getControls();
        $config['tabs_controls'] = $this->getTabsControls();

        return $config;
    }

    /**
     * Register section controls.
     *
     * Used to add new controls to the section element.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {

        $this->startControlsSection(
            'section_layout',
            [
                'label' => __('Layout'),
                'tab' => ControlsManager::TAB_LAYOUT,
            ]
        );

        $this->addControl(
            '_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HIDDEN,
                'render_type' => 'none',
            ]
        );

        $this->addControl(
            'stretch_section',
            [
                'label' => __('Stretch Section'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'return_value' => 'section-stretched',
                'prefix_class' => 'elementor-',
                'hide_in_inner' => true,
                'description' => __('Stretch the section to the full width of the page using JS.'),
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Content Width'),
                'type' => ControlsManager::SELECT,
                'default' => 'boxed',
                'options' => [
                    'boxed' => __('Boxed'),
                    'full_width' => __('Full Width'),
                ],
                'prefix_class' => 'elementor-section-',
            ]
        );

        $this->addControl(
            'content_width',
            [
                'label' => __('Content Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 500,
                        'max' => 1600,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout' => ['boxed'],
                ],
                'show_label' => false,
                'separator' => 'none',
            ]
        );

        $this->addControl(
            'gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default'),
                    'no' => __('No Gap'),
                    'narrow' => __('Narrow'),
                    'extended' => __('Extended'),
                    'wide' => __('Wide'),
                    'wider' => __('Wider'),
                ],
            ]
        );

        $this->addControl(
            'height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default'),
                    'full' => __('Fit To Screen'),
                    'min-height' => __('Min Height'),
                ],
                'prefix_class' => 'elementor-section-height-',
                'hide_in_inner' => true,
            ]
        );

        $this->addResponsiveControl(
            'custom_height',
            [
                'label' => __('Minimum Height'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 400,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1440,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh', 'vw'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} > .elementor-container:after' => 'content: ""; min-height: inherit;', // Hack for IE11
                ],
                'condition' => [
                    'height' => ['min-height'],
                ],
                'hide_in_inner' => true,
            ]
        );

        $this->addControl(
            'height_inner',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default'),
                    'min-height' => __('Min Height'),
                ],
                'prefix_class' => 'elementor-section-height-',
                'hide_in_top' => true,
            ]
        );

        $this->addResponsiveControl(
            'custom_height_inner',
            [
                'label' => __('Minimum Height'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 400,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'height_inner' => ['min-height'],
                ],
                'hide_in_top' => true,
            ]
        );

        $this->addControl(
            'column_position',
            [
                'label' => __('Column Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'middle',
                'options' => [
                    'stretch' => __('Stretch'),
                    'top' => __('Top'),
                    'middle' => __('Middle'),
                    'bottom' => __('Bottom'),
                ],
                'prefix_class' => 'elementor-section-items-',
                'condition' => [
                    'height' => ['full', 'min-height'],
                ],
            ]
        );

        $this->addControl(
            'content_position',
            [
                'label' => __('Content Position'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'top' => __('Top'),
                    'middle' => __('Middle'),
                    'bottom' => __('Bottom'),
                ],
                'prefix_class' => 'elementor-section-content-',
            ]
        );

        $this->addControl(
            'overflow',
            [
                'label' => __('Overflow'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'hidden' => __('Hidden'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        $possible_tags = [
            'div',
            'header',
            'footer',
            'main',
            'article',
            'section',
            'aside',
            'nav',
        ];

        $options = [
            '' => __('Default'),
        ] + array_combine($possible_tags, $possible_tags);

        $this->addControl(
            'html_tag',
            [
                'label' => __('HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => $options,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'structure',
            [
                'label' => __('Structure'),
                'type' => ControlsManager::STRUCTURE,
                'default' => '10',
                'render_type' => 'none',
            ]
        );

        $this->endControlsSection();

        // Section background
        $this->startControlsSection(
            'section_background',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_background');

        $this->startControlsTab(
            'tab_background_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background',
                'types' => ['classic', 'gradient', 'video'],
                'fields_options' => [
                    'background' => [
                        'frontend_available' => true,
                    ],
                    'video_link' => [
                        'frontend_available' => true,
                    ],
                    'video_start' => [
                        'frontend_available' => true,
                    ],
                    'video_end' => [
                        'frontend_available' => true,
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_background_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_hover',
                'selector' => '{{WRAPPER}}:hover',
            ]
        );

        $this->addControl(
            'background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Background Overlay
        $this->startControlsSection(
            'section_background_overlay',
            [
                'label' => __('Background Overlay'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'background_background' => ['classic', 'gradient', 'video'],
                ],
            ]
        );

        $this->startControlsTabs('tabs_background_overlay');

        $this->startControlsTab(
            'tab_background_overlay_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_overlay',
                'selector' => '{{WRAPPER}} > .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-background-overlay' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'background_overlay_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'overlay_blend_mode',
            [
                'label' => __('Blend Mode'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Normal'),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_background_overlay_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_overlay_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_hover_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => .5,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-background-overlay' => 'opacity: {{SIZE}};',
                ],
                'condition' => [
                    'background_overlay_hover_background' => ['classic', 'gradient'],
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-background-overlay',
            ]
        );

        $this->addControl(
            'background_overlay_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'render_type' => 'ui',
                'separator' => 'before',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section border
        $this->startControlsSection(
            'section_border',
            [
                'label' => __('Border'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_border');

        $this->startControlsTab(
            'tab_border_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border',
            ]
        );

        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}, {{WRAPPER}} > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_border_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_hover',
                'selector' => '{{WRAPPER}}:hover',
            ]
        );

        $this->addResponsiveControl(
            'border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}:hover, {{WRAPPER}}:hover > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover',
            ]
        );

        $this->addControl(
            'border_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'default' => [
                    'size' => 0.3,
                ],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'background_background',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'border_border',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'transition: background {{background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
                    '{{WRAPPER}} > .elementor-background-overlay' => 'transition: background {{background_overlay_hover_transition.SIZE}}s, border-radius {{SIZE}}s, opacity {{background_overlay_hover_transition.SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section Shape Divider
        $this->startControlsSection(
            'section_shape_divider',
            [
                'label' => __('Shape Divider'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_shape_dividers');

        $shapes_options = [
            '' => __('None'),
        ];

        foreach (Shapes::getShapes() as $shape_name => $shape_props) {
            $shapes_options[$shape_name] = $shape_props['title'];
        }

        foreach ([
            'top' => __('Top'),
            'bottom' => __('Bottom'),
        ] as $side => $side_label
        ) {
            $base_control_key = "shape_divider_$side";

            $this->startControlsTab(
                "tab_$base_control_key",
                [
                    'label' => $side_label,
                ]
            );

            $this->addControl(
                $base_control_key,
                [
                    'label' => __('Type'),
                    'type' => ControlsManager::SELECT,
                    'options' => $shapes_options,
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );

            $this->addControl(
                $base_control_key . '_color',
                [
                    'label' => __('Color'),
                    'type' => ControlsManager::COLOR,
                    'condition' => [
                        "shape_divider_$side!" => '',
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .elementor-shape-$side .elementor-shape-fill" => 'fill: {{UNIT}};',
                    ],
                ]
            );

            $this->addResponsiveControl(
                $base_control_key . '_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SLIDER,
                    'default' => [
                        'unit' => '%',
                    ],
                    'tablet_default' => [
                        'unit' => '%',
                    ],
                    'mobile_default' => [
                        'unit' => '%',
                    ],
                    'range' => [
                        '%' => [
                            'min' => 100,
                            'max' => 300,
                        ],
                    ],
                    'condition' => [
                        "shape_divider_$side" => array_keys(Shapes::filterShapes('height_only', Shapes::FILTER_EXCLUDE)),
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .elementor-shape-$side svg" => 'width: calc({{SIZE}}{{UNIT}} + 1.3px)',
                    ],
                ]
            );

            $this->addResponsiveControl(
                $base_control_key . '_height',
                [
                    'label' => __('Height'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'condition' => [
                        "shape_divider_$side!" => '',
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .elementor-shape-$side svg" => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->addControl(
                $base_control_key . '_flip',
                [
                    'label' => __('Flip'),
                    'type' => ControlsManager::SWITCHER,
                    'condition' => [
                        "shape_divider_$side" => array_keys(Shapes::filterShapes('has_flip')),
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .elementor-shape-$side svg" => 'transform: translateX(-50%) rotateY(180deg)',
                    ],
                ]
            );

            $this->addControl(
                $base_control_key . '_negative',
                [
                    'label' => __('Invert'),
                    'type' => ControlsManager::SWITCHER,
                    'frontend_available' => true,
                    'condition' => [
                        "shape_divider_$side" => array_keys(Shapes::filterShapes('has_negative')),
                    ],
                    'render_type' => 'none',
                ]
            );

            $this->addControl(
                $base_control_key . '_above_content',
                [
                    'label' => __('Bring to Front'),
                    'type' => ControlsManager::SWITCHER,
                    'selectors' => [
                        "{{WRAPPER}} > .elementor-shape-$side" => 'z-index: 2; pointer-events: none',
                    ],
                    'condition' => [
                        "shape_divider_$side!" => '',
                    ],
                ]
            );

            $this->endControlsTab();
        }

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section Typography
        $this->startControlsSection(
            'section_typo',
            [
                'label' => __('Typography'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        if (in_array(SchemeColor::getType(), SchemesManager::getEnabledSchemes(), true)) {
            $this->addControl(
                'colors_warning',
                [
                    'type' => ControlsManager::RAW_HTML,
                    'raw' => __('Note: The following colors won\'t work if Default Colors are enabled.'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                ]
            );
        }

        $this->addControl(
            'heading_color',
            [
                'label' => __('Heading Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
                ],
                'separator' => 'none',
            ]
        );

        $this->addControl(
            'color_text',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'color_link',
            [
                'label' => __('Link Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'color_link_hover',
            [
                'label' => __('Link Hover Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_align',
            [
                'label' => __('Text Align'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        // Section Advanced
        $this->startControlsSection(
            'section_advanced',
            [
                'label' => __('Advanced'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'z_index',
            [
                'label' => __('Z-Index'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
                'label_block' => false,
            ]
        );

        $this->addControl(
            '_element_id',
            [
                'label' => __('CSS ID'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id'),
                'label_block' => false,
                'style_transfer' => false,
            ]
        );

        $this->addControl(
            'css_classes',
            [
                'label' => __('CSS Classes'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'dynamic' => [
                    'active' => true,
                ],
                'prefix_class' => '',
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
                'label_block' => false,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_effects',
            [
                'label' => __('Motion Effects'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'animation',
            [
                'label' => __('Entrance Animation'),
                'type' => ControlsManager::ANIMATION,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'animation_duration',
            [
                'label' => __('Animation Duration'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    'slow' => __('Slow'),
                    '' => __('Normal'),
                    'fast' => __('Fast'),
                ],
                'prefix_class' => 'animated-',
                'condition' => [
                    'animation!' => '',
                ],
            ]
        );

        $this->addControl(
            'animation_delay',
            [
                'label' => __('Animation Delay') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'default' => '',
                'min' => 0,
                'step' => 100,
                'condition' => [
                    'animation!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        // Section Responsive
        $this->startControlsSection(
            '_section_responsive',
            [
                'label' => __('Responsive'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addControl(
            'reverse_order_tablet',
            [
                'label' => __('Reverse Columns') . ' (' . __('Tablet') . ')',
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'prefix_class' => 'elementor-',
                'return_value' => 'reverse-tablet',
            ]
        );

        $this->addControl(
            'reverse_order_mobile',
            [
                'label' => __('Reverse Columns') . ' (' . __('Mobile') . ')',
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'prefix_class' => 'elementor-',
                'return_value' => 'reverse-mobile',
            ]
        );

        $this->addControl(
            'heading_visibility',
            [
                'label' => __('Visibility'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'responsive_description',
            [
                'raw' => __('Attention: The display settings (show/hide for mobile, tablet or desktop) will only take effect once you are on the preview or live page, and not while you\'re in editing mode in Elementor.'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->addControl(
            'hide_desktop',
            [
                'label' => __('Hide On Desktop'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-desktop',
            ]
        );

        $this->addControl(
            'hide_tablet',
            [
                'label' => __('Hide On Tablet'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-tablet',
            ]
        );

        $this->addControl(
            'hide_mobile',
            [
                'label' => __('Hide On Mobile'),
                'type' => ControlsManager::SWITCHER,
                'default' => '',
                'prefix_class' => 'elementor-',
                'label_on' => __('Hide'),
                'label_off' => __('Show'),
                'return_value' => 'hidden-phone',
            ]
        );

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    /**
     * Render section edit tools.
     *
     * Used to generate the edit tools HTML.
     *
     * @since 1.8.0
     * @access protected
     */
    protected function renderEditTools()
    {
        ?>
        <div class="elementor-element-overlay">
            <ul class="elementor-editor-element-settings elementor-editor-section-settings">
            <?php foreach (self::getEditTools() as $edit_tool_name => $edit_tool) : ?>
                <?php if ('add' === $edit_tool_name) : ?>
                    <# if ( ! isInner ) { #>
                <?php endif ?>
                <li class="elementor-editor-element-setting elementor-editor-element-<?= esc_attr($edit_tool_name) ?>"
                    title="<?= esc_attr($edit_tool['title']) ?>">
                    <i class="eicon-<?= esc_attr($edit_tool['icon']) ?>" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?= esc_html($edit_tool['title']) ?></span>
                </li>
                <?php if ('add' === $edit_tool_name) : ?>
                    <# } #>
                <?php endif ?>
            <?php endforeach ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Render section output in the editor.
     *
     * Used to generate the live preview, using a Backbone JavaScript template.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <# if ( settings.background_video_link ) { #>
            <div class="elementor-background-video-container elementor-hidden-phone">
                <div class="elementor-background-video-embed"></div>
                <video class="elementor-background-video-hosted elementor-html5-video" autoplay loop muted></video>
            </div>
        <# } #>
        <div class="elementor-background-overlay"></div>
        <div class="elementor-shape elementor-shape-top"></div>
        <div class="elementor-shape elementor-shape-bottom"></div>
        <div class="elementor-container elementor-column-gap-{{ settings.gap }}">
            <div class="elementor-row"></div>
        </div>
        <?php
    }

    /**
     * Before section rendering.
     *
     * Used to add stuff before the section element.
     *
     * @since 1.0.0
     * @access public
     */
    public function beforeRender()
    {
        $settings = $this->getSettingsForDisplay();
        ?>
        <<?= esc_html($this->getHtmlTag()) ?> <?php $this->printRenderAttributeString('_wrapper') ?>>
        <?php
        if ('video' === $settings['background_background']) :
            if ($settings['background_video_link']) :
                $video_properties = Embed::getVideoProperties($settings['background_video_link']);
                ?>
                <div class="elementor-background-video-container elementor-hidden-phone">
                <?php if ($video_properties) : ?>
                    <div class="elementor-background-video-embed"></div>
                <?php else : ?>
                    <video class="elementor-background-video-hosted elementor-html5-video" autoplay loop muted></video>
                <?php endif ?>
                </div>
                <?php
            endif;
        endif;

        $has_background_overlay = in_array($settings['background_overlay_background'], ['classic', 'gradient'], true) ||
            in_array($settings['background_overlay_hover_background'], ['classic', 'gradient'], true);
        ?>
        <?php if ($has_background_overlay) : ?>
            <div class="elementor-background-overlay"></div>
        <?php endif;

        if ($settings['shape_divider_top']) {
            $this->printShapeDivider('top');
        }

        if ($settings['shape_divider_bottom']) {
            $this->printShapeDivider('bottom');
        }
        ?>
            <div class="elementor-container elementor-column-gap-<?= esc_attr($settings['gap']) ?>">
                <div class="elementor-row">
        <?php
    }

    /**
     * After section rendering.
     *
     * Used to add stuff after the section element.
     *
     * @since 1.0.0
     * @access public
     */
    public function afterRender()
    {
        ?>
                </div>
            </div>
        </<?= esc_html($this->getHtmlTag()) ?>>
        <?php
    }

    /**
     * Add section render attributes.
     *
     * Used to add attributes to the current section wrapper HTML tag.
     *
     * @since 1.3.0
     * @access protected
     */
    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        $section_type = $this->getData('isInner') ? 'inner' : 'top';

        $this->addRenderAttribute('_wrapper', 'class', [
            'elementor-section',
            'elementor-' . $section_type . '-section',
        ]);
    }

    /**
     * Get default child type.
     *
     * Retrieve the section child type based on element data.
     *
     * @since 1.0.0
     * @access protected
     *
     * @param array $element_data Element ID.
     *
     * @return ElementBase Section default child type.
     */
    protected function _getDefaultChildType(array $element_data)
    {
        return Plugin::$instance->elements_manager->getElementTypes('column');
    }

    /**
     * Get HTML tag.
     *
     * Retrieve the section element HTML tag.
     *
     * @since 1.5.3
     * @access private
     *
     * @return string Section HTML tag.
     */
    private function getHtmlTag()
    {
        $html_tag = $this->getSettings('html_tag');

        if (empty($html_tag)) {
            $html_tag = 'section';
        }

        return $html_tag;
    }

    /**
     * Print section shape divider.
     *
     * Used to generate the shape dividers HTML.
     *
     * @since 1.3.0
     * @access private
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     *
     * @param string $side Shape divider side, used to set the shape key.
     */
    private function printShapeDivider($side)
    {
        $settings = $this->getActiveSettings();
        $base_setting_key = "shape_divider_$side";
        $negative = !empty($settings[$base_setting_key . '_negative']);
        ?>
        <div class="elementor-shape elementor-shape-<?= esc_attr($side) ?>" data-negative="<?= var_export($negative) ?>">
            <?php include Shapes::getShapePath($settings[$base_setting_key], !empty($settings[$base_setting_key . '_negative'])) ?>
        </div>
        <?php
    }
}
