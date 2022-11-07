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
 * Elementor common widget.
 *
 * Elementor base widget that gives you all the advanced options of the basic
 * widget.
 *
 * @since 1.0.0
 */
class WidgetCommon extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve common widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'common';
    }

    /**
     * Show in panel.
     *
     * Whether to show the common widget in the panel or not.
     *
     * @since 1.0.0
     * @access public
     *
     * @return bool Whether to show the widget in the panel.
     */
    public function showInPanel()
    {
        return false;
    }

    /**
     * Register common widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            '_section_style',
            [
                'label' => __('Advanced'),
                'tab' => ControlsManager::TAB_ADVANCED,
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
            '_margin',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            '_z_index',
            [
                'label' => __('Z-Index'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'selectors' => [
                    '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                ],
                'label_block' => false,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            '_element_id',
            [
                'label' => __('CSS ID'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id'),
                'label_block' => false,
                'style_transfer' => false,
            ]
        );

        $this->addControl(
            '_css_classes',
            [
                'label' => __('CSS Classes'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'prefix_class' => '',
                'title' => __('Add your custom class WITHOUT the dot. e.g: my-class'),
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
            '_animation',
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
                    '_animation!' => '',
                ],
            ]
        );

        $this->addControl(
            '_animation_delay',
            [
                'label' => __('Animation Delay') . ' (ms)',
                'type' => ControlsManager::NUMBER,
                'default' => '',
                'min' => 0,
                'step' => 100,
                'condition' => [
                    '_animation!' => '',
                ],
                'render_type' => 'none',
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_background',
            [
                'label' => __('Background'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->startControlsTabs('_tabs_background');

        $this->startControlsTab(
            '_tab_background_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => '_background',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            '_tab_background_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => '_background_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addControl(
            '_background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
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
            '_section_border',
            [
                'label' => __('Border'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->startControlsTabs('_tabs_border');

        $this->startControlsTab(
            '_tab_border_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => '_border',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->addResponsiveControl(
            '_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => '_box_shadow',
                'selector' => '{{WRAPPER}} > .elementor-widget-container',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            '_tab_border_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => '_border_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addResponsiveControl(
            '_border_radius_hover',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => '_box_shadow_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
            ]
        );

        $this->addControl(
            '_border_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'separator' => 'before',
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'transition: background {{_background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            '_section_position',
            [
                'label' => __('Custom Positioning'),
                'tab' => ControlsManager::TAB_ADVANCED,
            ]
        );

        $this->addResponsiveControl(
            '_element_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'inherit' => __('Full Width') . ' (100%)',
                    'auto' => __('Inline') . ' (auto)',
                    'initial' => __('Custom'),
                ],
                'selectors_dictionary' => [
                    'inherit' => '100%',
                ],
                'prefix_class' => 'elementor-widget%s__width-',
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}; max-width: {{VALUE}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_custom_width',
            [
                'label' => __('Custom Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'condition' => [
                    '_element_width' => 'initial',
                ],
                'device_args' => [
                    ControlsStack::RESPONSIVE_TABLET => [
                        'condition' => [
                            '_element_width_tablet' => ['initial'],
                        ],
                    ],
                    ControlsStack::RESPONSIVE_MOBILE => [
                        'condition' => [
                            '_element_width_mobile' => ['initial'],
                        ],
                    ],
                ],
                'size_units' => ['px', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_element_vertical_align',
            [
                'label' => __('Vertical Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => __('End'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'condition' => [
                    '_element_width!' => '',
                    '_position' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'align-self: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            '_position_description',
            [
                'raw' => '<strong>' . __('Please note!') . '</strong> ' .
                    __('Custom positioning is not considered best practice for responsive web design and should not be used too frequently.'),
                'type' => ControlsManager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addControl(
            '_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'absolute' => __('Absolute'),
                    'fixed' => __('Fixed'),
                ],
                'prefix_class' => 'elementor-',
                'frontend_available' => true,
            ]
        );

        $start = is_rtl() ? __('Right') : __('Left');
        $end = !is_rtl() ? __('Right') : __('Left');

        $this->addControl(
            '_offset_orientation_h',
            [
                'label' => __('Horizontal Orientation'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => $start,
                        'icon' => 'eicon-h-align-left',
                    ],
                    'end' => [
                        'title' => $end,
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'classes' => 'elementor-control-start-end',
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_x',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_x_end',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => '0',
                ],
                'size_units' => ['px', '%', 'vw', 'vh'],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_h' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addControl(
            '_offset_orientation_v',
            [
                'label' => __('Vertical Orientation'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'toggle' => false,
                'default' => 'start',
                'options' => [
                    'start' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'end' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'render_type' => 'ui',
                'condition' => [
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_y',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v!' => 'end',
                    '_position!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            '_offset_y_end',
            [
                'label' => __('Offset'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vh' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                    'vw' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px', '%', 'vh', 'vw'],
                'default' => [
                    'size' => '0',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    '_offset_orientation_v' => 'end',
                    '_position!' => '',
                ],
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
}
