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
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class WidgetPromo extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'promo';
    }

    /**
     * Get widget title.
     *
     * Retrieve heading widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Promo');
    }

    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-banner';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
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
        return ['theme-elements'];
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
        return ['promo', 'banner', 'text'];
    }

    public function getScriptDepends()
    {
        return ['jquery-slick'];
    }

    /**
     * Register heading widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_title',
            [
                'label' => __('Content'),
            ]
        );
            $repeater = new Repeater();

            $repeater->addControl(
                'promo_content',
                [
                    'label' => __('Content'),
                    'type' => ControlsManager::WYSIWYG,
                    'show_label' => false,
                ]
            );

            $this->addControl(
                'promos',
                [
                    'label' => __('Promo Contents'),
                    'type' => ControlsManager::REPEATER,
                    'fields' => $repeater->getControls(),
                    'default' => [
                        [
                            'promo_content' => __('Click edit button to change this text.'),
                        ],
                    ],
                    //'title_field' => '{{{ tab_title }}}',
                ]
            );
            $this->addControl(
                'close_btn',
                [
                    'label' => __('Close button'),
                    'type' => ControlsManager::SWITCHER,
                    'label_on' => __('On'),
                    'label_off' => __('Off'),
                    'return_value' => 'yes',
                ]
            );
            $this->addControl(
                'close_time',
                [
                    'label' => __('Cookie time to hide'),
                    'type' => ControlsManager::NUMBER,
                    'default' => 1,
                    'description' => __('Unit: day'),
                    'condition' => [
                        'close_btn' => 'yes',
                    ],
                ]
            );
            $this->addControl(
                'autoplay',
                [
                    'label' => __('Auto play'),
                    'type' => ControlsManager::SWITCHER,
                    'label_on' => __('Yes'),
                    'label_off' => __('No'),
                    'return_value' => 'yes',
                    'frontend_available' => true,
                ]
            );
            $this->addControl(
                'autoplay_speed',
                [
                    'label' => __('Delay time'),
                    'type' => ControlsManager::NUMBER,
                    'default' => 3,
                    'description' => __('Unit: second'),
                    'frontend_available' => true,
                    'condition' => [
                        'auto_play' => 'yes',
                    ],
                ]
            );
            $this->addControl(
                'navigation',
                [
                    'label' => __('Show navigation'),
                    'type' => ControlsManager::SWITCHER,
                    'label_on' => __('Yes'),
                    'label_off' => __('No'),
                    'return_value' => 'arrows',
                    'frontend_available' => true,
                ]
            );
            $this->addControl(
                'default_slides_desktop',
                [
                    'type' => ControlsManager::HIDDEN,
                    'default' => 1,
                    'frontend_available' => true,
                ]
            );
            $this->addControl(
                'default_slides_tablet',
                [
                    'type' => ControlsManager::HIDDEN,
                    'default' => 1,
                    'frontend_available' => true,
                ]
            );
            $this->addControl(
                'default_slides_mobile',
                [
                    'type' => ControlsManager::HIDDEN,
                    'default' => 1,
                    'frontend_available' => true,
                ]
            );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title_style',
            [
                'label' => __('Style'),
                'tab' => ControlsManager::TAB_STYLE,
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
                        '{{WRAPPER}} .promo-widget .promo-item' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
            $this->addResponsiveControl(
                'vertical',
                [
                    'label' => __('Vertical'),
                    'type' => ControlsManager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => __('Top'),
                            'icon' => 'fa fa-long-arrow-up',
                        ],
                        'center' => [
                            'title' => __('Middle'),
                            'icon' => 'fa fa-arrows-h',
                        ],
                        'flex-end' => [
                            'title' => __('bottom'),
                            'icon' => 'fa fa-long-arrow-down',
                        ],
                    ],
                    'default' => 'center',
                    'selectors' => [
                        '{{WRAPPER}} .promo-widget' => 'align-items: {{VALUE}};',
                    ],
                ]
            );
            $this->addControl(
                'height',
                [
                    'label' => __('Height'),
                    'type' => ControlsManager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 20,
                            'max' => 200,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .promo-widget' => 'min-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->addGroupControl(
                GroupControlBackground::getType(),
                [
                    'name' => 'background',
                    'types' => ['none', 'classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .promo-widget', 
                ]
            );
            $this->addControl(
                'text_color',
                [
                    'label' => __('Text Color'),
                    'type' => ControlsManager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .promo-widget, {{WRAPPER}} .promo-widget p' => 'color: {{VALUE}}', 
                    ],
                ]
            );
            $this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .promo-widget, {{WRAPPER}} .promo-widget p',
				]
			);

        $this->endControlsSection();
    }

    /**
     * Render heading widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $id = 'promo-'. $this->getId(); 
        if (empty($settings['promos']) || (isset($_COOKIE[$id]) && $_COOKIE[$id])) {
            return;
        }

        ?>
        <div class="elementor-promo promo-widget">
            <div class="promo-inner">
                <div class="elementor-block-carousel slick-block items-desktop-1 items-tablet-1 items-mobile-1">
                    <?php foreach($settings['promos'] as $promo): ?>
                    <div class="promo-item">
                        <?= $promo['promo_content']; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if($settings['close_btn'] == 'yes'): ?>
                    <span class="promo-close-btn" <?php if($settings['close_time']): ?> data-close_time="<?= $settings['close_time']; ?>"<?php endif; ?>>Close icon here</span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render heading widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate(){}
}
