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
 * Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class WidgetButton extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve button widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'button';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Button');
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-button';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
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
     * Get button sizes.
     *
     * Retrieve an array of button sizes for the button widget.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array An array containing button sizes.
     */
    public static function getButtonSizes()
    {
        return [
            'xs' => __('Extra Small'),
            'sm' => __('Small'),
            'md' => __('Medium'),
            'lg' => __('Large'),
            'xl' => __('Extra Large'),
        ];
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_button',
            [
                'label' => __('Button'),
            ]
        );

        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default'),
                    'info' => __('Info'),
                    'success' => __('Success'),
                    'warning' => __('Warning'),
                    'danger' => __('Danger'),
                ],
                'prefix_class' => 'elementor-button-',
            ]
        );

        $this->addControl(
            'text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Click here'),
                'placeholder' => __('Click here'),
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
                'default' => [
                    'url' => '#',
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );

        $this->addControl(
            'size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'default' => 'sm',
                'options' => self::getButtonSizes(),
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->addControl(
            'icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
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
            'button_css_id',
            [
                'label' => __('Button ID'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id'),
                'label_block' => false,
                'description' => __(
                    'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. ' .
                    'This field allows <code>A-z 0-9</code> & underscore chars without spaces.'
                ),
                'separator' => 'before',

            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} a.elementor-button',
            ]
        );

        $this->startControlsTabs('tabs_button_style');

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
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button' => 'background-color: {{VALUE}};',
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
            'hover_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:not(#e):hover, {{WRAPPER}} a.elementor-button:not(#e):focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_hover_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} a.elementor-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} a.elementor-button:focus' => 'border-color: {{VALUE}};',
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
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-button',
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
                    '{{WRAPPER}} a.elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->addResponsiveControl(
            'text_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('wrapper', 'class', 'elementor-button-wrapper');

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('button', 'href', $settings['link']['url']);
            $this->addRenderAttribute('button', 'class', 'elementor-button-link');

            if ($settings['link']['is_external']) {
                $this->addRenderAttribute('button', 'target', '_blank');
            }

            if ($settings['link']['nofollow']) {
                $this->addRenderAttribute('button', 'rel', 'nofollow');
            }
        }

        $this->addRenderAttribute('button', 'class', 'elementor-button');
        $this->addRenderAttribute('button', 'role', 'button');

        if (!empty($settings['button_css_id'])) {
            $this->addRenderAttribute('button', 'id', $settings['button_css_id']);
        }

        if (!empty($settings['size'])) {
            $this->addRenderAttribute('button', 'class', 'elementor-size-' . $settings['size']);
        }

        if ($settings['hover_animation']) {
            $this->addRenderAttribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }
        ?>
        <div <?= $this->getRenderAttributeString('wrapper') ?>>
            <a <?= $this->getRenderAttributeString('button') ?>>
                <?php $this->renderText() ?>
            </a>
        </div>
        <?php
    }

    /**
     * Render button widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <#
        view.addRenderAttribute( 'button', {
            'class': [
                'elementor-button',
                'elementor-size-' + settings.size,
                'elementor-animation-' + settings.hover_animation
            ],
            'href': settings.link.url
        } );
        view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );

        view.addInlineEditingAttributes( 'text', 'none' );
        #>
        <div class="elementor-button-wrapper">
            <a id="{{ settings.button_css_id }}" {{{ view.getRenderAttributeString( 'button' ) }}} role="button">
                <span class="elementor-button-content-wrapper">
                    <# if ( settings.icon ) { #>
                    <span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
                        <i class="{{ settings.icon }}" aria-hidden="true"></i>
                    </span>
                    <# } #>
                    <span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
                </span>
            </a>
        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function renderText()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute([
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ]);

        $this->addInlineEditingAttributes('text', 'none');
        ?>
        <span <?= $this->getRenderAttributeString('content-wrapper') ?>>
            <?php if (!empty($settings['icon'])) : ?>
            <span <?= $this->getRenderAttributeString('icon-align') ?>>
                <i class="<?= esc_attr($settings['icon']) ?>" aria-hidden="true"></i>
            </span>
            <?php endif ?>
            <span <?= $this->getRenderAttributeString('text') ?>><?= $settings['text'] ?></span>
        </span>
        <?php
    }
}
