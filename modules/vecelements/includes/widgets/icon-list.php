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
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class WidgetIconList extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve icon list widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'icon-list';
    }

    /**
     * Get widget title.
     *
     * Retrieve icon list widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Icon List');
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon list widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-bullet-list';
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
        return ['icon list', 'icon', 'list'];
    }

    /**
     * Register icon list widget controls.
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
                'label' => __('Icon List'),
            ]
        );

        $this->addControl(
            'view',
            [
                'label' => __('Layout'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'traditional',
                'options' => [
                    'traditional' => [
                        'title' => __('Default'),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => __('Inline'),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'render_type' => 'template',
                'classes' => 'elementor-control-start-end',
                'label_block' => false,
                'style_transfer' => true,
                'prefix_class' => 'elementor-icon-list--layout-',
            ]
        );

        $repeater = new Repeater();

        $repeater->addControl(
            'text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'placeholder' => __('List Item'),
                'default' => __('List Item'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->addControl(
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
        $repeater->addControl(
            'vecicon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'default' => 'vecicon-heart1',
                'type_icon' => 'vecicon',
                'label_block' => true,
                'condition' => [
                    'icon_source' => 'vecicon',
                ],
            ]
        );

        $repeater->addControl(
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

        $repeater->addControl(
            'link',
            [
                'label' => __('Link'),
                'type' => ControlsManager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
                'placeholder' => __('https://your-link.com'),
            ]
        );

        $this->addControl(
            'icon_list',
            [
                'label' => '',
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                    [
                        'text' => __('List Item #1'),
                        'icon' => 'fa fa-check',
                    ],
                    [
                        'text' => __('List Item #2'),
                        'icon' => 'fa fa-times',
                    ],
                    [
                        'text' => __('List Item #3'),
                        'icon' => 'fa fa-dot-circle-o',
                    ],
                ],
                'title_field' => '<i class="{{ icon }}" aria-hidden="true"></i> {{{ text }}}',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_list',
            [
                'label' => __('List'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'space_between',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body.lang-rtl {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
            ]
        );

        $text_columns = range(1, 10);
        $text_columns = array_combine($text_columns, $text_columns);
        $text_columns[''] = __('Default');

        $this->addResponsiveControl(
            'columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items' => 'columns: {{VALUE}};',
                ],
                'condition' => [
                    'view!' => 'inline',
                ],
            ]
        );

        $this->addResponsiveControl(
            'column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', '%', 'em', 'vw'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                    '%' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'vw' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    'em' => [
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'inline',
                ],
            ]
        );

        $this->addControl(
            'divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'content: ""',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'divider_style',
            [
                'label' => __('Style'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'solid' => __('Solid'),
                    'double' => __('Double'),
                    'dotted' => __('Dotted'),
                    'dashed' => __('Dashed'),
                ],
                'default' => 'solid',
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'divider_weight',
            [
                'label' => __('Weight'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'condition' => [
                    'divider' => 'yes',
                    'view!' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_height',
            [
                'label' => __('Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'divider' => 'yes',
                    'view' => 'inline',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'divider_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ddd',
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'condition' => [
                    'divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_icon_style',
            [
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'icon_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addControl(
            'icon_color_hover',
            [
                'label' => __('Hover'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'icon_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_text_style',
            [
                'label' => __('Text'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
            ]
        );

        $this->addControl(
            'text_color_hover',
            [
                'label' => __('Hover'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'text_indent',
            [
                'label' => __('Text Indent'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render icon list widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('icon_list', 'class', 'elementor-icon-list-items');
        $this->addRenderAttribute('list_item', 'class', 'elementor-icon-list-item');

        if ('inline' === $settings['view']) {
            $this->addRenderAttribute('icon_list', 'class', 'elementor-inline-items');
            $this->addRenderAttribute('list_item', 'class', 'elementor-inline-item');
        }
        ?>
        <ul <?= $this->getRenderAttributeString('icon_list') ?>>
            <?php
            foreach ($settings['icon_list'] as $index => $item) :
                $repeater_setting_key = $this->getRepeaterSettingKey('text', 'icon_list', $index);

                $this->addRenderAttribute($repeater_setting_key, 'class', 'elementor-icon-list-text');

                $this->addInlineEditingAttributes($repeater_setting_key);
                ?>
                <li class="elementor-icon-list-item" >
                    <?php
                    if (!empty($item['link']['url'])) {
                        $link_key = 'link_' . $index;

                        $this->addRenderAttribute($link_key, 'href', $item['link']['url']);

                        if ($item['link']['is_external']) {
                            $this->addRenderAttribute($link_key, 'target', '_blank');
                        }

                        if ($item['link']['nofollow']) {
                            $this->addRenderAttribute($link_key, 'rel', 'nofollow');
                        }

                        echo '<a ' . $this->getRenderAttributeString($link_key) . '>';
                    }
                    ?>
                    <?php if (!empty($item['icon'])) : ?>
                        <span class="elementor-icon-list-icon">
                            <?php if($item['icon_source'] == 'awesome') : ?>
                                <i class="<?= esc_attr($item['icon']) ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?php if($item['icon_source'] == 'vecicon') : ?>
                                <i class="<?= esc_attr($item['vecicon']) ?>" aria-hidden="true"></i>
                            <?php endif; ?>
                        </span>
                    <?php endif ?>
                    <span <?= $this->getRenderAttributeString($repeater_setting_key) ?>><?= $item['text'] ?></span>
                    <?php if (!empty($item['link']['url'])) : ?>
                        </a>
                    <?php endif ?>
                </li>
                <?php
            endforeach ?>
        </ul>
        <?php
    }

    /**
     * Render icon list widget output in the editor.
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
        view.addRenderAttribute( 'icon_list', 'class', 'elementor-icon-list-items' );
        view.addRenderAttribute( 'list_item', 'class', 'elementor-icon-list-item' );

        if ( 'inline' == settings.view ) {
            view.addRenderAttribute( 'icon_list', 'class', 'elementor-inline-items' );
            view.addRenderAttribute( 'list_item', 'class', 'elementor-inline-item' );
        }
        #>
        <# if ( settings.icon_list ) { #>
            <ul {{{ view.getRenderAttributeString( 'icon_list' ) }}}>
            <# _.each( settings.icon_list, function( item, index ) {

                var iconTextKey = view.getRepeaterSettingKey( 'text', 'icon_list', index );

                view.addRenderAttribute( iconTextKey, 'class', 'elementor-icon-list-text' );

                view.addInlineEditingAttributes( iconTextKey ); #>

                <li {{{ view.getRenderAttributeString( 'list_item' ) }}}>
                    <# if ( item.link && item.link.url ) { #>
                        <a href="{{ item.link.url }}">
                    <# } #>
                    <# if ( item.icon_source === 'awesome' && item.icon ) { #>
                    <span class="elementor-icon-list-icon">
                        <i class="{{ item.icon }}" aria-hidden="true"></i>
                    </span>
                    <# } #>
                    <# if ( item.icon_source === 'vecicon' && item.vecicon ) { #>
                    <span class="elementor-icon-list-icon">
                        <i class="{{ item.vecicon }}" aria-hidden="true"></i>
                    </span>
                    <# } #>
                    <span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.text }}}</span>
                    <# if ( item.link && item.link.url ) { #>
                        </a>
                    <# } #>
                </li>
            <# } ); #>
            </ul>
        <# } #>
        <?php
    }
}
