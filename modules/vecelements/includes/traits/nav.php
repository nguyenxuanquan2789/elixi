<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

trait NavTrait
{
    protected static $li_class = 'menu-item menu-item-type-%s menu-item-%s%s%s';

    protected $indicator = 'fa fa-caret-down';

    public function getScriptDepends()
    {
        return ['smartmenus'];
    }

    
    protected function registerNavStyleSection(array $args = [])
    {
        $self = ${'this'};
        $devices = isset($args['devices']) ? $args['devices'] : [
            'desktop',
            'tablet',
            'mobile',
        ];

        $self->startControlsSection(
            'section_style_nav',
            [
                'label' => $self->getTitle(),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => isset($args['condition']) ? $args['condition'] : [],
            ]
        );

        $self->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'menu_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-nav--main',
            ]
        );

        $self->startControlsTabs('tabs_menu_item_style');

        $self->startControlsTab(
            'tab_menu_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $self->addControl(
            'color_menu_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );
        $self->addControl(
            'bgcolor_menu_item',
            [
                'label' => __('Background color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $self->endControlsTab();

        $self->startControlsTab(
            'tab_menu_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $self->addControl(
            'color_menu_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'color: {{VALUE}}',
                ],
            ]
        );
        $self->addControl(
            'background_menu_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $self->addControl(
            'menu_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => array(
                    'border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $self->endControlsTab();

        $self->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border',
                'label' => __('Border'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .blockcart > a',
            )
        );
        $self->endControlsSection();
    }

    protected function registerDropdownStyleSection(array $args = [])
    {
        $self = ${'this'};

        $self->startControlsSection(
            'section_style_dropdown',
            [
                'label' => __('Dropdown'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => isset($args['dropdown_condition']) ? $args['dropdown_condition'] : [],
            ]
        );

        $self->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'dropdown_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'exclude' => ['line_height'],
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown',
            ]
        );

        $self->startControlsTabs('tabs_dropdown_item_style');

        $self->startControlsTab(
            'tab_dropdown_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $self->addControl(
            'color_dropdown_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e), {{WRAPPER}} .elementor-menu-toggle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $self->addControl(
            'background_color_dropdown_item',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $self->endControlsTab();

        $self->startControlsTab(
            'tab_dropdown_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $self->addControl(
            'color_dropdown_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-menu-toggle:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $self->addControl(
            'background_color_dropdown_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:hover, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
        $self->addControl(
            'dropdown_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => array(
                    'border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-nav--dropdown a:hover, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active' => 'border-color: {{VALUE}}',
                ),
            )
        );
        $self->endControlsTab();

        $self->endControlsTabs();

        $self->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown',
                'separator' => 'before',
            ]
        );

        $self->addResponsiveControl(
            'dropdown_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $self->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'dropdown_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-nav--main .elementor-nav--dropdown, {{WRAPPER}} .elementor-nav__container.elementor-nav--dropdown',
            ]
        );

        $self->addResponsiveControl(
            'padding_horizontal_dropdown_item',
            [
                'label' => __('Horizontal Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',

            ]
        );

        $self->addResponsiveControl(
            'padding_vertical_dropdown_item',
            [
                'label' => __('Vertical Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $self->addControl(
            'heading_dropdown_divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $self->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'dropdown_divider',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown li:not(:last-child)',
                'exclude' => ['width'],
            ]
        );

        $self->addControl(
            'dropdown_divider_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_divider_border!' => '',
                ],
            ]
        );

        $self->addResponsiveControl(
            'dropdown_top_distance',
            [
                'label' => __('Distance'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main > .elementor-nav > li > .elementor-nav--dropdown, ' .
                    '{{WRAPPER}} .elementor-nav__container.elementor-nav--dropdown' => 'margin-top: {{SIZE}}{{UNIT}} !important',
                ],
                'separator' => 'before',
            ]
        );

        $self->endControlsSection();
    }
}
