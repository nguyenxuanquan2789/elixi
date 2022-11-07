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
 * Elementor video widget.
 *
 * Elementor widget that displays a video player.
 *
 * @since 1.0.0
 */
class WidgetVideo extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve video widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'video';
    }

    /**
     * Get widget title.
     *
     * Retrieve video widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Video');
    }

    /**
     * Get widget icon.
     *
     * Retrieve video widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-youtube';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the video widget belongs to.
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
        return ['video', 'player', 'embed', 'youtube', 'vimeo', 'dailymotion'];
    }

    /**
     * Register video widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_video',
            [
                'label' => __('Video'),
            ]
        );

        $this->addControl(
            'video_type',
            [
                'label' => __('Source'),
                'type' => ControlsManager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __('YouTube'),
                    'vimeo' => __('Vimeo'),
                    'dailymotion' => __('Dailymotion'),
                    'hosted' => __('Self Hosted'),
                ],
            ]
        );

        $this->addControl(
            'youtube_url',
            [
                'label' => __('Link'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                    // 'categories' => [
                    //     TagsModule::POST_META_CATEGORY,
                    //     TagsModule::URL_CATEGORY,
                    // ],
                ],
                'placeholder' => __('Enter your URL') . ' (YouTube)',
                'default' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
                'label_block' => true,
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->addControl(
            'vimeo_url',
            [
                'label' => __('Link'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                    // 'categories' => [
                    //     TagsModule::POST_META_CATEGORY,
                    //     TagsModule::URL_CATEGORY,
                    // ],
                ],
                'placeholder' => __('Enter your URL') . ' (Vimeo)',
                'default' => 'https://vimeo.com/235215203',
                'label_block' => true,
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->addControl(
            'dailymotion_url',
            [
                'label' => __('Link'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                    // 'categories' => [
                    //     TagsModule::POST_META_CATEGORY,
                    //     TagsModule::URL_CATEGORY,
                    // ],
                ],
                'placeholder' => __('Enter your URL') . ' (Dailymotion)',
                'default' => 'https://www.dailymotion.com/video/x6tqhqb',
                'label_block' => true,
                'condition' => [
                    'video_type' => 'dailymotion',
                ],
            ]
        );

        $this->addControl(
            'insert_url',
            [
                // 'label' => __('External URL'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'yes',
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->addControl(
            'hosted_url',
            [
                // 'label' => __('Choose File'),
                'type' => ControlsManager::HIDDEN,
                // 'dynamic' => [
                //     'active' => true,
                //     'categories' => [
                //         TagsModule::MEDIA_CATEGORY,
                //     ],
                // ],
                // 'media_type' => 'video',
                'condition' => [
                    'video_type' => 'hosted',
                    'insert_url' => '',
                ],
            ]
        );

        $this->addControl(
            'external_url',
            [
                'label' => __('URL'),
                'type' => ControlsManager::URL,
                'autocomplete' => false,
                'show_external' => false,
                'label_block' => true,
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                    // 'categories' => [
                    //     TagsModule::POST_META_CATEGORY,
                    //     TagsModule::URL_CATEGORY,
                    // ],
                ],
                'media_type' => 'video',
                'placeholder' => __('Enter your URL'),
                'condition' => [
                    'video_type' => 'hosted',
                    'insert_url' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'start',
            [
                'label' => __('Start Time'),
                'type' => ControlsManager::NUMBER,
                'description' => __('Specify a start time (in seconds)'),
                'condition' => [
                    'loop' => '',
                ],
            ]
        );

        $this->addControl(
            'end',
            [
                'label' => __('End Time'),
                'type' => ControlsManager::NUMBER,
                'description' => __('Specify an end time (in seconds)'),
                'condition' => [
                    'loop' => '',
                    'video_type' => ['youtube', 'hosted'],
                ],
            ]
        );

        $this->addControl(
            'video_options',
            [
                'label' => __('Video Options'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'autoplay',
            [
                'label' => __('Autoplay'),
                'type' => ControlsManager::SWITCHER,
            ]
        );

        $this->addControl(
            'mute',
            [
                'label' => __('Mute'),
                'type' => ControlsManager::SWITCHER,
            ]
        );

        $this->addControl(
            'loop',
            [
                'label' => __('Loop'),
                'type' => ControlsManager::SWITCHER,
                'condition' => [
                    'video_type!' => 'dailymotion',
                ],
            ]
        );

        $this->addControl(
            'controls',
            [
                'label' => __('Player Controls'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type!' => 'vimeo',
                ],
            ]
        );

        $this->addControl(
            'showinfo',
            [
                'label' => __('Video Info'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type' => ['dailymotion'],
                ],
            ]
        );

        $this->addControl(
            'modestbranding',
            [
                'label' => __('Modest Branding'),
                'type' => ControlsManager::SWITCHER,
                'condition' => [
                    'video_type' => ['youtube'],
                    'controls' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'logo',
            [
                'label' => __('Logo'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type' => ['dailymotion'],
                ],
            ]
        );

        $this->addControl(
            'color',
            [
                'label' => __('Controls Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => [
                    'video_type' => ['vimeo', 'dailymotion'],
                ],
            ]
        );

        // YouTube.
        $this->addControl(
            'yt_privacy',
            [
                'label' => __('Privacy Mode'),
                'type' => ControlsManager::SWITCHER,
                'description' => __('When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.'),
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        $this->addControl(
            'rel',
            [
                'label' => __('Suggested Videos'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Current Video Channel'),
                    'yes' => __('Any Video'),
                ],
                'condition' => [
                    'video_type' => 'youtube',
                ],
            ]
        );

        // Vimeo.
        $this->addControl(
            'vimeo_title',
            [
                'label' => __('Intro Title'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->addControl(
            'vimeo_portrait',
            [
                'label' => __('Intro Portrait'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->addControl(
            'vimeo_byline',
            [
                'label' => __('Intro Byline'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'default' => 'yes',
                'condition' => [
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $this->addControl(
            'download_button',
            [
                'label' => __('Download Button'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->addControl(
            'poster',
            [
                'label' => __('Poster'),
                'type' => ControlsManager::MEDIA,
                'condition' => [
                    'video_type' => 'hosted',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::HIDDEN,
                'default' => 'youtube',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_image_overlay',
            [
                'label' => __('Image Overlay'),
            ]
        );

        $this->addControl(
            'show_image_overlay',
            [
                'label' => __('Image Overlay'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
            ]
        );

        $this->addControl(
            'image_overlay',
            [
                'label' => __('Choose Image'),
                'type' => ControlsManager::MEDIA,
                'seo' => true,
                'default' => [
                    'url' => Utils::getPlaceholderImageSrc(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'show_image_overlay' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'lazy_load',
            [
                'label' => __('Lazy Load'),
                'type' => ControlsManager::SWITCHER,
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'video_type!' => 'hosted',
                ],
            ]
        );

        $this->addControl(
            'show_play_icon',
            [
                'label' => __('Play Icon'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                ],
            ]
        );

        $this->addControl(
            'lightbox',
            [
                'label' => __('Lightbox'),
                'type' => ControlsManager::SWITCHER,
                'frontend_available' => true,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_video_style',
            [
                'label' => __('Video'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'aspect_ratio',
            [
                'label' => __('Aspect Ratio'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '169' => '16:9',
                    '219' => '21:9',
                    '43' => '4:3',
                    '32' => '3:2',
                    '11' => '1:1',
                ],
                'default' => '169',
                'prefix_class' => 'elementor-aspect-ratio-',
                'frontend_available' => true,
            ]
        );

        $this->addGroupControl(
            GroupControlCssFilter::getType(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .elementor-wrapper',
            ]
        );

        $this->addControl(
            'play_icon_title',
            [
                'label' => __('Play Icon'),
                'type' => ControlsManager::HEADING,
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'show_play_icon' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'play_icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'play_icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTextShadow::getType(),
            [
                'name' => 'play_icon_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-custom-embed-play i',
                'fields_options' => [
                    'text_shadow_type' => [
                        'label' => _x('Shadow', 'Text Shadow Control'),
                    ],
                ],
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_lightbox_style',
            [
                'label' => __('Lightbox'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_image_overlay' => 'yes',
                    'image_overlay[url]!' => '',
                    'lightbox' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'lightbox_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'lightbox_ui_color',
            [
                'label' => __('UI Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'lightbox_ui_color_hover',
            [
                'label' => __('UI Hover Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->addControl(
            'lightbox_video_width',
            [
                'label' => __('Content Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 50,
                    ],
                ],
                'selectors' => [
                    '(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'lightbox_content_position',
            [
                'label' => __('Content Position'),
                'type' => ControlsManager::SELECT,
                'frontend_available' => true,
                'options' => [
                    '' => __('Center'),
                    'top' => __('Top'),
                ],
                'selectors' => [
                    '#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
                ],
                'selectors_dictionary' => [
                    'top' => 'top: 60px',
                ],
            ]
        );

        $this->addResponsiveControl(
            'lightbox_content_animation',
            [
                'label' => __('Entrance Animation'),
                'type' => ControlsManager::ANIMATION,
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render video widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $video_url = $settings[$settings['video_type'] . '_url'];

        if ('hosted' === $settings['video_type']) {
            $video_url = $this->getHostedVideoUrl();
        }

        if (empty($video_url)) {
            return;
        }

        if ('hosted' === $settings['video_type']) {
            ob_start();

            $this->renderHostedVideo();

            $video_html = ob_get_clean();
        } else {
            $embed_params = $this->getEmbedParams();

            $embed_options = $this->getEmbedOptions();

            $video_html = Embed::getEmbedHtml($video_url, $embed_params, $embed_options);
        }

        if (empty($video_html)) {
            echo esc_url($video_url);

            return;
        }

        $this->addRenderAttribute('video-wrapper', 'class', 'elementor-wrapper');

        if (!$settings['lightbox']) {
            $this->addRenderAttribute('video-wrapper', 'class', 'elementor-fit-aspect-ratio');
        }

        $this->addRenderAttribute('video-wrapper', [
            'class' => 'elementor-open-' . ($settings['lightbox'] ? 'lightbox' : 'inline'),
        ]);
        ?>
        <div <?= $this->getRenderAttributeString('video-wrapper') ?>>
            <?php
            if (!$settings['lightbox']) {
                echo $video_html; // XSS ok.
            }

            if ($this->hasImageOverlay()) :
                $this->addRenderAttribute('image-overlay', 'class', 'elementor-custom-embed-image-overlay');

                if ($settings['lightbox']) {
                    if ('hosted' === $settings['video_type']) {
                        $lightbox_url = $video_url;
                    } else {
                        $lightbox_url = Embed::getEmbedUrl($video_url, $embed_params, $embed_options);
                    }

                    $lightbox_options = [
                        'type' => 'video',
                        'videoType' => $settings['video_type'],
                        'url' => $lightbox_url,
                        'modalOptions' => [
                            'id' => 'elementor-lightbox-' . $this->getId(),
                            'entranceAnimation' => $settings['lightbox_content_animation'],
                            'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
                            'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
                            'videoAspectRatio' => $settings['aspect_ratio'],
                        ],
                    ];

                    if ('hosted' === $settings['video_type']) {
                        $lightbox_options['videoParams'] = $this->getHostedParams();
                    }

                    $this->addRenderAttribute('image-overlay', [
                        'data-elementor-open-lightbox' => 'yes',
                        'data-elementor-lightbox' => json_encode($lightbox_options),
                    ]);

                    if (Plugin::$instance->editor->isEditMode()) {
                        $this->addRenderAttribute('image-overlay', [
                            'class' => 'elementor-clickable',
                        ]);
                    }
                }
                ?>
                <div <?= $this->getRenderAttributeString('image-overlay') ?>>
                    <?= GroupControlImageSize::getAttachmentImageHtml($settings, 'image_overlay') ?>
                    <?php if ('yes' === $settings['show_play_icon']) : ?>
                        <div class="elementor-custom-embed-play" role="button">
                            <i class="fa fa-play-circle" aria-hidden="true"></i>
                            <span class="elementor-screen-only"><?= __('Play Video') ?></span>
                        </div>
                    <?php endif ?>
                </div>
                <?php
            endif ?>
        </div>
        <?php
    }

    /**
     * Render video widget as plain content.
     *
     * Override the default behavior, by printing the video URL insted of rendering it.
     *
     * @since 1.4.5
     * @access public
     */
    public function renderPlainContent()
    {
        $settings = $this->getSettingsForDisplay();

        if ('hosted' !== $settings['video_type']) {
            $url = $settings[$settings['video_type'] . '_url'];
        } else {
            $url = $this->getHostedVideoUrl();
        }

        echo esc_url($url);
    }

    /**
     * Get embed params.
     *
     * Retrieve video widget embed parameters.
     *
     * @since 1.5.0
     * @access public
     *
     * @return array Video embed parameters.
     */
    public function getEmbedParams()
    {
        $settings = $this->getSettingsForDisplay();

        $params = [];

        if ($settings['autoplay'] && !$this->hasImageOverlay()) {
            $params['autoplay'] = '1';
        }

        $params_dictionary = [];

        if ('youtube' === $settings['video_type']) {
            $params_dictionary = [
                'loop',
                'controls',
                'mute',
                'rel',
                'modestbranding',
            ];

            if ($settings['loop']) {
                $video_properties = Embed::getVideoProperties($settings['youtube_url']);

                $params['playlist'] = $video_properties['video_id'];
            }

            $params['start'] = $settings['start'];

            $params['end'] = $settings['end'];

            $params['wmode'] = 'opaque';
        } elseif ('vimeo' === $settings['video_type']) {
            $params_dictionary = [
                'loop',
                'mute' => 'muted',
                'vimeo_title' => 'title',
                'vimeo_portrait' => 'portrait',
                'vimeo_byline' => 'byline',
            ];

            $params['color'] = str_replace('#', '', $settings['color']);

            $params['autopause'] = '0';
        } elseif ('dailymotion' === $settings['video_type']) {
            $params_dictionary = [
                'controls',
                'mute',
                'showinfo' => 'ui-start-screen-info',
                'logo' => 'ui-logo',
            ];

            $params['ui-highlight'] = str_replace('#', '', $settings['color']);

            $params['start'] = $settings['start'];

            $params['endscreen-enable'] = '0';
        }

        foreach ($params_dictionary as $key => $param_name) {
            $setting_name = $param_name;

            if (is_string($key)) {
                $setting_name = $key;
            }

            $setting_value = $settings[$setting_name] ? '1' : '0';

            $params[$param_name] = $setting_value;
        }

        return $params;
    }

    /**
     * Whether the video widget has an overlay image or not.
     *
     * Used to determine whether an overlay image was set for the video.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return bool Whether an image overlay was set for the video.
     */
    protected function hasImageOverlay()
    {
        $settings = $this->getSettingsForDisplay();

        return !empty($settings['image_overlay']['url']) && 'yes' === $settings['show_image_overlay'];
    }

    /**
     * @since 2.1.0
     * @access private
     */
    private function getEmbedOptions()
    {
        $settings = $this->getSettingsForDisplay();

        $embed_options = [];

        if ('youtube' === $settings['video_type']) {
            $embed_options['privacy'] = $settings['yt_privacy'];
        } elseif ('vimeo' === $settings['video_type']) {
            $embed_options['start'] = $settings['start'];
        }

        $embed_options['lazy_load'] = !empty($settings['lazy_load']);

        return $embed_options;
    }

    /**
     * @since 2.1.0
     * @access private
     */
    private function getHostedParams()
    {
        $settings = $this->getSettingsForDisplay();

        $video_params = [];

        foreach (['autoplay', 'loop', 'controls'] as $option_name) {
            if ($settings[$option_name]) {
                $video_params[$option_name] = '';
            }
        }

        if ($settings['mute']) {
            $video_params['muted'] = 'muted';
        }

        if (!$settings['download_button']) {
            $video_params['controlsList'] = 'nodownload';
        }

        if ($settings['poster']['url']) {
            $video_params['poster'] = $settings['poster']['url'];
        }

        return $video_params;
    }

    /**
     * @param bool $from_media
     *
     * @return string
     * @since 2.1.0
     * @access private
     */
    private function getHostedVideoUrl()
    {
        $settings = $this->getSettingsForDisplay();

        if (!empty($settings['insert_url'])) {
            $video_url = $settings['external_url']['url'];
        } else {
            $video_url = $settings['hosted_url']['url'];
        }

        if (empty($video_url)) {
            return '';
        }

        if ($settings['start'] || $settings['end']) {
            $video_url .= '#t=';
        }

        if ($settings['start']) {
            $video_url .= $settings['start'];
        }

        if ($settings['end']) {
            $video_url .= ',' . $settings['end'];
        }

        return $video_url;
    }

    /**
     *
     * @since 2.1.0
     * @access private
     */
    private function renderHostedVideo()
    {
        $video_url = $this->getHostedVideoUrl();

        if (empty($video_url)) {
            return;
        }

        $video_params = $this->getHostedParams();
        ?>
        <video class="elementor-video" src="<?= esc_url($video_url) ?>"
            <?= Utils::renderHtmlAttributes($video_params) ?>></video>
        <?php
    }
}
