<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

trait CarouselTrait
{
    public function getScriptDepends()
    {
        return ['jquery-slick'];
    }

    protected function registerCarouselSection(array $args = [])
    {
        $self = ${'this'};
        $default_slides_desktop = isset($args['default_slides_desktop']) ? $args['default_slides_desktop'] : 4;
        $default_slides_tablet = isset($args['default_slides_tablet']) ? $args['default_slides_tablet'] : 3;
        $default_slides_mobile = isset($args['default_slides_mobile']) ? $args['default_slides_mobile'] : 2;
        $variable_width = isset($args['variable_width']) ? ['variable_width' => ''] : [];

        $self->startControlsSection(
            'section_additional_options',
            [
                'label' => __('Carousel'),
                'condition' => [
                    'enable_slider' => 'yes'
                ]
            ]
        );

        $self->addControl(
            'default_slides_desktop',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => (int) $default_slides_desktop,
                'frontend_available' => true,
            ]
        );
        $self->addControl(
            'default_slides_tablet',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => (int) $default_slides_tablet,
                'frontend_available' => true,
            ]
        );
        $self->addControl(
            'default_slides_mobile',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => (int) $default_slides_mobile,
                'frontend_available' => true,
            ]
        );

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $self->addControl(
            'rows',
            [
                'label' => __('Rows'),
                'type' => ControlsManager::NUMBER,
                'default' => 1,
                'frontend_available' => true,
            ]
        );
        $self->addResponsiveControl(
            'slides_to_show',
            [
                'label' => __('Slides to Show'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                ] + $slides_to_show,
                'frontend_available' => true,
            ]
        );

        $self->addResponsiveControl(
            'slides_to_scroll',
            [
                'label' => __('Slides to Scroll'),
                'type' => ControlsManager::SELECT,
                'description' => __('Set how many slides are scrolled per swipe.'),
                'options' => [
                    '' => __('Default'),
                ] + $slides_to_show,
                'condition' => [
                    'slides_to_show!' => '1',
                    'center_mode' => '',
                ] + $variable_width,
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'center_mode',
            [
                'label' => __('Center Mode'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $self->addResponsiveControl(
            'center_padding',
            [
                'label' => __('Center Padding'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'default' => [
                    'size' => 50,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                    '%' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'center_mode!' => '',
                ] + $variable_width,
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'navigation',
            [
                'label' => __('Navigation'),
                'type' => ControlsManager::SELECT,
                'default' => 'both',
                'options' => [
                    'both' => __('Arrows and Dots'),
                    'arrows' => __('Arrows'),
                    'dots' => __('Dots'),
                    'none' => __('None'),
                ],
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'additional_options',
            [
                'label' => __('Additional Options'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $self->addControl(
            'pause_on_hover',
            [
                'label' => __('Pause on Hover'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $self->addResponsiveControl(
            'autoplay',
            [
                'label' => __('Autoplay'),
                'type' => ControlsManager::SELECT,
                'default' => 'yes',
                'options' => [
                    'yes' => __('Yes'),
                    '' => __('No'),
                ],
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed'),
                'type' => ControlsManager::NUMBER,
                'default' => 5000,
                'frontend_available' => true,
            ]
        );

        $self->addResponsiveControl(
            'infinite',
            [
                'label' => __('Infinite Loop'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'tablet_default' => '',
                'mobile_default' => '',
                'options' => [
                    'yes' => __('Yes'),
                    '' => __('No'),
                ],
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'effect',
            [
                'label' => __('Effect'),
                'type' => ControlsManager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => __('Slide'),
                    'fade' => __('Fade'),
                ],
                'condition' => [
                    'slides_to_show' => '1',
                    'center_mode' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'speed',
            [
                'label' => __('Animation Speed'),
                'type' => ControlsManager::NUMBER,
                'default' => 500,
                'frontend_available' => true,
            ]
        );

        $self->addControl(
            'direction',
            [
                'label' => __('Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'ltr',
                'options' => [
                    'ltr' => __('Left'),
                    'rtl' => __('Right'),
                ],
                'frontend_available' => true,
            ]
        );

        $self->endControlsSection();
    }

    protected function registerNavigationStyleSection()
    {
        $self = ${'this'};

        $self->startControlsSection(
            'section_style_navigation',
            [
                'label' => __('Navigation'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'navigation' => ['arrows', 'dots', 'both'],
                ],
            ]
        );

        $self->addControl(
            'heading_style_arrows',
            [
                'label' => __('Arrows'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $self->addControl(
            'arrows_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => __('Inside'),
                    'outside' => __('Outside'),
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $self->addControl(
            'arrows_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $self->addControl(
            'arrows_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-image-carousel-wrapper .slick-slider .slick-next:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['arrows', 'both'],
                ],
            ]
        );

        $self->addControl(
            'heading_style_dots',
            [
                'label' => __('Dots'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $self->addControl(
            'dots_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'outside',
                'options' => [
                    'outside' => __('Outside'),
                    'inside' => __('Inside'),
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $self->addControl(
            'dots_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $self->addControl(
            'dots_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-dots li button:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation' => ['dots', 'both'],
                ],
            ]
        );

        $self->endControlsSection();
    }

    protected function renderCarousel(array &$settings, array &$slides)
    {
        if (empty($slides)) {
            return;
        }
        $self = ${'this'};

        $item_desktop = $settings['default_slides_desktop'];
        $item_tablet = $settings['default_slides_tablet'];
        $item_mobile = $settings['default_slides_mobile'];
        if($settings['slides_to_show']){
            $item_desktop = $settings['slides_to_show'];
        }
        if($settings['slides_to_show_tablet']){
            $item_tablet = $settings['slides_to_show_tablet'];
        }
        if($settings['slides_to_show_tablet']){
            $item_mobile = $settings['slides_to_show_mobile'];
        }
        $widget_name = '';
        if(isset($settings['widget_name'])){
            $widget_name = $settings['widget_name'];
        }
        $self->addRenderAttribute('carousel', 'class', 'elementor-image-carousel slick-block '. $widget_name .' items-desktop-'. $item_desktop . ' items-tablet-' . $item_tablet . ' items-mobile-'. $item_mobile);

        if ('none' !== $settings['navigation']) {
            if ('dots' !== $settings['navigation']) {
                $self->addRenderAttribute('carousel', 'class', 'slick-arrows-' . $settings['arrows_position']);
            }

            if ('arrows' !== $settings['navigation']) {
                $self->addRenderAttribute('carousel', 'class', 'slick-dots-' . $settings['dots_position']);
            }
        }
        ?>
        <div class="elementor-image-carousel-wrapper elementor-slick-slider" dir="<?= $settings['direction'] ?>">
            <div <?= $self->getRenderAttributeString('carousel') ?>>
            <?php foreach ($slides as &$slide) : ?>
                <?= $slide ?>
            <?php endforeach ?>
            </div>
        </div>
        <?php
    }
}
