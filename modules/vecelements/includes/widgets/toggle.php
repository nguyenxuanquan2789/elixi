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
 * Elementor toggle widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class WidgetToggle extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve toggle widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'toggle';
    }

    /**
     * Get widget title.
     *
     * Retrieve toggle widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Toggle');
    }

    /**
     * Get widget icon.
     *
     * Retrieve toggle widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-toggle';
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
        return ['tabs', 'accordion', 'toggle'];
    }

    /**
     * Register toggle widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_toggle',
            [
                'label' => __('Toggle'),
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'tab_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('Toggle Title'),
                'label_block' => true,
            ]
        );

        $repeater->addControl(
            'tab_content',
            [
                'label' => __('Content'),
                'type' => ControlsManager::WYSIWYG,
                'default' => __('Toggle Content'),
                'show_label' => false,
            ]
        );

        $this->addControl(
            'tabs',
            [
                'label' => __('Toggle Items'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'tab_title' => __('Toggle #1'),
                        'tab_content' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
                    ],
                    [
                        'tab_title' => __('Toggle #2'),
                        'tab_content' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
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
            'icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => is_rtl() ? 'fa fa-caret-left' : 'fa fa-caret-right',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'icon_active',
            [
                'label' => __('Active Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'fa fa-caret-up',
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'title_html_tag',
            [
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                ],
                'default' => 'div',
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style',
            [
                'label' => __('Toggle'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-toggle-item',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_title',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'title_background',
            [
                'label' => __('Background'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addControl(
            'tab_active_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-tab-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            ]
        );

        $this->addResponsiveControl(
            'title_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_icon',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Start'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('End'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => is_rtl() ? 'right' : 'left',
                'toggle' => false,
                'label_block' => false,
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title .elementor-toggle-icon .fa:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'icon_active_color',
            [
                'label' => __('Active Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-title.elementor-active .elementor-toggle-icon .fa:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon!' => '',
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
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-icon.elementor-toggle-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-toggle .elementor-toggle-icon.elementor-toggle-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_toggle_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'content_background_color',
            [
                'label' => __('Background'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'content_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-toggle .elementor-tab-content',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addResponsiveControl(
            'content_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-toggle .elementor-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render toggle widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $id_int = \Tools::substr($this->getIdInt(), 0, 3);

        empty($settings['icon']) or $this->addRenderAttribute('toggle_icon', [
            'class' => 'elementor-toggle-icon elementor-toggle-icon-' . $settings['icon_align'],
            'aria-hidden' => 'true',
        ]);
        ?>
        <div class="elementor-toggle" role="tablist">
        <?php foreach ($settings['tabs'] as $index => $item) :
            $tab_count = $index + 1;

            $tab_title_key = $this->getRepeaterSettingKey('tab_title', 'tabs', $index);

            $tab_content_key = $this->getRepeaterSettingKey('tab_content', 'tabs', $index);

            $this->addRenderAttribute($tab_title_key, [
                'id' => 'elementor-tab-title-' . $id_int . $tab_count,
                'class' => ['elementor-tab-title'],
                'data-tab' => $tab_count,
                'role' => 'tab',
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);

            $this->addRenderAttribute($tab_content_key, [
                'id' => 'elementor-tab-content-' . $id_int . $tab_count,
                'class' => ['elementor-tab-content', 'elementor-clearfix'],
                'data-tab' => $tab_count,
                'role' => 'tabpanel',
                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
            ]);

            $this->addInlineEditingAttributes($tab_content_key, 'advanced');
            ?>
            <div class="elementor-toggle-item">
                <<?= esc_html($settings['title_html_tag']) ?> <?= $this->getRenderAttributeString($tab_title_key) ?>>
                    <?php if ($settings['icon']) : ?>
                    <span <?= $this->getRenderAttributeString('toggle_icon') ?>>
                        <i class="elementor-toggle-icon-closed <?= esc_attr($settings['icon']) ?>"></i>
                        <i class="elementor-toggle-icon-opened <?= esc_attr($settings['icon_active']) ?>"></i>
                    </span>
                    <?php endif ?>
                    <a href=""><?= $item['tab_title'] ?></a>
                </<?= esc_html($settings['title_html_tag']) ?>>
                <div <?= $this->getRenderAttributeString($tab_content_key) ?>>
                    <?= $this->parseTextEditor($item['tab_content']) ?>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <?php
    }

    /**
     * Render toggle widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-toggle" role="tablist">
        <#
        if ( ! settings.tabs ) {
            return;
        }
        var tabindex = view.getIDInt().toString().substr( 0, 3 ),
            iconAlign = settings.icon_align;

        _.each( settings.tabs, function( item, index ) {
            var tabCount = index + 1,
                tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
                tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

            view.addRenderAttribute( tabTitleKey, {
                'id': 'elementor-tab-title-' + tabindex + tabCount,
                'class': [ 'elementor-tab-title' ],
                'data-tab': tabCount,
                'role': 'tab',
                'aria-controls': 'elementor-tab-content-' + tabindex + tabCount
            } );

            view.addRenderAttribute( tabContentKey, {
                'id': 'elementor-tab-content-' + tabindex + tabCount,
                'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
                'data-tab': tabCount,
                'role': 'tabpanel',
                'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
            } );

            view.addInlineEditingAttributes( tabContentKey, 'advanced' );
            #>
            <div class="elementor-toggle-item">
                <{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
                <# if ( settings.icon ) { #>
                    <span class="elementor-toggle-icon elementor-toggle-icon-{{ iconAlign }}" aria-hidden="true">
                        <i class="elementor-toggle-icon-closed {{ settings.icon }}"></i>
                        <i class="elementor-toggle-icon-opened {{ settings.icon_active }}"></i>
                    </span>
                <# } #>
                    <a href="">{{{ item.tab_title }}}</a>
                </{{{ settings.title_html_tag }}}>
                <div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
            </div>
            <#
        } ); #>
        </div>
        <?php
    }
}
