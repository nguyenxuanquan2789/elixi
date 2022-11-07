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
 * Elementor counter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class WidgetCounter extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve counter widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'counter';
    }

    /**
     * Get widget title.
     *
     * Retrieve counter widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Counter');
    }

    /**
     * Get widget icon.
     *
     * Retrieve counter widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-counter';
    }

    /**
     * Retrieve the list of scripts the counter widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function getScriptDepends()
    {
        return ['jquery-numerator'];
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
        return ['counter'];
    }

    /**
     * Register counter widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_counter',
            [
                'label' => __('Counter'),
            ]
        );

        $this->addControl(
            'starting_number',
            [
                'label' => __('Starting Number'),
                'type' => ControlsManager::NUMBER,
                'default' => 0,
            ]
        );

        $this->addControl(
            'ending_number',
            [
                'label' => __('Ending Number'),
                'type' => ControlsManager::NUMBER,
                'default' => 100,
            ]
        );

        $this->addControl(
            'prefix',
            [
                'label' => __('Number Prefix'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => 1,
            ]
        );

        $this->addControl(
            'suffix',
            [
                'label' => __('Number Suffix'),
                'type' => ControlsManager::TEXT,
                'default' => '',
                'placeholder' => __('Plus'),
            ]
        );

        $this->addControl(
            'duration',
            [
                'label' => __('Animation Duration'),
                'type' => ControlsManager::NUMBER,
                'default' => 2000,
                'min' => 100,
                'step' => 100,
            ]
        );

        $this->addControl(
            'thousand_separator',
            [
                'label' => __('Thousand Separator'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'thousand_separator_char',
            [
                'label' => __('Separator'),
                'type' => ControlsManager::SELECT,
                'condition' => [
                    'thousand_separator' => 'yes',
                ],
                'options' => [
                    '' => 'Default',
                    '.' => 'Dot',
                    ' ' => 'Space',
                ],
            ]
        );

        $this->addControl(
            'title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'label_block' => true,
                'default' => __('Cool Number'),
                'placeholder' => __('Cool Number'),
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
            'section_number',
            [
                'label' => __('Number'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'number_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-counter-number-wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography_number',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-counter-number-wrapper',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_title',
            [
                'label' => __('Title'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-counter-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'typography_title',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-counter-title',
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render counter widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <div class="elementor-counter">
            <div class="elementor-counter-number-wrapper">
                <span class="elementor-counter-number-prefix">{{{ settings.prefix }}}</span>
                <span class="elementor-counter-number"
                    data-duration="{{ settings.duration }}"
                    data-to-value="{{ settings.ending_number }}"
                    data-delimiter="{{ settings.thousand_separator ? settings.thousand_separator_char || ',' : '' }}">
                    {{{ settings.starting_number }}}
                </span>
                <span class="elementor-counter-number-suffix">{{{ settings.suffix }}}</span>
            </div>
        <# if ( settings.title ) { #>
            <div class="elementor-counter-title">{{{ settings.title }}}</div>
        <# } #>
        </div>
        <?php
    }

    /**
     * Render counter widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        $this->addRenderAttribute('counter', [
            'class' => 'elementor-counter-number',
            'data-duration' => $settings['duration'],
            'data-to-value' => $settings['ending_number'],
        ]);

        if (!empty($settings['thousand_separator'])) {
            $delimiter = empty($settings['thousand_separator_char']) ? ',' : $settings['thousand_separator_char'];
            $this->addRenderAttribute('counter', 'data-delimiter', $delimiter);
        }
        ?>
        <div class="elementor-counter">
            <div class="elementor-counter-number-wrapper">
                <span class="elementor-counter-number-prefix"><?= $settings['prefix'] ?></span>
                <span <?= $this->getRenderAttributeString('counter') ?>><?= $settings['starting_number'] ?></span>
                <span class="elementor-counter-number-suffix"><?= $settings['suffix'] ?></span>
            </div>
            <?php if ($settings['title']) : ?>
                <div class="elementor-counter-title"><?= $settings['title'] ?></div>
            <?php endif ?>
        </div>
        <?php
    }
}
