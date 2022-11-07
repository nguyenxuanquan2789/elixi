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
 * Elementor column element.
 *
 * Elementor column handler class is responsible for initializing the column
 * element.
 *
 * @since 1.0.0
 */
class ElementColumn extends ElementBase
{
    /**
     * Element edit tools.
     *
     * Holds all the edit tools of the element. For example: delete, duplicate etc.
     *
     * @access protected
     * @static
     *
     * @var array
     */
    protected static $_edit_tools;

    /**
     * Get column name.
     *
     * Retrieve the column name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Column name.
     */
    public function getName()
    {
        return 'column';
    }

    /**
     * Get element type.
     *
     * Retrieve the element type, in this case `column`.
     *
     * @since 2.1.0
     * @access public
     * @static
     *
     * @return string The type.
     */
    public static function getType()
    {
        return 'column';
    }

    /**
     * Get column title.
     *
     * Retrieve the column title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Column title.
     */
    public function getTitle()
    {
        return __('Column');
    }

    /**
     * Get column icon.
     *
     * Retrieve the column icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Column icon.
     */
    public function getIcon()
    {
        return 'eicon-column';
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
     * @since 2.5.0
     * @access protected
     *
     * @return array The initial config.
     */
    protected function _getInitialConfig()
    {
        $config = parent::_getInitialConfig();

        $config['controls'] = $this->getControls();
        $config['tabs_controls'] = $this->getTabsControls();

        return $config;
    }

    /**
     * Get default edit tools.
     *
     * Retrieve the element default edit tools. Used to set initial tools.
     *
     * @since 1.0.0
     * @access protected
     * @static
     *
     * @return array Default edit tools.
     */
    protected static function getDefaultEditTools()
    {
        $column_label = __('Column');

        $edit_tools = [
            'edit' => [
                'title' => __('Edit'),
                'icon' => 'column',
            ],
        ];

        if (self::isEditButtonsEnabled()) {
            $edit_tools += [
                'duplicate' => [
                    /* translators: %s: Column label */
                    'title' => sprintf(__('Duplicate %s'), $column_label),
                    'icon' => 'clone',
                ],
                'add' => [
                    /* translators: %s: Column label */
                    'title' => sprintf(__('Add %s'), $column_label),
                    'icon' => 'plus',
                ],
                'remove' => [
                    /* translators: %s: Column label */
                    'title' => sprintf(__('Remove %s'), $column_label),
                    'icon' => 'close',
                ],
            ];
        }

        return $edit_tools;
    }

    /**
     * Register column controls.
     *
     * Used to add new controls to the column element.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        // Section Layout.
        $this->startControlsSection(
            'layout',
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

        $this->addResponsiveControl(
            '_inline_size',
            [
                'label' => __('Column Width') . ' (%)',
                'type' => ControlsManager::NUMBER,
                'min' => 2,
                'max' => 98,
                'required' => true,
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'max' => 100,
                        'required' => false,
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'max' => 100,
                        'required' => false,
                    ],
                ],
                'min_affected_device' => [
                    ControlsStack::RESPONSIVE_DESKTOP => ControlsStack::RESPONSIVE_TABLET,
                    ControlsStack::RESPONSIVE_TABLET => ControlsStack::RESPONSIVE_TABLET,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}%',
                ],
            ]
        );

        $this->addResponsiveControl(
            'content_position',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'top' => __('Top'),
                    'center' => __('Middle'),
                    'bottom' => __('Bottom'),
                    'space-between' => __('Space Between'),
                    'space-around' => __('Space Around'),
                    'space-evenly' => __('Space Evenly'),
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'bottom' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-column .elementor-column-wrap' => 'align-items: {{VALUE}}',
                    '{{WRAPPER}}.elementor-column > .elementor-column-wrap > .elementor-widget-wrap' => 'align-content: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'align',
            [
                'label' => __('Horizontal Align'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'flex-start' => __('Start'),
                    'center' => __('Center'),
                    'flex-end' => __('End'),
                    'space-between' => __('Space Between'),
                    'space-around' => __('Space Around'),
                    'space-evenly' => __('Space Evenly'),
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-column > .elementor-column-wrap > .elementor-widget-wrap' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'space_between_widgets',
            [
                'label' => __('Widgets Space') . ' (px)',
                'type' => ControlsManager::NUMBER,
                'placeholder' => 20,
                'selectors' => [
                    '{{WRAPPER}} > .elementor-column-wrap > .elementor-widget-wrap > .elementor-widget:not(.elementor-widget__width-auto):not(.elementor-widget__width-initial):not(:last-child):not(.elementor-absolute)' => 'margin-bottom: {{VALUE}}px', //Need the full path for exclude the inner section
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
                'render_type' => 'none',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style',
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
                'selector' => '{{WRAPPER}}:not(.elementor-motion-effects-element-type-background) > .elementor-element-populated, {{WRAPPER}} > .elementor-column-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer',
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
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
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

        // Section Column Background Overlay.
        $this->startControlsSection(
            'section_background_overlay',
            [
                'label' => __('Background Overlay'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'background_background' => ['classic', 'gradient'],
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
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
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
                    '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
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
                'selector' => '{{WRAPPER}} > .elementor-element-populated >  .elementor-background-overlay',
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
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
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
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay',
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
                    '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay' => 'opacity: {{SIZE}};',
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
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated >  .elementor-background-overlay',
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
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
            ]
        );

        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated, {{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} > .elementor-element-populated',
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
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
            ]
        );

        $this->addResponsiveControl(
            'border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-element-populated, {{WRAPPER}}:hover > .elementor-element-populated > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover > .elementor-element-populated',
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
                    '{{WRAPPER}} > .elementor-element-populated' => 'transition: background {{background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
                    '{{WRAPPER}} > .elementor-element-populated > .elementor-background-overlay' => 'transition: background {{background_overlay_hover_transition.SIZE}}s, border-radius {{SIZE}}s, opacity {{background_overlay_hover_transition.SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        // Section Typography.
        $this->startControlsSection(
            'section_typo',
            [
                'label' => __('Typography'),
                'type' => ControlsManager::SECTION,
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
                    '{{WRAPPER}} .elementor-element-populated .elementor-heading-title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} > .elementor-element-populated' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-element-populated a:not(#e)' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-element-populated a:not(#e):hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} > .elementor-element-populated' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        // Section Advanced.
        $this->startControlsSection(
            'section_advanced',
            [
                'label' => __('Advanced'),
                'type' => ControlsManager::SECTION,
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            'margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} > .elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // TODO: Backward comparability for deprecated controls
        $this->addControl(
            'screen_sm',
            [
                'type' => ControlsManager::HIDDEN,
            ]
        );

        $this->addControl(
            'screen_sm_width',
            [
                'type' => ControlsManager::HIDDEN,
                'condition' => [
                    'screen_sm' => ['custom'],
                ],
                'prefix_class' => 'elementor-sm-',
            ]
        );
        // END Backward comparability

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

        $this->startControlsSection(
            '_section_responsive',
            [
                'label' => __('Responsive'),
                'tab' => ControlsManager::TAB_ADVANCED,
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
                'label_on' => 'Hide',
                'label_off' => 'Show',
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
                'label_on' => 'Hide',
                'label_off' => 'Show',
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
                'label_on' => 'Hide',
                'label_off' => 'Show',
                'return_value' => 'hidden-phone',
            ]
        );

        $this->endControlsSection();

        Plugin::$instance->controls_manager->addCustomCssControls($this);
    }

    /**
     * Render column edit tools.
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
            <ul class="elementor-editor-element-settings elementor-editor-column-settings">
                <?php foreach (self::getEditTools() as $edit_tool_name => $edit_tool) : ?>
                    <li class="elementor-editor-element-setting elementor-editor-element-<?= $edit_tool_name ?>"
                        title="<?= $edit_tool['title'] ?>">
                        <i class="eicon-<?= $edit_tool['icon'] ?>" aria-hidden="true"></i>
                        <span class="elementor-screen-only"><?= $edit_tool['title'] ?></span>
                    </li>
                <?php endforeach ?>
            </ul>
            <div class="elementor-column-percents-tooltip"></div>
        </div>
        <?php
    }

    /**
     * Render column output in the editor.
     *
     * Used to generate the live preview, using a Backbone JavaScript template.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-column-wrap">
            <div class="elementor-background-overlay"></div>
            <div class="elementor-widget-wrap"></div>
        </div>
        <?php
    }

    /**
     * Before column rendering.
     *
     * Used to add stuff before the column element.
     *
     * @since 1.0.0
     * @access public
     */
    public function beforeRender()
    {
        $settings = $this->getSettingsForDisplay();

        $has_background_overlay = in_array($settings['background_overlay_background'], ['classic', 'gradient'], true) ||
            in_array($settings['background_overlay_hover_background'], ['classic', 'gradient'], true);

        $column_wrap_classes = ['elementor-column-wrap'];

        if ($this->getChildren()) {
            $column_wrap_classes[] = ' elementor-element-populated';
        }

        $this->addRenderAttribute([
            '_inner_wrapper' => [
                'class' => $column_wrap_classes,
            ],
            '_widget_wrapper' => [
                'class' => ['elementor-widget-wrap'],
            ],
            '_background_overlay' => [
                'class' => ['elementor-background-overlay'],
            ],
        ]);
        ?>
        <<?= $this->getHtmlTag() . ' ' . $this->getRenderAttributeString('_wrapper') ?>>
            <div <?= $this->getRenderAttributeString('_inner_wrapper') ?>>
            <?php if ($has_background_overlay) : ?>
                <div <?= $this->getRenderAttributeString('_background_overlay') ?>></div>
            <?php endif ?>
        <div <?= $this->getRenderAttributeString('_widget_wrapper') ?>>
        <?php
    }

    /**
     * After column rendering.
     *
     * Used to add stuff after the column element.
     *
     * @since 1.0.0
     * @access public
     */
    public function afterRender()
    {
        ?>
                </div>
            </div>
        </<?= $this->getHtmlTag() ?>>
        <?php
    }

    /**
     * Add column render attributes.
     *
     * Used to add attributes to the current column wrapper HTML tag.
     *
     * @since 1.3.0
     * @access protected
     */
    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        $is_inner = $this->getData('isInner');

        $column_type = !empty($is_inner) ? 'inner' : 'top';

        $settings = $this->getSettings();

        $this->addRenderAttribute('_wrapper', 'class', [
            'elementor-column',
            'elementor-col-' . $settings['_column_size'],
            'elementor-' . $column_type . '-column',
        ]);
    }

    /**
     * Get default child type.
     *
     * Retrieve the column child type based on element data.
     *
     * @since 1.0.0
     * @access protected
     *
     * @param array $element_data Element ID.
     *
     * @return ElementBase Column default child type.
     */
    protected function _getDefaultChildType(array $element_data)
    {
        if ('section' === $element_data['elType']) {
            return Plugin::$instance->elements_manager->getElementTypes('section');
        }

        return Plugin::$instance->widgets_manager->getWidgetTypes($element_data['widgetType']);
    }

    /**
     * Get HTML tag.
     *
     * Retrieve the column element HTML tag.
     *
     * @since 1.5.3
     * @access private
     *
     * @return string Column HTML tag.
     */
    private function getHtmlTag()
    {
        $html_tag = $this->getSettings('html_tag');

        if (empty($html_tag)) {
            $html_tag = 'div';
        }

        return $html_tag;
    }
}
