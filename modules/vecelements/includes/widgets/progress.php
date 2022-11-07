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
 * Elementor progress widget.
 *
 * Elementor widget that displays an escalating progress bar.
 *
 * @since 1.0.0
 */
class WidgetProgress extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve progress widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'progress';
    }

    /**
     * Get widget title.
     *
     * Retrieve progress widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Progress Bar');
    }

    /**
     * Get widget icon.
     *
     * Retrieve progress widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-skill-bar';
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
        return ['progress', 'bar'];
    }

    /**
     * Register progress widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_progress',
            [
                'label' => __('Progress Bar'),
            ]
        );

        $this->addControl(
            'title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title'),
                'default' => __('My Skill'),
                'label_block' => true,
            ]
        );

        $this->addControl(
            'progress_type',
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
            ]
        );

        $this->addControl(
            'percent',
            [
                'label' => __('Percentage'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 50,
                    'unit' => '%',
                ],
                'label_block' => true,
            ]
        );

        $this->addControl('display_percentage', [
            'label' => __('Display Percentage'),
            'type' => ControlsManager::SELECT,
            'default' => 'show',
            'options' => [
                'show' => __('Show'),
                'hide' => __('Hide'),
            ],
        ]);

        $this->addControl(
            'inner_text',
            [
                'label' => __('Inner Text'),
                'type' => ControlsManager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('e.g. Web Designer'),
                'default' => __('Web Designer'),
                'label_block' => true,
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
            'section_progress_style',
            [
                'label' => __('Progress Bar'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'bar_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-progress-wrapper .elementor-progress-bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'bar_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-progress-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'bar_inline_color',
            [
                'label' => __('Inner Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-progress-bar' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title',
            [
                'label' => __('Title Style'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .elementor-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render progress widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('wrapper', [
            'class' => 'elementor-progress-wrapper',
            'role' => 'progressbar',
            'aria-valuemin' => '0',
            'aria-valuemax' => '100',
            'aria-valuenow' => $settings['percent']['size'],
            'aria-valuetext' => $settings['inner_text'],
        ]);

        if (!empty($settings['progress_type'])) {
            $this->addRenderAttribute('wrapper', 'class', 'progress-' . $settings['progress_type']);
        }

        $this->addRenderAttribute('progress-bar', [
            'class' => 'elementor-progress-bar',
            'data-max' => $settings['percent']['size'],
        ]);

        $this->addRenderAttribute('inner_text', [
            'class' => 'elementor-progress-text',
        ]);

        $this->addInlineEditingAttributes('inner_text');
        ?>
        <?php if (!empty($settings['title'])) : ?>
            <span class="elementor-title"><?= $settings['title'] ?></span>
        <?php endif ?>

        <div <?= $this->getRenderAttributeString('wrapper') ?>>
            <div <?= $this->getRenderAttributeString('progress-bar') ?>>
                <span <?= $this->getRenderAttributeString('inner_text') ?>><?= $settings['inner_text'] ?></span>
                <?php if ('hide' !== $settings['display_percentage']) : ?>
                    <span class="elementor-progress-percentage"><?= $settings['percent']['size'] ?>%</span>
                <?php endif ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render progress widget output in the editor.
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
        view.addRenderAttribute( 'progressWrapper', {
            'class': [ 'elementor-progress-wrapper', 'progress-' + settings.progress_type ],
            'role': 'progressbar',
            'aria-valuemin': '0',
            'aria-valuemax': '100',
            'aria-valuenow': settings.percent.size,
            'aria-valuetext': settings.inner_text
        } );

        view.addRenderAttribute( 'inner_text', {
            'class': 'elementor-progress-text'
        } );

        view.addInlineEditingAttributes( 'inner_text' );
        #>
        <# if ( settings.title ) { #>
            <span class="elementor-title">{{{ settings.title }}}</span><#
        } #>
        <div {{{ view.getRenderAttributeString( 'progressWrapper' ) }}}>
            <div class="elementor-progress-bar" data-max="{{ settings.percent.size }}">
                <span {{{ view.getRenderAttributeString( 'inner_text' ) }}}>{{{ settings.inner_text }}}</span>
                <# if ( 'hide' !== settings.display_percentage ) { #>
                    <span class="elementor-progress-percentage">{{{ settings.percent.size }}}%</span>
                <# } #>
            </div>
        </div>
        <?php
    }
}
