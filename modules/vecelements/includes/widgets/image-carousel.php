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
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class WidgetImageCarousel extends WidgetBase
{
    use CarouselTrait;

    /**
     * Get widget name.
     *
     * Retrieve image carousel widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'image-carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve image carousel widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Image Carousel');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image carousel widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-slider-push';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['image', 'photo', 'visual', 'carousel', 'slider'];
    }

    /**
     * Register image carousel widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image_carousel',
            [
                'label' => __('Image Carousel'),
            ]
        );

        $this->addControl(
            'links',
            [
                'type' => ControlsManager::RAW_HTML,
                'raw' => '
                    <style>
                    .elementor-control-links.elementor-hidden-control ~
                    .elementor-control-carousel .elementor-control-link,
                    .elementor-control-links { display: none; }
                    </style>',
                'condition' => [
                    'link_to' => 'custom',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
            ]
        );

        $repeater->addControl(
            'caption',
            [
                'label' => __('Caption'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your caption about the image'),
                'title' => __('Input image caption here'),
            ]
        );

        $repeater->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'label_block' => true,
                'placeholder' => __('http://your-link.com'),
            ]
        );

        $this->addControl(
            'carousel',
            [
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'image' => [
                            'url' => Utils::getPlaceholderImageSrc(),
                        ],
                    ],
                ],
                'title_field' => '<# if (image.url) { #>' .
                    '<img src="{{ elementor.imagesManager.getImageUrl(image) }}" class="ce-repeater-thumb"><# } #>' .
                    '{{{ caption || image.title || image.alt || image.url.split("/").pop() }}}',
            ]
        );

        $this->addControl(
            'link_to',
            [
                'label' => __('Link'),
                'type' => ControlsManager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None'),
                    'file' => __('Media File'),
                    'custom' => __('Custom URL'),
                ],
            ]
        );

        $this->addControl(
            'variable_width',
            [
                'label' => __('Variable Width'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'image_stretch',
            [
                'label' => __('Image Stretch'),
                'type' => ControlsManager::SWITCHER,
            ]
        );

        $this->addResponsiveControl(
            'image_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide-image' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_stretch!' => '',
                ],
            ]
        );

        $this->addControl(
            'open_lightbox',
            [
                'label' => __('Lightbox'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default'),
                    'yes' => __('Yes'),
                    'no' => __('No'),
                ],
                'condition' => [
                    'link_to' => 'file',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->addControl(
            'enable_slider',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'yes',
            ]
        );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'variable_width' => true,
            'default_slides_desktop' => 1,
            'default_slides_tablet' => 1,
            'default_slides_mobile' => 1,
        ]);

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'image_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'custom' => __('Custom'),
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_spacing_custom',
            [
                'label' => __('Image Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}}; -webkit-clip-path: inset(0 0 0 {{SIZE}}{{UNIT}}); clip-path: inset(0 0 0 {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'padding-left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'image_spacing' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide-image',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-wrapper .elementor-image-carousel .slick-slide-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_caption',
            [
                'label' => __('Caption'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'caption_align',
            [
                'label' => __('Alignment'),
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'caption_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-image-carousel-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'caption_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
            ]
        );

        $this->endControlsSection();

        $this->registerNavigationStyleSection();
    }

    public function onImport($widget)
    {
        // Compatibility fix with WP image-carousel
        if (isset($widget['settings']['carousel'][0]['url'])) {
            $carousel = [];
            $import_images = Plugin::$instance->templates_manager->getImportImagesInstance();

            foreach ($widget['settings']['carousel'] as &$img) {
                $image = $import_images->import($img);

                $carousel[] = [
                    '_imported' => true,
                    '_id' => Utils::generateRandomString(),
                    'image' => $image ? $image : [
                        'id' => 0,
                        'url' => Utils::getPlaceholderImageSrc(),
                    ],
                ];
            }

            $widget['settings']['carousel'] = $carousel;
        }

        return $widget;
    }

    /**
     * Render image carousel widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['carousel'])) {
            return;
        }

        $id = $this->getId();
        $edit_mode = Plugin::$instance->editor->isEditMode();
        $slides = [];

        foreach ($settings['carousel'] as $index => &$attachment) {
            if (empty($attachment['image']['url'])) {
                continue;
            }
            $image_html = GroupControlImageSize::getAttachmentImageHtml($attachment, 'image', 'auto', 'slick-slide-image');
            $link = $this->getLinkUrl($attachment, $settings['link_to']);

            if ($link) {
                $link_key = 'link_' . $index;

                $this->addRenderAttribute($link_key, [
                    'href' => $link['url'],
                    'data-elementor-open-lightbox' => $settings['open_lightbox'],
                    'data-elementor-lightbox-slideshow' => $id,
                    'data-elementor-lightbox-index' => $index,
                ]);

                if ($edit_mode) {
                    $this->addRenderAttribute($link_key, [
                        'class' => 'elementor-clickable',
                    ]);
                }

                if (!empty($link['is_external'])) {
                    $this->addRenderAttribute($link_key, 'target', '_blank');
                }

                if (!empty($link['nofollow'])) {
                    $this->addRenderAttribute($link_key, 'rel', 'nofollow');
                }

                $image_html = '<a ' . $this->getRenderAttributeString($link_key) . '>' . $image_html . '</a>';
            }

            $slide_html = '<figure class="slick-slide-inner">' . $image_html;

            if (!empty($attachment['caption'])) {
                $slide_html .=
                    '<figcaption class="elementor-image-carousel-caption">' . $attachment['caption'] . '</figcaption>';
            }

            $slide_html .= '</figure>';

            $slides[] = $slide_html;
        }

        if ('yes' === $settings['variable_width']) {
            $this->addRenderAttribute('carousel', 'class', 'slick-variable-width');
        }

        if ('yes' === $settings['image_stretch']) {
            $this->addRenderAttribute('carousel', 'class', 'slick-image-stretch');
        }

        $this->renderCarousel($settings, $slides);
    }

    /**
     * Retrieve image carousel link URL.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $attachment
     * @param object $instance
     *
     * @return array|string|false An array/string containing the attachment URL, or false if no link.
     */
    private function getLinkUrl($attachment, $link_to)
    {
        if ('none' === $link_to) {
            return false;
        }

        if ('custom' === $link_to) {
            if (empty($attachment['link']['url'])) {
                return false;
            }

            return $attachment['link'];
        }

        return empty($attachment['image']['url']) ? false : [
            'url' => Helper::getMediaLink($attachment['image']['url']),
        ];
    }
}
