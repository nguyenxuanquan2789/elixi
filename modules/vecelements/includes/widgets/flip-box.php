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

class WidgetFlipBox extends WidgetBase
{
    public function getName()
    {
        return 'flip-box';
    }

    public function getTitle()
    {
        return __('Flip Box');
    }

    public function getIcon()
    {
        return 'eicon-flip-box';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['flip', 'box', 'cta', 'banner'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_a',
            [
                'label' => __('Front'),
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
                'default' => 'icon',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image',
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
            'title_text_a',
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
            'description_text_a',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
                'placeholder' => __('Enter your description'),
            ]
        );

        $this->addControl(
            'title_size_a',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => __('H1'),
                    'h2' => __('H2'),
                    'h3' => __('H3'),
                    'h4' => __('H4'),
                    'h5' => __('H5'),
                    'h6' => __('H6'),
                    'div' => __('div'),
                    'span' => __('span'),
                    'p' => __('p'),
                ],
                'default' => 'h3',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_b',
            [
                'label' => __('Back'),
            ]
        );

        $this->addControl(
            'graphic_element_b',
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
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image_b',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'condition' => [
                    'graphic_element_b' => 'image',
                ],
            ]
        );

        $this->addControl(
            'icon_b',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'fa fa-star',
                'condition' => [
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_view_b',
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
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_shape_b',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'graphic_element_b' => 'icon',
                    'icon_view_b!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'title_text_b',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'default' => __('This is the heading'),
                'placeholder' => __('Enter your title'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_text_b',
            [
                'show_label' => false,
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.'),
                'placeholder' => __('Enter your description'),
            ]
        );

        $this->addControl(
            'title_size_b',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => __('H1'),
                    'h2' => __('H2'),
                    'h3' => __('H3'),
                    'h4' => __('H4'),
                    'h5' => __('H5'),
                    'h6' => __('H6'),
                    'div' => __('div'),
                    'span' => __('span'),
                    'p' => __('p'),
                ],
                'default' => 'h3',
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
            'section_style_flip_box',
            [
                'label' => __('Flip Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'height',
            [
                'label' => __('Height'),
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
                    '{{WRAPPER}} .elementor-flip-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-side, {{WRAPPER}} .elementor-flip-box-overlay' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'heading_hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'flip_effect',
            [
                'label' => __('Flip Effect'),
                'type' => ControlsManager::SELECT,
                'default' => 'flip',
                'options' => [
                    'flip' => 'Flip',
                    'slide' => 'Slide',
                    'push' => 'Push',
                    'zoom-in' => 'Zoom In',
                    'zoom-out' => 'Zoom Out',
                    'fade' => 'Fade',
                ],
                'prefix_class' => 'elementor-flip-box--effect-',
            ]
        );

        $this->addControl(
            'flip_direction',
            [
                'label' => __('Flip Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'up',
                'options' => [
                    'left' => __('Left'),
                    'right' => __('Right'),
                    'up' => __('Up'),
                    'down' => __('Down'),
                ],
                'prefix_class' => 'elementor-flip-box--direction-',
                'condition' => [
                    'flip_effect!' => [
                        'fade',
                        'zoom-in',
                        'zoom-out',
                    ],
                ],
            ]
        );

        $this->addControl(
            'flip_3d',
            [
                'label' => __('3D Depth'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('On'),
                'label_off' => __('Off'),
                'return_value' => 'elementor-flip-box--3d',
                'prefix_class' => '',
                'condition' => [
                    'flip_effect' => 'flip',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_a',
            [
                'label' => __('Front'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_style_a');

        $this->startControlsTab(
            'tab_box_a',
            [
                'label' => __('Box'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_a',
                'types' => ['none', 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            ]
        );

        $this->addControl(
            'background_overlay_a',
            [
                'label' => __('Background Overlay'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_a_background' => 'classic',
                    'background_a_image[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'alignment_a',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'vertical_position_a',
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
                'prefix_class' => 'elementor-flip-box-front--valign-',
            ]
        );

        $this->addResponsiveControl(
            'padding_a',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_a',
                'label' => __('Border Style'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-overlay',
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'shadow_a',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_a',
            [
                'label' => __('Icon'),
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    implode(', ', [
                        '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon',
                        '{{WRAPPER}} .elementor-flip-box-front .elementor-view-default .elementor-icon',
                    ]) => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_a',
            [
                'label' => __('Image'),
                'condition' => [
                    'graphic_element' => 'image',
                ],
            ]
        );

        $this->addControl(
            'image_spacing',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_width',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'image_opacity',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image_border_radius',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_a',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'heading_style_title_a',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'title_spacing_a',
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
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color_a',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-title',
            ]
        );

        $this->addControl(
            'heading_style_description_a',
            [
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_color_a',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography_a',
                'selector' => '{{WRAPPER}} .elementor-flip-box-front .elementor-flip-box-description',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_b',
            [
                'label' => __('Back'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_style_b');

        $this->startControlsTab(
            'tab_box_b',
            [
                'label' => __('Box'),
            ]
        );

        $this->addGroupControl(
            GroupControlBackground::getType(),
            [
                'name' => 'background_b',
                'types' => ['none', 'classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            ]
        );

        $this->addControl(
            'background_overlay_b',
            [
                'label' => __('Background Overlay'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_b_background' => 'classic',
                    'background_b_image[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'alignment_b',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'vertical_position_b',
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
                'prefix_class' => 'elementor-flip-box-back--valign-',
            ]
        );

        $this->addResponsiveControl(
            'padding_b',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'border_b',
                'label' => __('Border Style'),
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-overlay',
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'shadow_b',
                'separator' => 'default',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_image_b',
            [
                'label' => __('Image'),
                'condition' => [
                    'graphic_element_b' => 'image',
                ],
            ]
        );

        $this->addControl(
            'image_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_width_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'image_opacity_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img',
            ]
        );

        $this->addControl(
            'image_border_radius_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-image img' => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_icon_b',
            [
                'label' => __('Icon'),
                'condition' => [
                    'graphic_element_b' => 'icon',
                ],
            ]
        );

        $this->addControl(
            'icon_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color_b',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-flip-box-back .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color_b',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_size_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding_b',
            [
                'label' => __('Icon Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'icon_rotate_b',
            [
                'label' => __('Icon Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'icon_border_radius_b',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_view_b!' => 'default',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_content_b',
            [
                'label' => __('Content'),
            ]
        );

        $this->addControl(
            'heading_style_title_b',
            [
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'title_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_color_b',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-title',
            ]
        );

        $this->addControl(
            'heading_description_style_b',
            [
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'description_spacing_b',
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
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-button' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'button!' => '',
                ],
            ]
        );

        $this->addControl(
            'description_color_b',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography_b',
                'selector' => '{{WRAPPER}} .elementor-flip-box-back .elementor-flip-box-description',
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

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
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
            'tab_button_hover',
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
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
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $flipbox_b_html_tag = 'div';
        $button_tag = 'a';
        $link_url = empty($settings['link']['url']) ? false : $settings['link']['url'];

        $this->addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');
        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);
        $this->addRenderAttribute('button_icon', 'class', 'elementor-button-icon elementor-align-icon-' . $settings['button_icon_align']);

        if (!empty($link_url)) {
            if ($settings['link_click'] == 'box' || empty($settings['button'])) {
                $flipbox_b_html_tag = 'a';
                $button_tag = 'button';
                $this->addRenderAttribute('flipbox-back', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('flipbox-back', 'target', '_blank');
                }
                if ($settings['link']['nofollow']) {
                    $this->addRenderAttribute('flipbox-back', 'rel', 'nofollow');
                }
            } else {
                $this->addRenderAttribute('button', 'href', $link_url);

                if ($settings['link']['is_external']) {
                    $this->addRenderAttribute('button', 'target', '_blank');
                }
                if ($settings['link']['nofollow']) {
                    $this->addRenderAttribute('button', 'rel', 'nofollow');
                }
            }
        }
        if ('icon' === $settings['graphic_element']) {
            $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-icon-wrapper');
            $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-view-' . $settings['icon_view']);

            if ('default' != $settings['icon_view']) {
                $this->addRenderAttribute('icon-wrapper-front', 'class', 'elementor-shape-' . $settings['icon_shape']);
            }
            if (!empty($settings['icon'])) {
                $this->addRenderAttribute('icon_front', 'class', $settings['icon']);
            }
        }
        if ('icon' === $settings['graphic_element_b']) {
            $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-icon-wrapper');
            $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-view-' . $settings['icon_view_b']);

            if ('default' != $settings['icon_view_b']) {
                $this->addRenderAttribute('icon-wrapper-back', 'class', 'elementor-shape-' . $settings['icon_shape_b']);
            }
            if (!empty($settings['icon_b'])) {
                $this->addRenderAttribute('icon_b', 'class', $settings['icon_b']);
            }
        }
        ?>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <?php if ('icon' === $settings['graphic_element']) : ?>
                        <div <?= $this->getRenderAttributeString('icon-wrapper-front') ?>>
                            <div class="elementor-icon">
                                <i <?= $this->getRenderAttributeString('icon_front') ?>></i>
                            </div>
                        </div>
                    <?php elseif ('image' === $settings['graphic_element']) : ?>
                        <div class="elementor-flip-box-image">
                            <?= GroupControlImageSize::getAttachmentImageHtml($settings) ?>
                        </div>
                    <?php endif ?>
                    <?php if (!empty($settings['title_text_a'])) : ?>
                        <<?= $settings['title_size_a'] ?> class="elementor-flip-box-title">
                            <?= $settings['title_text_a'] ?>
                        </<?= $settings['title_size_a'] ?>>
                    <?php endif ?>
                    <?php if (!empty($settings['description_text_a'])) : ?>
                        <div class="elementor-flip-box-description"><?= $settings['description_text_a'] ?></div>
                    <?php endif ?>
                    </div>
                </div>
            </div>
            <<?= $flipbox_b_html_tag . ' ' . $this->getRenderAttributeString('flipbox-back') ?>>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <?php if ('none' != $settings['graphic_element_b']) : ?>
                        <?php if ('image' === $settings['graphic_element_b']) : ?>
                        <div class="elementor-flip-box-image">
                            <?= GroupControlImageSize::getAttachmentImageHtml($settings, 'image_b') ?>
                        </div>
                        <?php elseif ('icon' == $settings['graphic_element_b']) : ?>
                            <div <?= $this->getRenderAttributeString('icon-wrapper-back') ?>>
                                <div class="elementor-icon">
                                    <i <?= $this->getRenderAttributeString('icon_b') ?>></i>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endif ?>
                    <?php if (!empty($settings['title_text_b'])) : ?>
                        <<?= $settings['title_size_b'] ?> class="elementor-flip-box-title">
                            <?= $settings['title_text_b'] ?>
                        </<?= $settings['title_size_b'] ?>>
                    <?php endif ?>
                    <?php if (!empty($settings['description_text_b'])) : ?>
                        <div class="elementor-flip-box-description"><?= $settings['description_text_b'] ?></div>
                    <?php endif ?>
                    <?php if (!empty($settings['button'])) : ?>
                        <<?= $button_tag. ' '. $this->getRenderAttributeString('button') ?>>
                        <?php if (!empty($settings['button_icon'])) : ?>
                            <span <?= $this->getRenderAttributeString('button_icon') ?>>
                                <i class="<?= esc_attr($settings['button_icon']) ?>"></i>
                            </span>
                        <?php endif ?>
                            <span class="elementor-button-text"><?= $settings['button'] ?></span>
                        </<?= $button_tag ?>>
                    <?php endif ?>
                    </div>
                </div>
            </<?= $flipbox_b_html_tag ?>>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <#
        if ( 'icon' === settings.graphic_element ) {
            var iconWrapperClasses = 'elementor-icon-wrapper';
            iconWrapperClasses += ' elementor-view-' + settings.icon_view;

            if ( 'default' !== settings.icon_view ) {
                iconWrapperClasses += ' elementor-shape-' + settings.icon_shape;
            }
        }
        if ( 'icon' === settings.graphic_element_b ) {
            var iconWrapperClassesBack = 'elementor-icon-wrapper';
            iconWrapperClassesBack += ' elementor-view-' + settings.icon_view_b;

            if ( 'default' !== settings.icon_view_b ) {
                iconWrapperClassesBack += ' elementor-shape-' + settings.icon_shape_b;
            }
        }

        var titleTagFront = settings.title_size_a,
            titleTagBack = settings.title_size_b,
            btnSizeClass = 'elementor-size-' + settings.button_size,
            wrapperTag = 'div',
            buttonTag = 'div';

        view.addRenderAttribute('button', 'class', ['elementor-button', btnSizeClass]);
        view.addRenderAttribute('flipbox-back', 'class', 'elementor-flip-box-back elementor-flip-box-side');

        if (settings.link) {
            if ( 'box' === settings.link_click || !settings.button ) {
                wrapperTag = 'a';
                buttonTag = 'button';
                view.addRenderAttribute( 'flipbox-back', 'href', settings.link.url );
            } else {
                buttonTag = 'button';
                view.addRenderAttribute('button', 'href', settings.link.url);
            }
        }
        #>
        <div class="elementor-flip-box">
            <div class="elementor-flip-box-front elementor-flip-box-side">
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <# if ( 'icon' === settings.graphic_element ) { #>
                        <div class="{{ iconWrapperClasses }}">
                             <div class="elementor-icon">
                                <i class="{{ settings.icon }}"></i>
                            </div>
                        </div>
                    <# } else if ( 'image' === settings.graphic_element && settings.image.url ) { #>
                        <div class="elementor-flip-box-image">
                            <img src="{{ elementor.imagesManager.getImageUrl( settings.image ) }}">
                        </div>
                    <# } #>
                    <# if ( settings.title_text_a ) { #>
                        <{{ titleTagFront }} class="elementor-flip-box-title">
                            {{{ settings.title_text_a }}}
                        </{{ titleTagFront }}>
                    <# } #>
                    <# if ( settings.description_text_a ) { #>
                        <div class="elementor-flip-box-description">{{{ settings.description_text_a }}}</div>
                    <# } #>
                    </div>
                </div>
            </div>
            <{{ wrapperTag }} {{{ view.getRenderAttributeString('flipbox-back') }}}>
                <div class="elementor-flip-box-overlay">
                    <div class="elementor-flip-box-content">
                    <# if ( 'icon' === settings.graphic_element_b ) { #>
                        <div class="{{ iconWrapperClassesBack }}">
                            <div class="elementor-icon">
                                <i class="{{ settings.icon_b }}"></i>
                            </div>
                        </div>
                    <# } else if ( 'image' === settings.graphic_element_b && settings.image_b.url ) { #>
                        <div class="elementor-flip-box-image">
                            <img src="{{ elementor.imagesManager.getImageUrl( settings.image_b ) }}">
                        </div>
                    <# } #>
                    <# if ( settings.title_text_b ) { #>
                        <{{ titleTagBack }} class="elementor-flip-box-title">
                            {{{ settings.title_text_b }}}
                        </{{ titleTagBack }}>
                    <# } #>
                    <# if ( settings.description_text_b ) { #>
                        <div class="elementor-flip-box-description">{{{ settings.description_text_b }}}</div>
                    <# } #>
                    <# if (settings.button) { #>
                        <{{ buttonTag }} {{{ view.getRenderAttributeString('button') }}}>
                            <# if (settings.button_icon) {#>
                            <span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
                                <i class="{{ settings.button_icon }}"></i>
                            </span>
                            <# } #>
                            <span class="elementor-button-text">{{{ settings.button }}}</span>
                        </{{ buttonTag }}>
                    <# } #>
                    </div>
                </div>
            </{{ wrapperTag }}>
        </div>
        <?php
    }
}
