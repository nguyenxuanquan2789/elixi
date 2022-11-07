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
 * Elementor icon widget.
 *
 * Elementor widget that displays an icon from over 600+ icons.
 *
 * @since 1.0.0
 */
class WidgetIcon extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve icon widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'icon';
    }

    /**
     * Get widget title.
     *
     * Retrieve icon widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Icon');
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-favorite';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the icon widget belongs to.
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
        return ['icon'];
    }

    /**
     * Register icon widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_icon',
            [
                'label' => __('Icon'),
            ]
        );
        $this->addControl(
            'icon_source',
            [
                'label' => __('Icon source'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'vecicon' => __('Theme icon'),
                    'awesome' => __('Awesome icon'),
                ],
                'default' => 'vecicon',
            ]
        );
        $this->addControl(
            'vecicon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'vecicon-heart1',
                'label_block' => true,
                'type_icon' => 'vecicon',
                'condition' => [
                    'icon_source' => 'vecicon',
                ],
            ]
        );

        $this->addControl(
            'icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'type_icon' => 'awesome',
                'default' => 'fa fa-star',
                'label_block' => true,
                'condition' => [
                    'icon_source' => 'awesome',
                ],
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-view-',
            ]
        );

        $this->addControl(
            'shape',
            [
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ],
                'default' => 'circle',
                'condition' => [
                    'view!' => 'default',
                ],
                'prefix_class' => 'elementor-shape-',
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
                    '{{WRAPPER}} .elementor-icon-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->startControlsTabs('icon_colors');

        $this->startControlsTab(
            'icon_colors_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed a.elementor-icon:not(#e), {{WRAPPER}}.elementor-view-default a.elementor-icon:not(#e)' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addControl(
            'secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon, {{WRAPPER}}.elementor-view-stacked a.elementor-icon:not(#e)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'icon_colors_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'hover_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed a.elementor-icon:not(#e):hover, {{WRAPPER}}.elementor-view-default a.elementor-icon:not(#e):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'hover_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover, {{WRAPPER}}.elementor-view-stacked a.elementor-icon:not(#e):hover' => 'color: {{VALUE}};',
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

        $this->addControl(
            'size',
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
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'icon_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'rotate',
            [
                'label' => __('Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0,
                    'unit' => 'deg',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->addControl(
            'border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'view' => 'framed',
                ],
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
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render icon widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('wrapper', 'class', 'elementor-icon-wrapper');

        $this->addRenderAttribute('icon-wrapper', 'class', 'elementor-icon');

        if (!empty($settings['hover_animation'])) {
            $this->addRenderAttribute('icon-wrapper', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        $icon_tag = 'div';

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('icon-wrapper', 'href', $settings['link']['url']);
            $icon_tag = 'a';

            if (!empty($settings['link']['is_external'])) {
                $this->addRenderAttribute('icon-wrapper', 'target', '_blank');
            }

            if ($settings['link']['nofollow']) {
                $this->addRenderAttribute('icon-wrapper', 'rel', 'nofollow');
            }
        }

        if ($settings['icon_source'] == 'awesome' && !empty($settings['icon'])) {
            $this->addRenderAttribute('icon', 'class', $settings['icon']);
            $this->addRenderAttribute('icon', 'aria-hidden', 'true');
        }
        if ($settings['icon_source'] == 'vecicon' && !empty($settings['vecicon'])) {
            $this->addRenderAttribute('icon', 'class', $settings['vecicon']);
            $this->addRenderAttribute('icon', 'aria-hidden', 'true');
        }

        ?>
        <div <?= $this->getRenderAttributeString('wrapper') ?>>
            <<?= $icon_tag . ' ' . $this->getRenderAttributeString('icon-wrapper') ?>>
                <i <?= $this->getRenderAttributeString('icon') ?>></i>
            </<?= $icon_tag ?>>
        </div>
        <?php
    }

    /**
     * Render icon widget output in the editor.
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
        var link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
            iconTag = link ? 'a' : 'div';
        #>
        <div class="elementor-icon-wrapper">
            <{{{ iconTag }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}" {{{ link }}}>
                <# if(settings.icon_source == 'awesome'){ #>
                <i class="{{ settings.icon }}" aria-hidden="true"></i>
                <# } #>
                <# if(settings.icon_source == 'vecicon'){ #>
                <i class="{{ settings.vecicon }}" aria-hidden="true"></i>
                <# } #>
            </{{{ iconTag }}}>
        </div>
        <?php
    }
}
