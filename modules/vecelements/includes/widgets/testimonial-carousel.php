<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or exit;

class WidgetTestimonialCarousel extends WidgetBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'testimonial-carousel';
    }

    public function getTitle()
    {
        return __('Testimonial Carousel');
    }

    public function getIcon()
    {
        return 'eicon-testimonial-carousel';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['testimonial', 'blockquote', 'carousel', 'slider'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_testimonials',
            [
                'label' => __('Testimonials'),
            ]
        );

        $sample = [
            'content' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
            'image' => [
                'url' => Utils::getPlaceholderImageSrc(),
            ],
            'name' => 'John Doe',
            'title' => 'Designer',
        ];

        $this->addControl(
            'slides',
            [
                'type' => ControlsManager::REPEATER,
                'default' => [$sample, $sample, $sample],
                'fields' => [
                    [
                        'name' => 'content',
                        'label' => __('Content'),
                        'type' => ControlsManager::TEXTAREA,
                        'rows' => '8',
                        'default' => __('List Item'),
                    ],
                    [
                        'name' => 'image',
                        'label' => __('Add Image'),
                        'type' => ControlsManager::MEDIA,
                        'seo' => 'true',
                        'default' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                    [
                        'name' => 'name',
                        'label' => __('Name'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'John Doe',
                    ],
                    [
                        'name' => 'title',
                        'label' => __('Job'),
                        'type' => ControlsManager::TEXT,
                        'default' => 'Designer',
                    ],
                    [
                        'name' => 'link',
                        'label' => __('Link'),
                        'type' => ControlsManager::URL,
                        'placeholder' => __('https://your-link.com'),
                    ],
                ],
                'title_field' => '<# if (image.url) { #>' .
                    '<img src="{{ elementor.imagesManager.getImageUrl(image) }}" class="ce-repeater-thumb"><# } #>' .
                    '{{{ name || title || image.title || image.alt || image.url.split("/").pop() }}}',
            ]
        );

        $this->addControl(
            'layout',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::SELECT,
                'default' => 'image_inline',
                'options' => [
                    'image_inline' => __('Image Inline'),
                    'image_stacked' => __('Image Stacked'),
                    'image_above' => __('Image Above'),
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'alignment',
            [
                'label' => __('Alignment'),
                'label_block' => false,
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
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->addControl(
            'enable_slider',
            [
                'label' => __('Use carousel'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'yes',
            ]
        );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'default_slides_desktop' => 2,
            'default_slides_tablet' => 2,
            'default_slides_mobile' => 1,
        ]);

        $this->startControlsSection(
            'section_style_testimonials',
            [
                'label' => __('Testimonials'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}}; -webkit-clip-path: inset(0 0 0 {{SIZE}}{{UNIT}}); clip-path: inset(0 0 0 {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'slide_min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
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
                'selectors' => [
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'slide_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'slide_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}}  .slick-slider .slick-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'slide_border_size',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}}  .slick-slider .slick-slide-inner' => 'border-style: solid; border-width: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->addControl(
            'slide_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'slide_border_size[top]!' => '',
                ],
            ]
        );

        $this->addControl(
            'slide_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%'],
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-slider .slick-slide-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('tabs_style_content');

        $this->startControlsTab(
            'tab_style_content',
            [
                'label' => __('Content'),
            ]
        );

        $this->addResponsiveControl(
            'content_gap',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'content_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'content_typography',
                'label' => __('Typography'),
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-testimonial-content',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_name',
            [
                'label' => __('Name'),
            ]
        );

        $this->addControl(
            'name_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'name_typography',
                'label' => __('Typography'),
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-testimonial-name',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_style_title',
            [
                'label' => __('Job'),
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'label' => __('Typography'),
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-testimonial-job',
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'image_gap',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout!' => 'image_inline',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'image_border',
            [
                'label' => __('Border'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes'),
                'label_off' => __('No'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-style: solid;',
                ],
            ]
        );

        $this->addControl(
            'image_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_border!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_border_size',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                ],
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_border!' => '',
                ],
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-wrapper .elementor-testimonial-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->registerNavigationStyleSection();
    }

    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $layout_class = 'elementor-testimonial-image-position-' . ('image_inline' == $settings['layout'] ? 'aside' : 'top');
        $slides = [];

        foreach ($settings['slides'] as &$slide) {
            $has_link = !empty($slide['link']['url']);

            if ($has_link) {
                $this->setRenderAttribute('link', [
                    'href' => $slide['link']['url'],
                    'target' => $slide['link']['is_external'] ? '_blank' : null,
                    'rel' => !empty($slide['link']['nofollow']) ? 'nofollow' : null,
                ]);
            }
            ob_start();
            ?>
            <div class="slick-slide-inner">
                <div class="elementor-testimonial-wrapper">
                <?php if ('image_above' == $settings['layout'] && !empty($slide['image']['url'])) : ?>
                    <div class="elementor-testimonial-meta <?= $layout_class ?>">
                        <div class="elementor-testimonial-meta-inner">
                            <div class="elementor-testimonial-image">
                            <?php if ($has_link) : ?>
                                <a <?= $this->getRenderAttributeString('link') ?>>
                                    <?= GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto') ?>
                                </a>
                            <?php else : ?>
                                <?= GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto') ?>
                            <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <?php if (!empty($slide['content'])) : ?>
                    <div class="elementor-testimonial-content"><?= $slide['content'] ?></div>
                <?php endif ?>
                    <div class="elementor-testimonial-meta <?= $layout_class ?>">
                        <div class="elementor-testimonial-meta-inner">
                        <?php if ('image_above' != $settings['layout'] && !empty($slide['image']['url'])) : ?>
                            <div class="elementor-testimonial-image">
                            <?php if ($has_link) : ?>
                                <a <?= $this->getRenderAttributeString('link') ?>>
                                    <?= GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto') ?>
                                </a>
                            <?php else : ?>
                                <?= GroupControlImageSize::getAttachmentImageHtml($slide, 'image', 'auto') ?>
                            <?php endif ?>
                            </div>
                        <?php endif ?>
                            <div class="elementor-testimonial-details">
                            <?php if (!empty($slide['name'])) : ?>
                                <div class="elementor-testimonial-name">
                                <?php if ($has_link) : ?>
                                    <a <?= $this->getRenderAttributeString('link') ?>><?= $slide['name'] ?></a>
                                <?php else : ?>
                                    <?= $slide['name'] ?>
                                <?php endif ?>
                                </div>
                            <?php endif ?>
                            <?php if (!empty($slide['title'])) : ?>
                                <div class="elementor-testimonial-job">
                                <?php if ($has_link) : ?>
                                    <a <?= $this->getRenderAttributeString('link') ?>><?= $slide['title'] ?></a>
                                <?php else : ?>
                                    <?= $slide['title'] ?>
                                <?php endif ?>
                                </div>
                            <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $slides[] = ob_get_clean();
        }

        $this->renderCarousel($settings, $slides);
    }
}
