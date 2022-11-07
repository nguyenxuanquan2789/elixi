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
 * Elementor image widget.
 *
 * Elementor widget that displays an image into the page.
 *
 * @since 1.0.0
 */
class WidgetImage extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve image widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'image';
    }

    /**
     * Get widget title.
     *
     * Retrieve image widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Image');
    }

    /**
     * Get widget icon.
     *
     * Retrieve image widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-image';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the image widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['basic'];
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
        return ['image', 'photo', 'visual'];
    }

    /**
     * Register image widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_image',
            [
                'label' => __('Image'),
            ]
        );

        $this->addControl(
            'image',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
            ]
        );

        $this->addResponsiveControl(
            'align',
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
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'caption',
            [
                'label' => __('Custom Caption'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => __('Enter your image caption'),
                'dynamic' => [
                    'active' => true,
                ],
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
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('https://your-link.com'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
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

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'width',
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
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space',
            [
                'label' => __('Max Width') . ' (%)',
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
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'separator_panel_style',
            [
                'type' => ControlsManager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->startControlsTabs('image_effects');

        $this->startControlsTab(
            'normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'opacity',
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
                    '{{WRAPPER}} .elementor-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .elementor-image img',
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
            'opacity_hover',
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
                    '{{WRAPPER}} .elementor-image:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .elementor-image:hover img',
            ]
        );

        $this->addControl(
            'background_hover_transition',
            [
                'label' => __('Transition Duration'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

        $this->addControl(
            'hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .elementor-image img',
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'image_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-image img',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_caption',
            [
                'label' => __('Caption'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'caption!' => '',
                ],
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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addControl(
            'caption_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-image-caption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .widget-image-caption',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'caption_text_shadow',
                'selector' => '{{WRAPPER}} .widget-image-caption',
            ]
        );

        $this->addResponsiveControl(
            'caption_space',
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
                    '{{WRAPPER}} .widget-image-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render image widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['image']['url'])) {
            return;
        }

        $has_caption = !empty($settings['caption']);

        $this->addRenderAttribute('wrapper', 'class', 'elementor-image');

        if (!empty($settings['shape'])) {
            $this->addRenderAttribute('wrapper', 'class', 'elementor-image-shape-' . $settings['shape']);
        }

        $link = $this->getLinkUrl($settings);

        if ($link) {
            $this->addRenderAttribute('link', [
                'href' => $link['url'],
                'data-elementor-open-lightbox' => $settings['open_lightbox'],
            ]);

            if (Plugin::$instance->editor->isEditMode()) {
                $this->addRenderAttribute('link', [
                    'class' => 'elementor-clickable',
                ]);
            }

            if (!empty($link['is_external'])) {
                $this->addRenderAttribute('link', 'target', '_blank');
            }

            if (!empty($link['nofollow'])) {
                $this->addRenderAttribute('link', 'rel', 'nofollow');
            }
        }?>
        <div <?= $this->getRenderAttributeString('wrapper') ?>>
        <?php if ($has_caption) : ?>
            <figure class="ce-caption">
        <?php endif ?>
        <?php if ($link) : ?>
            <a <?= $this->getRenderAttributeString('link') ?>>
        <?php endif ?>
            <?= GroupControlImageSize::getAttachmentImageHtml($settings) ?>
        <?php if ($link) : ?>
            </a>
        <?php endif ?>
        <?php if ($has_caption) : ?>
            <figcaption class="widget-image-caption ce-caption-text"><?= $settings['caption'] ?></figcaption>
        <?php endif ?>
        <?php if ($has_caption) : ?>
            </figure>
        <?php endif ?>
        </div>
        <?php
    }

    /**
     * Render image widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <# if ( settings.image.url ) {
            var image_url = elementor.imagesManager.getImageUrl( settings.image );

            if ( ! image_url ) {
                return;
            }

            var link_url;

            if ( 'custom' === settings.link_to ) {
                link_url = settings.link.url;
            } else if ( 'file' === settings.link_to ) {
                link_url = image_url;
            }

            #><div class="elementor-image{{ settings.shape ? ' elementor-image-shape-' + settings.shape : '' }}"><#
            var lightbox = 'data-elementor-open-lightbox',
                imgClass = '';

            if ( '' !== settings.hover_animation ) {
                imgClass = 'elementor-animation-' + settings.hover_animation;
            }

            if ( settings.caption ) {
                #><figure class="ce-caption"><#
            }

            if ( link_url ) {
                #><a class="elementor-clickable" {{ lightbox }}="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
            }
            #><img src="{{ image_url }}" class="{{ imgClass }}"><#

            if ( link_url ) {
                #></a><#
            }

            if ( settings.caption ) {
                #><figcaption class="widget-image-caption ce-caption-text">{{{ settings.caption }}}</figcaption><#
            }

            if ( settings.caption ) {
                #></figure><#
            }

            #></div><#
        } #>
        <?php
    }

    /**
     * Retrieve image widget link URL.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $settings
     *
     * @return array|string|false An array/string containing the link URL, or false if no link.
     */
    private function getLinkUrl($settings)
    {
        if ('none' === $settings['link_to']) {
            return false;
        }

        if ('custom' === $settings['link_to']) {
            if (empty($settings['link']['url'])) {
                return false;
            }
            return $settings['link'];
        }

        return [
            'url' => Helper::getMediaLink($settings['image']['url']),
        ];
    }
}
