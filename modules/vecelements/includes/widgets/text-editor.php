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
 * Elementor text editor widget.
 *
 * Elementor widget that displays a WYSIWYG text editor, just like the PrestaShop
 * editor.
 *
 * @since 1.0.0
 */
class WidgetTextEditor extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve text editor widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'text-editor';
    }

    /**
     * Get widget title.
     *
     * Retrieve text editor widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Text Editor');
    }

    /**
     * Get widget icon.
     *
     * Retrieve text editor widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-text';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the text editor widget belongs to.
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
        return ['text', 'editor'];
    }

    /**
     * Register text editor widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_editor',
            [
                'label' => __('Text Editor'),
            ]
        );

        $this->addControl(
            'editor',
            [
                'label' => '',
                'type' => ControlsManager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
            ]
        );

        $this->addControl(
            'drop_cap',
            [
                'label' => __('Drop Cap'),
                'type' => ControlsManager::SWITCHER,
                'label_off' => __('Off'),
                'label_on' => __('On'),
                'prefix_class' => 'elementor-drop-cap-',
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style',
            [
                'label' => __('Text Editor'),
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-text-editor' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
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
                'name' => 'typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $text_columns = range(1, 10);
        $text_columns = array_combine($text_columns, $text_columns);
        $text_columns[''] = __('Default');

        $this->addResponsiveControl(
            'text_columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::SELECT,
                'separator' => 'before',
                'options' => $text_columns,
                'selectors' => [
                    '{{WRAPPER}} .elementor-text-editor' => 'columns: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-text-editor' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_drop_cap',
            [
                'label' => __('Drop Cap'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'drop_cap' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_view',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-drop-cap-view-',
                'condition' => [
                    'drop_cap' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_primary_color',
            [
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap, {{WRAPPER}}.elementor-drop-cap-view-default .elementor-drop-cap' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'condition' => [
                    'drop_cap' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_secondary_color',
            [
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-drop-cap-view-framed .elementor-drop-cap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-drop-cap-view-stacked .elementor-drop-cap' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'drop_cap_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 5,
                ],
                'range' => [
                    'px' => [
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-drop-cap' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'drop_cap_view!' => 'default',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_space',
            [
                'label' => __('Space'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-drop-cap' => 'margin-right: {{SIZE}}{{UNIT}};',
                    'body.lang-rtl {{WRAPPER}} .elementor-drop-cap' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['%', 'px'],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-drop-cap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'drop_cap_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .elementor-drop-cap' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'drop_cap_view' => 'framed',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'drop_cap_typography',
                'selector' => '{{WRAPPER}} .elementor-drop-cap-letter',
                'exclude' => [
                    'letter_spacing',
                ],
                'condition' => [
                    'drop_cap' => 'yes',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render text editor widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $editor_content = $this->getSettingsForDisplay('editor');

        $editor_content = $this->parseTextEditor($editor_content);

        $this->addRenderAttribute('editor', 'class', ['elementor-text-editor', 'elementor-clearfix']);

        $this->addInlineEditingAttributes('editor', 'advanced');
        ?>
        <div <?= $this->getRenderAttributeString('editor') ?>><?= $editor_content ?></div>
        <?php
    }

    /**
     * Render text editor widget as plain content.
     *
     * Override the default behavior by printing the content without rendering it.
     *
     * @since 1.0.0
     * @access public
     */
    public function renderPlainContent()
    {
        // In plain mode, render without shortcode
        echo $this->getSettings('editor');
    }

    /**
     * Render text editor widget output in the editor.
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
        view.addRenderAttribute( 'editor', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ] );

        view.addInlineEditingAttributes( 'editor', 'advanced' );
        #>
        <div {{{ view.getRenderAttributeString( 'editor' ) }}}>{{{ settings.editor }}}</div>
        <?php
    }
}
