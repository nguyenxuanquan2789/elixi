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
 * Elementor star rating widget.
 *
 * Elementor widget that displays star rating.
 *
 * @since 2.3.0
 */
class WidgetStarRating extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve star rating widget name.
     *
     * @since 2.3.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'star-rating';
    }

    /**
     * Get widget title.
     *
     * Retrieve star rating widget title.
     *
     * @since 2.3.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Star Rating');
    }

    /**
     * Get widget icon.
     *
     * Retrieve star rating widget icon.
     *
     * @since 2.3.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-rating';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.3.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['star', 'rating', 'rate', 'review'];
    }

    /**
     * Register star rating widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.3.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_rating',
            [
                'label' => __('Rating'),
            ]
        );

        $this->addControl(
            'rating_scale',
            [
                'label' => __('Rating Scale'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '5' => '0-5',
                    '10' => '0-10',
                ],
                'default' => '5',
            ]
        );

        $this->addControl(
            'rating',
            [
                'label' => __('Rating'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'default' => 5,
            ]
        );

        $this->addControl(
            'star_style',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'star_fontawesome' => 'Font Awesome',
                    'star_unicode' => 'Unicode',
                ],
                'default' => 'star_fontawesome',
                'render_type' => 'template',
                'prefix_class' => 'elementor--star-style-',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'unmarked_star_style',
            [
                'label' => __('Unmarked Style'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'solid' => [
                        'title' => __('Solid'),
                        'icon' => 'fa fa-star',
                    ],
                    'outline' => [
                        'title' => __('Outline'),
                        'icon' => 'fa fa-star-o',
                    ],
                ],
                'default' => 'solid',
            ]
        );

        $this->addControl(
            'title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'separator' => 'before',
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor-star-rating--align-',
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title_style',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'title!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating__title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-star-rating__title',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addResponsiveControl(
            'title_gap',
            [
                'label' => __('Gap'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}}:not(.elementor-star-rating--align-justify) .elementor-star-rating__title' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_stars_style',
            [
                'label' => __('Stars'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_space',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-right: {{SIZE}}{{UNIT}}',
                    'body.lang-rtl {{WRAPPER}} .elementor-star-rating i:not(:last-of-type)' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'stars_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating i:before' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'stars_unmarked_color',
            [
                'label' => __('Unmarked Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-star-rating i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function getRating()
    {
        $settings = $this->getSettingsForDisplay();
        $rating_scale = (int) $settings['rating_scale'];
        $rating = (float) $settings['rating'] > $rating_scale ? $rating_scale : $settings['rating'];

        return [$rating, $rating_scale];
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function renderStars($icon)
    {
        $rating_data = $this->getRating();
        $rating = $rating_data[0];
        $floored_rating = (int) $rating;
        $stars_html = '';

        for ($stars = 1; $stars <= $rating_data[1]; $stars++) {
            if ($stars <= $floored_rating) {
                $stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
            } elseif ($floored_rating + 1 === $stars && $rating !== $floored_rating) {
                $stars_html .= '<i class="elementor-star-' . ($rating - $floored_rating) * 10 . '">' . $icon . '</i>';
            } else {
                $stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
            }
        }

        return $stars_html;
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $rating_data = $this->getRating();
        $textual_rating = $rating_data[0] . '/' . $rating_data[1];
        $icon = '&#61445;';

        if ('star_fontawesome' === $settings['star_style']) {
            if ('outline' === $settings['unmarked_star_style']) {
                $icon = '&#61446;';
            }
        } elseif ('star_unicode' === $settings['star_style']) {
            $icon = '&#9733;';

            if ('outline' === $settings['unmarked_star_style']) {
                $icon = '&#9734;';
            }
        }

        $this->addRenderAttribute('icon_wrapper', [
            'class' => 'elementor-star-rating',
            'title' => $textual_rating,
            'itemtype' => 'http://schema.org/Rating',
            'itemscope' => '',
            'itemprop' => 'reviewRating',
        ]);

        $schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
        $stars_element =
            '<div ' . $this->getRenderAttributeString('icon_wrapper') . '>' .
                $this->renderStars($icon) . ' ' . $schema_rating .
            '</div>';
        ?>
        <div class="elementor-star-rating__wrapper">
            <?php if (!empty($settings['title'])) : ?>
                <div class="elementor-star-rating__title"><?= $settings['title'] ?></div>
            <?php endif ?>
            <?= $stars_element ?>
        </div>
        <?php
    }

    /**
     * @since 2.3.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <#
        var getRating = function() {
            var ratingScale = parseInt( settings.rating_scale, 10 ),
                rating = settings.rating > ratingScale ? ratingScale : settings.rating;

            return [ rating, ratingScale ];
        },
        ratingData = getRating(),
        rating = ratingData[0],
        textualRating = ratingData[0] + '/' + ratingData[1],
        renderStars = function( icon ) {
            var starsHtml = '',
                flooredRating = Math.floor( rating );

            for ( var stars = 1; stars <= ratingData[1]; stars++ ) {
                if ( stars <= flooredRating  ) {
                    starsHtml += '<i class="elementor-star-full">' + icon + '</i>';
                } else if ( flooredRating + 1 === stars && rating !== flooredRating ) {
                    starsHtml +=
                        '<i class="elementor-star-' + (rating - flooredRating).toFixed(1) * 10 + '">' + icon + '</i>';
                } else {
                    starsHtml += '<i class="elementor-star-empty">' + icon + '</i>';
                }
            }

            return starsHtml;
        },
        icon = '&#61445;';

        if ( 'star_fontawesome' === settings.star_style ) {
            if ( 'outline' === settings.unmarked_star_style ) {
                icon = '&#61446;';
            }
        } else if ( 'star_unicode' === settings.star_style ) {
            icon = '&#9733;';

            if ( 'outline' === settings.unmarked_star_style ) {
                icon = '&#9734;';
            }
        }

        view.addRenderAttribute( 'iconWrapper', 'class', 'elementor-star-rating' );
        view.addRenderAttribute( 'iconWrapper', 'itemtype', 'http://schema.org/Rating' );
        view.addRenderAttribute( 'iconWrapper', 'title', textualRating );
        view.addRenderAttribute( 'iconWrapper', 'itemscope', '' );
        view.addRenderAttribute( 'iconWrapper', 'itemprop', 'reviewRating' );

        var stars = renderStars( icon );
        #>
        <div class="elementor-star-rating__wrapper">
            <# if ( ! _.isEmpty( settings.title ) ) { #>
                <div class="elementor-star-rating__title">{{ settings.title }}</div>
            <# } #>
            <div {{{ view.getRenderAttributeString( 'iconWrapper' ) }}} >
                {{{ stars }}}
                <span itemprop="ratingValue" class="elementor-screen-only">{{ textualRating }}</span>
            </div>
        </div>
        <?php
    }
}
