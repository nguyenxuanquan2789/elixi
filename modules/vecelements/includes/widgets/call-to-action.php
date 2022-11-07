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

class WidgetCallToAction extends WidgetBase
{
    public function getName()
    {
        return 'call-to-action';
    }

    public function getTitle()
    {
        return __('Call to Action');
    }

    public function getIcon()
    {
        return 'eicon-image-rollover';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['cta', 'banner'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_cta',
            [
                'label' => __('Call to Action'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'classic' => __('Classic'),
                    'cover' => __('Cover'),
                ],
                'render_type' => 'template',
                'prefix_class' => 'elementor-cta--skin-',
                'default' => 'classic',
            ]
        );

        $this->addControl(
            'bg_image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'layout',
            [
                'label' => __('Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'above' => [
                        'title' => __('Above'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-cta-%s-layout-image-',
                'condition' => [
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_min_width',
            [
                'label' => __('Min Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-bg-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                    'layout' => ['left', 'right'],
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-bg-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                    'bg_image[url]!' => '',
                    'layout' => ['', 'above'],
                ],
            ]
        );

        $this->addControl(
            'ribbon_title',
            [
                'label' => __('Ribbon'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'ribbon_horizontal_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'ribbon_title!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'graphic_element',
            [
                'label' => __('Graphic Element'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => __('None'),
                        'icon' => 'fa fa-ban',
                    ],
                    'image' => [
                        'title' => __('Image'),
                        'icon' => 'fa fa-picture-o',
                    ],
                    'icon' => [
                        'title' => __('Icon'),
                        'icon' => 'fa fa-star',
                    ],
                ],
                'default' => 'none',
            ]
        );

        $this->addControl(
            'graphic_image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'condition' => [
                    'graphic_element' => 'image',
                ],
            ]
        );

        $this->addControl(
            'icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'fa fa-star',
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_view',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_shape',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'icon_view!' => 'default',
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('This is the heading'),
                'placeholder' => __('Enter your title'),
                'label_block' => true,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_text',
            [
                'label' => __('Description'),
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
                'placeholder' => __('Enter your description'),
                'rows' => 5,
                'show_label' => false,
            ]
        );

        $this->addControl(
            'title_tag',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'default' => 'h2',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addControl(
            'button',
            [
                'label' => __('Button Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Click Here'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'placeholder' => __('https://your-link.com'),
            ]
        );

        $this->addControl(
            'link_click',
            [
                'label' => __('Apply Link On'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'box' => __('Whole Box'),
                    'button' => __('Button Only'),
                ],
                'default' => 'button',
                'condition' => [
                    'button!' => '',
                    'link[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_icon',
            [
                'label' => __('Button Icon'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'button!' => '',
                    'button_icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'button!' => '',
                    'button_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_box',
            [
                'label' => __('Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'min-height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-content' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'alignment',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-content' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'vertical_position',
            [
                'label' => __('Vertical Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => __('Middle'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => __('Bottom'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'elementor-cta--valign-',
            ]
        );

        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_ribbon',
            [
                'label' => __('Ribbon'),
                'tab' => ControlsManager::TAB_STYLE,
                'show_label' => false,
                'condition' => [
                    'ribbon_title!' => '',
                ],
            ]
        );

        $ribbon_distance_transform = is_rtl()
            ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)'
            : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)'
        ;
        $this->addResponsiveControl(
            'ribbon_distance',
            [
                'label' => __('Distance'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
                ],
            ]
        );

        $this->addControl(
            'ribbon_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'ribbon_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-ribbon-inner' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'ribbon_typography',
                'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-ribbon-inner',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'graphic_element' => 'image',
                ],
            ]
        );

        $this->addControl(
            'graphic_image_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'graphic_image_width',
            [
                'label' => __('Size (%)'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'graphic_image_opacity',
            [
                'label' => __('Opacity'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'graphic_image_border',
                'selector' => '{{WRAPPER}} .elementor-cta-image img',
            ]
        );

        $this->addControl(
            'graphic_image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'graphic_element' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'icon_view' => 'framed',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate',
            [
                'label' => __('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'title',
                            'operator' => '!==',
                            'value' => '',
                        ],
                        [
                            'name' => 'description_text',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'heading_style_title',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Title'),
                'separator' => 'before',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'title_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-cta-title',
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addControl(
            'heading_style_description',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Description'),
                'separator' => 'before',
                'condition' => [
                    'description_text!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'description_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'description_text!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-cta-description',
                'condition' => [
                    'description_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'heading_content_colors',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Colors'),
                'separator' => 'before',
            ]
        );

        $this->startControlsTabs('color_tabs');

        $this->startControlsTab(
            'colors_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Title Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Description Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'description_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'content_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-content' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'colors_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'title_color_hover',
            [
                'label' => __('Title Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addControl(
            'description_color_hover',
            [
                'label' => __('Description Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-description' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'description_text!' => '',
                ],
            ]
        );

        $this->addControl(
            'content_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-content' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'skin' => 'classic',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_button',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'button_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'label' => __('Typography'),
                'selector' => '{{WRAPPER}} .elementor-button',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            ]
        );

        $this->startControlsTabs('button_tabs');

        $this->startControlsTab(
            'button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'button_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button.elementor-button:not(#e), {{WRAPPER}} a.elementor-button:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'button-hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'button_hover_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button.elementor-button:not(#e):hover, {{WRAPPER}} a.elementor-button:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_hover_effects',
            [
                'label' => __('Hover Effects'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'content_hover_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Content'),
                'separator' => 'before',
                'condition' => [
                    'skin' => 'cover',
                ],
            ]
        );

        $this->addControl(
            'content_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => 'None',
                    'enter-from-right' => 'Slide In Right',
                    'enter-from-left' => 'Slide In Left',
                    'enter-from-top' => 'Slide In Up',
                    'enter-from-bottom' => 'Slide In Down',
                    'enter-zoom-in' => 'Zoom In',
                    'enter-zoom-out' => 'Zoom Out',
                    'fade-in' => 'Fade In',
                    'grow' => 'Grow',
                    'shrink' => 'Shrink',
                    'move-right' => 'Move Right',
                    'move-left' => 'Move Left',
                    'move-up' => 'Move Up',
                    'move-down' => 'Move Down',
                    'exit-to-right' => 'Slide Out Right',
                    'exit-to-left' => 'Slide Out Left',
                    'exit-to-top' => 'Slide Out Up',
                    'exit-to-bottom' => 'Slide Out Down',
                    'exit-zoom-in' => 'Zoom In',
                    'exit-zoom-out' => 'Zoom Out',
                    'fade-out' => 'Fade Out',
                ],
                'default' => 'grow',
                'condition' => [
                    'skin' => 'cover',
                ],
            ]
        );

        /*
         * Add class 'elementor-animated-content' to widget when assigned content animation
         */
        $this->addControl(
            'animation_class',
            [
                'label' => 'Animation',
                'type' => ControlsManager::HIDDEN,
                'default' => 'animated-content',
                'prefix_class' => 'elementor-',
                'condition' => [
                    'content_animation!' => '',
                ],
            ]
        );

        $this->addControl(
            'content_animation_duration',
            [
                'label' => __('Animation Duration'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => [
                    'size' => 1000,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-content-item' => 'transition-duration: {{SIZE}}ms',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(2)' => 'transition-delay: calc( {{SIZE}}ms / 3 )',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(3)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 2 )',
                    '{{WRAPPER}}.elementor-cta--sequenced-animation .elementor-content-item:nth-child(4)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 3 )',
                ],
                'condition' => [
                    'content_animation!' => '',
                    'skin' => 'cover',
                ],
            ]
        );

        $this->addControl(
            'sequenced_animation',
            [
                'label' => __('Sequenced Animation'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('On'),
                'label_off' => __('Off'),
                'return_value' => 'elementor-cta--sequenced-animation',
                'prefix_class' => '',
                'condition' => [
                    'content_animation!' => '',
                    'skin' => 'cover',
                ],
            ]
        );

        $this->addControl(
            'background_hover_heading',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Background'),
                'separator' => 'before',
                'condition' => [
                    'skin' => 'cover',
                ],
            ]
        );

        $this->addControl(
            'transformation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => 'None',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'move-left' => 'Move Left',
                    'move-right' => 'Move Right',
                    'move-up' => 'Move Up',
                    'move-down' => 'Move Down',
                ],
                'default' => 'zoom-in',
                'prefix_class' => 'elementor-bg-transform elementor-bg-transform-',
            ]
        );

        $this->startControlsTabs('bg_effects_tabs');

        $this->startControlsTab(
            'normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'overlay_color',
            [
                'label' => __('Overlay Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta:not(:hover) .elementor-cta-bg-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'bg_filters',
                'selector' => '{{WRAPPER}} .elementor-cta-bg',
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
                    'color-burn' => 'Color Burn',
                    'hue' => 'Hue',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'exclusion' => 'Exclusion',
                    'luminosity' => 'Luminosity',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'overlay_color_hover',
            [
                'label' => __('Overlay Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta:hover .elementor-cta-bg-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'bg_filters_hover',
                'selector' => '{{WRAPPER}}:hover .elementor-cta-bg',
            ]
        );

        $this->addControl(
            'effect_duration',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'render_type' => 'template',
                'default' => [
                    'size' => 1500,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-cta .elementor-cta-bg, {{WRAPPER}} .elementor-cta .elementor-cta-bg-overlay' => 'transition-duration: {{SIZE}}ms',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $title_tag = $settings['title_tag'];
        $wrapper_tag = 'div';
        $button_tag = 'a';
        $link_url = empty($settings['link']['url']) ? false : $settings['link']['url'];
        $content_animation = $settings['content_animation'];
        $animation_class = '';
        $print_bg = true;
        $print_content = true;

        if (!empty($settings['bg_image']['url'])) {
            $bg_image = &$settings['bg_image'];

            $this->addRenderAttribute('bg_image', [
                'src' => Helper::getMediaLink($bg_image['url']),
                'alt' => isset($bg_image['alt']) ? $bg_image['alt'] : '',
                'loading' => 'lazy',
            ]);
        } elseif ('classic' === $settings['skin']) {
            $print_bg = false;
        }

        if (empty($settings['title']) && empty($settings['description_text']) && empty($settings['button']) && 'none' === $settings['graphic_element']) {
            $print_content = false;
        }

        $this->addRenderAttribute('title', 'class', [
            'elementor-cta-title',
            'elementor-content-item',
        ]);
        $this->addRenderAttribute('description_text', 'class', [
            'elementor-cta-description',
            'elementor-content-item',
        ]);
        $this->addRenderAttribute('btn', 'class', 'elementor-button elementor-size-' . $settings['button_size']);
        $this->addRenderAttribute('button_icon', 'class', 'elementor-button-icon elementor-align-icon-' . $settings['button_icon_align']);
        $this->addRenderAttribute('button', 'class', 'elementor-button-text');
        $this->addRenderAttribute('graphic_element', 'class', 'elementor-content-item');

        if ('icon' === $settings['graphic_element']) {
            $this->addRenderAttribute('graphic_element', 'class', [
                'elementor-icon-wrapper',
                'elementor-cta-icon',
                'elementor-view-' . $settings['icon_view']
            ]);

            if ('default' !== $settings['icon_view']) {
                $this->addRenderAttribute('graphic_element', 'class', 'elementor-shape-' . $settings['icon_shape']);
            }
            if (!empty($settings['icon'])) {
                $this->addRenderAttribute('icon', 'class', $settings['icon']);
            }
        } elseif ('image' === $settings['graphic_element'] && !empty($settings['graphic_image']['url'])) {
            $this->addRenderAttribute('graphic_element', 'class', 'elementor-cta-image');
        }

        if (!empty($content_animation) && 'cover' == $settings['skin']) {
            $animation_class = 'elementor-animated-item--' . $content_animation;

            $this->addRenderAttribute('title', 'class', $animation_class);
            $this->addRenderAttribute('graphic_element', 'class', $animation_class);
            $this->addRenderAttribute('description_text', 'class', $animation_class);
        }

        if (!empty($link_url)) {
            if ('box' === $settings['link_click'] || empty($settings['button'])) {
                $wrapper_tag = 'a';
                $button_tag = 'button';
                $this->addRenderAttribute('wrapper', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('wrapper', 'target', '_blank');
                }
                if ($settings['link']['nofollow']) {
                    $this->addRenderAttribute('wrapper', 'rel', 'nofollow');
                }
            } else {
                $this->addRenderAttribute('btn', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('btn', 'target', '_blank');
                }
                if ($settings['link']['nofollow']) {
                    $this->addRenderAttribute('btn', 'rel', 'nofollow');
                }
            }
        }

        $this->addInlineEditingAttributes('title', 'none');
        $this->addInlineEditingAttributes('description_text');
        $this->addInlineEditingAttributes('button', 'none');
        ?>
        <<?= $wrapper_tag . ' ' . $this->getRenderAttributeString('wrapper') ?> class="elementor-cta">
        <?php if ($print_bg) : ?>
            <div class="elementor-cta-bg-wrapper">
                <?php if (isset($bg_image)) : ?>
                    <img class="elementor-cta-bg elementor-bg" <?= $this->getRenderAttributeString('bg_image') ?>>
                <?php endif ?>
                <div class="elementor-cta-bg-overlay"></div>
            </div>
        <?php endif ?>
        <?php if ($print_content) : ?>
            <div class="elementor-cta-content">
                <?php if ('image' === $settings['graphic_element'] && !empty($settings['graphic_image']['url'])) : ?>
                    <div <?= $this->getRenderAttributeString('graphic_element') ?>>
                        <?= GroupControlImageSize::getAttachmentImageHtml($settings, 'graphic_image') ?>
                    </div>
                <?php elseif ('icon' === $settings['graphic_element'] && !empty($settings['icon'])) : ?>
                    <div <?= $this->getRenderAttributeString('graphic_element') ?>>
                        <div class="elementor-icon">
                            <i <?= $this->getRenderAttributeString('icon') ?>></i>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (!empty($settings['title'])) : ?>
                    <<?= $title_tag . ' ' . $this->getRenderAttributeString('title') ?>>
                        <?= $settings['title'] ?>
                    </<?= $title_tag ?>>
                <?php endif ?>

                <?php if (!empty($settings['description_text'])) : ?>
                    <div <?= $this->getRenderAttributeString('description_text') ?>>
                        <?= $settings['description_text'] ?>
                    </div>
                <?php endif ?>

                <?php if (!empty($settings['button'])) : ?>
                    <div class="elementor-cta-button-wrapper elementor-content-item <?= $animation_class ?>">
                    <<?= $button_tag . ' ' . $this->getRenderAttributeString('btn') ?>>
                    <?php if (!empty($settings['button_icon'])) : ?>
                        <span <?= $this->getRenderAttributeString('button_icon') ?>>
                            <i class="<?= esc_attr($settings['button_icon']) ?>"></i>
                        </span>
                    <?php endif ?>
                        <span <?= $this->getRenderAttributeString('button') ?>><?= $settings['button'] ?></span>
                    </<?= $button_tag ?>>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>
        <?php if (!empty($settings['ribbon_title'])) : ?>
            <?php
            $this->addRenderAttribute('ribbon-wrapper', 'class', 'elementor-ribbon');

            if (!empty($settings['ribbon_horizontal_position'])) {
                $this->addRenderAttribute(
                    'ribbon-wrapper',
                    'class',
                    'elementor-ribbon-' . $settings['ribbon_horizontal_position']
                );
            }
            ?>
            <div <?= $this->getRenderAttributeString('ribbon-wrapper') ?>>
                <div class="elementor-ribbon-inner"><?= $settings['ribbon_title'] ?></div>
            </div>
        <?php endif ?>
        </<?= $wrapper_tag ?>>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        var wrapperTag = 'div',
            buttonTag = 'a',
            animationClass,
            btnSizeClass = 'elementor-size-' + settings.button_size,
            printBg = true,
            printContent = true;

        if ( 'box' === settings.link_click || !settings.button ) {
            wrapperTag = 'a';
            buttonTag = 'button';
            view.addRenderAttribute( 'wrapper', 'href', '#' );
        }

        if ( settings.bg_image.url ) {
            view.addRenderAttribute( 'bg_image', 'src', elementor.imagesManager.getImageUrl( settings.bg_image ) );
        } else if ( 'classic' === settings.skin ) {
            printBg = false;
        }

        if ( !settings.title && !settings.description_text && !settings.button && 'none' == settings.graphic_element ) {
            printContent = false;
        }

        if ( 'icon' === settings.graphic_element ) {
            var iconWrapperClasses = 'elementor-icon-wrapper';
                iconWrapperClasses += ' elementor-cta-image';
                iconWrapperClasses += ' elementor-view-' + settings.icon_view;
            if ( 'default' !== settings.icon_view ) {
                iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
            }
            view.addRenderAttribute( 'graphic_element', 'class', iconWrapperClasses );

        } else if ( 'image' === settings.graphic_element && settings.graphic_image.url ) {
            var imageUrl = elementor.imagesManager.getImageUrl( settings.graphic_image );

            view.addRenderAttribute( 'graphic_element', 'class', 'elementor-cta-image' );
        }

        if ( settings.content_animation && 'cover' === settings.skin ) {
            var animationClass = 'elementor-animated-item--' + settings.content_animation;

            view.addRenderAttribute( 'title', 'class', animationClass );
            view.addRenderAttribute( 'description_text', 'class', animationClass );
            view.addRenderAttribute( 'graphic_element', 'class', animationClass );
        }

        view.addRenderAttribute( 'title', 'class', 'elementor-cta-title elementor-content-item' );
        view.addRenderAttribute( 'description_text', 'class', 'elementor-cta-description elementor-content-item' );
        view.addRenderAttribute( 'button', 'class', 'elementor-button-text' );
        view.addRenderAttribute( 'graphic_element', 'class', 'elementor-content-item' );

        view.addInlineEditingAttributes( 'title', 'none' );
        view.addInlineEditingAttributes( 'description_text' );
        view.addInlineEditingAttributes( 'button', 'none' );
        #>

        <{{ wrapperTag }} class="elementor-cta" {{{ view.getRenderAttributeString( 'wrapper' ) }}}>

        <# if ( printBg ) { #>
            <div class="elementor-cta-bg-wrapper">
                <# if ( settings.bg_image.url ) { #>
                    <img class="elementor-cta-bg elementor-bg" {{{ view.getRenderAttributeString( 'bg_image' ) }}}>
                <# } #>
                <div class="elementor-cta-bg-overlay"></div>
            </div>
        <# } #>
        <# if ( printContent ) { #>
            <div class="elementor-cta-content">
            <# if ( imageUrl ) { #>
                <div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
                    <img src="{{ imageUrl }}">
                </div>
            <#  } else if ( 'icon' === settings.graphic_element && settings.icon ) { #>
                <div {{{ view.getRenderAttributeString( 'graphic_element' ) }}}>
                    <div class="elementor-icon">
                        <i class="{{ settings.icon }}"></i>
                    </div>
                </div>
            <# } #>
            <# if ( settings.title ) { #>
                <{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>
                    {{{ settings.title }}}
                </{{ settings.title_tag }}>
            <# } #>

            <# if ( settings.description_text ) { #>
                <div {{{ view.getRenderAttributeString( 'description_text' ) }}}>{{{ settings.description_text }}}</div>
            <# } #>

            <# if ( settings.button ) { #>
                <div class="elementor-cta-button-wrapper elementor-content-item {{ animationClass }}">
                    <{{ buttonTag }} href="#" class="elementor-button {{ btnSizeClass }}">
                    <# if ( settings.button_icon ) { #>
                        <span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
                            <i class="{{ settings.button_icon }}"></i>
                        </span>
                    <# } #>
                        <span {{{ view.getRenderAttributeString( 'button' ) }}}>{{{ settings.button }}}</span>
                    </{{ buttonTag }}>
                </div>
            <# } #>
            </div>
        <# } #>
        <# if ( settings.ribbon_title ) {
            var ribbonClasses = 'elementor-ribbon';

            if ( settings.ribbon_horizontal_position ) {
                ribbonClasses += ' elementor-ribbon-' + settings.ribbon_horizontal_position;
            } #>
            <div class="{{ ribbonClasses }}">
                <div class="elementor-ribbon-inner">{{{ settings.ribbon_title }}}</div>
            </div>
        <# } #>
        </{{ wrapperTag }}>
        <?php
    }
}
