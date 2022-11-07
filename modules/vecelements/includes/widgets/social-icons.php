<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

/**
 * Elementor social icons widget.
 *
 * Elementor widget that displays icons to social pages like Facebook and Twitter.
 *
 * @since 1.0.0
 */
class WidgetSocialIcons extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve social icons widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'social-icons';
    }

    /**
     * Get widget title.
     *
     * Retrieve social icons widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Social Icons');
    }

    /**
     * Get widget icon.
     *
     * Retrieve social icons widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-social-icons';
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
        return ['social', 'icon', 'link'];
    }

    /**
     * Register social icons widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_social_icon',
            [
                'label' => __('Social Icons'),
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'icon_source',
            [
                'label' => __('Icon source'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'vecicon' => __('Theme icon'),
                    'awesome' => __('Awesome icon'),
                ],
                'default' => 'awesome',
            ]
        );
        $repeater->addControl(
            'vecicon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'vecicon-logo-facebook',
                'type_icon' => 'vecicon',
                'condition' => [
                    'icon_source' => 'vecicon',
                ],
                'include' => [
                    'vecicon-facebook',
                    'vecicon-google',
                    'vecicon-instagram',
                    'vecicon-line' ,
                    'vecicon-linkedin',
                    'vecicon-meta',
                    'vecicon-pinterest',
                    'vecicon-skype',
                    'vecicon-snapchat',
                    'vecicon-telegram',
                    'vecicon-tumblr',
                    'vecicon-twitter',
                    'vecicon-vk',
                    'vecicon-whatsapp',
                    'vecicon-xing' ,
                    'vecicon-youtube',
                    'vecicon-tiktok',
                    'vecicon-behance',
                    'vecicon-discord',
                    'vecicon-dribbble',
                ],
            ]
        );

        $repeater->addControl(
            'social',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'type_icon' => 'awesome',
                'default' => 'fa fa-android',
                'condition' => [
                    'icon_source' => 'awesome',
                ],
                'include' => [
                    'fa fa-android',
                    'fa fa-apple',
                    'fa fa-behance',
                    'fa fa-bitbucket',
                    'fa fa-codepen',
                    'fa fa-delicious',
                    'fa fa-deviantart',
                    'fa fa-digg',
                    'fa fa-dribbble',
                    'fa fa-envelope',
                    'fa fa-facebook',
                    'fa fa-flickr',
                    'fa fa-foursquare',
                    'fa fa-free-code-camp',
                    'fa fa-github',
                    'fa fa-gitlab',
                    'fa fa-globe',
                    'fa fa-google-plus',
                    'fa fa-houzz',
                    'fa fa-instagram',
                    'fa fa-jsfiddle',
                    'fa fa-link',
                    'fa fa-linkedin',
                    'fa fa-medium',
                    'fa fa-meetup',
                    'fa fa-mixcloud',
                    'fa fa-odnoklassniki',
                    'fa fa-pinterest',
                    'fa fa-product-hunt',
                    'fa fa-reddit',
                    'fa fa-rss',
                    'fa fa-shopping-cart',
                    'fa fa-skype',
                    'fa fa-slideshare',
                    'fa fa-snapchat',
                    'fa fa-soundcloud',
                    'fa fa-spotify',
                    'fa fa-stack-overflow',
                    'fa fa-steam',
                    'fa fa-stumbleupon',
                    'fa fa-telegram',
                    'fa fa-thumb-tack',
                    'fa fa-tripadvisor',
                    'fa fa-tumblr',
                    'fa fa-twitch',
                    'fa fa-twitter',
                    'fa fa-vimeo',
                    'fa fa-vk',
                    'fa fa-weibo',
                    'fa fa-weixin',
                    'fa fa-whatsapp',
                    'fa fa-wordpress',
                    'fa fa-xing',
                    'fa fa-yelp',
                    'fa fa-youtube',
                    'fa fa-500px',
                ],
            ]
        );

        $repeater->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'label_block' => true,
                'default' => [
                    'is_external' => 'true',
                ],
                'placeholder' => __('https://your-link.com'),
            ]
        );
        
        $this->addControl(
            'social_icon_list',
            [
                'label' => __('Social Icons'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'social' => 'fa fa-facebook',
                    ],
                    [
                        'social' => 'fa fa-twitter',
                    ],
                    [
                        'social' => 'fa fa-google-plus',
                    ],
                ],
                'title_field' => '<# if ( icon_source == "social" ) { #>
                    <i class="{{ social }}"></i>  {{{ social.replace( "fa fa-", "" )' .
                        '.replace( "-", " " ).replace( /\b\w/g, function( letter ){ return letter.toUpperCase() } ) }}}
                    <# }else{ #>
                        <i class="{{ vecicon }}"></i>  {{{ vecicon.replace( "vecicon-", "" )' .
                            '.replace( "-", " " ) }}}
                    <# } #>',
            ]
        );

        $this->addControl(
            'shape',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'default' => 'rounded',
                'options' => [
                    'rounded' => __('Rounded'),
                    'square' => __('Square'),
                    'circle' => __('Circle'),
                ],
                'prefix_class' => 'elementor-shape-',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
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
            'section_social_style',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
        $this->addControl(
            'icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Official Color'),
                    'custom' => __('Custom'),
                ],
            ]
        );

        $this->addControl(
            'icon_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'icon_color' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon:not(:hover)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'icon_color' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon:not(:hover) i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->addControl(
            'icon_primary_color_hover',
            [
                'label' => __('Primary Color Hover'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'icon_color' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'icon_secondary_color_hover',
            [
                'label' => __('Secondary Color Hover'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'icon_color' => 'custom',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'em',
                ],
                'tablet_default' => [
                    'unit' => 'em',
                ],
                'mobile_default' => [
                    'unit' => 'em',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
            ]
        );

        $icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

        $this->addResponsiveControl(
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
                    '{{WRAPPER}} .elementor-social-icon:not(:last-child)' => $icon_spacing,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border', // We know this mistake - TODO: 'icon_border' (for hover control condition also)
                'selector' => '{{WRAPPER}} .elementor-social-icon',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_social_hover',
            [
                'label' => __('Icon Hover'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => [
                    'image_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-social-icon:hover' => 'border-color: {{VALUE}};',
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

        $this->endControlsSection();
    }

    /**
     * Render social icons widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $class_animation = '';

        if (!empty($settings['hover_animation'])) {
            $class_animation = ' elementor-animation-' . $settings['hover_animation'];
        }
        ?>
        <div class="elementor-social-icons-wrapper">
        <?php foreach ($settings['social_icon_list'] as $index => $item) :
            $link_key = 'link_' . $index;
            if($item['icon_source'] == 'awesome'){
                $social = str_replace('fa fa-', '', $item['social']);
            }
            if($item['icon_source'] == 'vecicon'){
                $social = str_replace('vecicon-', '', $item['vecicon']);
            }
            $this->addRenderAttribute($link_key, [
                'class' => 'elementor-icon elementor-social-icon elementor-social-icon-' . $social . $class_animation,
                'href' => $item['link']['url'],
            ]);

            if ($item['link']['is_external']) {
                $this->addRenderAttribute($link_key, 'target', '_blank');
            }

            if ($item['link']['nofollow']) {
                $this->addRenderAttribute($link_key, 'rel', 'nofollow');
            }
            ?>
            <a <?= $this->getRenderAttributeString($link_key) ?>>
                <?php if($item['icon_source'] == 'awesome') : ?>
                <i class="<?= $item['social'] ?>"></i>
                <?php endif; ?>
                <?php if($item['icon_source'] == 'vecicon') : ?>
                <i class="<?= $item['vecicon'] ?>"></i>
                <?php endif; ?>
            </a>
        <?php endforeach ?>
        </div>
        <?php
    }

    /**
     * Render social icons widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-social-icons-wrapper">
        <# _.each( settings.social_icon_list, function( item ) {
            if(item.icon_source === 'awesome'){ 
                var social = item.social.replace( 'fa fa-', '' );
            }
            if(item.icon_source === 'vecicon'){ 
                var social = item.vecicon.replace( 'vecicon-', '' );
            }
            var link = item.link ? item.link.url : '',
                linkClass = 'elementor-icon elementor-social-icon elementor-social-icon-' + social; #>
            <a class="{{ linkClass }} elementor-animation-{{ settings.hover_animation }}" href="{{ link }}">
                <# if(item.icon_source === 'awesome'){ #>
                <i class="{{ item.social }}"></i>
                <# } #>
                <# if(item.icon_source === 'vecicon'){ #>
                <i class="{{ item.vecicon }}"></i>
                <# } #>
            </a>
        <# } ); #>
        </div>
        <?php
    }
}
