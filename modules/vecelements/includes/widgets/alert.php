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
 * Elementor alert widget.
 *
 * Elementor widget that displays a collapsible display of content in an toggle
 * style, allowing the user to open multiple items.
 *
 * @since 1.0.0
 */
class WidgetAlert extends WidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve alert widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'alert';
    }

    /**
     * Get widget title.
     *
     * Retrieve alert widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Alert');
    }

    /**
     * Get widget icon.
     *
     * Retrieve alert widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-alert';
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
        return ['alert', 'notice', 'message'];
    }

    /**
     * Register alert widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_alert',
            [
                'label' => __('Alert'),
            ]
        );

        $this->addControl(
            'alert_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'info',
                'options' => [
                    'info' => __('Info'),
                    'success' => __('Success'),
                    'warning' => __('Warning'),
                    'danger' => __('Danger'),
                ],
                'style_transfer' => true,
            ]
        );

        $this->addControl(
            'alert_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'placeholder' => __('Enter your title'),
                'default' => __('This is an Alert'),
                'label_block' => true,
            ]
        );

        $this->addControl(
            'alert_description',
            [
                'label' => __('Content'),
                'type' => ControlsManager::TEXTAREA,
                'placeholder' => __('Enter your description'),
                'default' => __('I am a description. Click the edit button to change this text.'),
                'separator' => 'none',
                'show_label' => false,
            ]
        );

        $this->addControl(
            'show_dismiss',
            [
                'label' => __('Dismiss Button'),
                'type' => ControlsManager::SELECT,
                'default' => 'show',
                'options' => [
                    'show' => __('Show'),
                    'hide' => __('Hide'),
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
            'section_type',
            [
                'label' => __('Alert'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'background',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'border_left-width',
            [
                'label' => __('Left Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'alert_title',
                'selector' => '{{WRAPPER}} .elementor-alert-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_description',
            [
                'label' => __('Description'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-alert-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'alert_description',
                'selector' => '{{WRAPPER}} .elementor-alert-description',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render alert widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();

        if (empty($settings['alert_title'])) {
            return;
        }

        if (!empty($settings['alert_type'])) {
            $this->addRenderAttribute('wrapper', 'class', 'elementor-alert elementor-alert-' . $settings['alert_type']);
        }

        $this->addRenderAttribute('wrapper', 'role', 'alert');
        $this->addRenderAttribute('alert_title', 'class', 'elementor-alert-title');

        $this->addInlineEditingAttributes('alert_title', 'none');
        ?>
        <div <?= $this->getRenderAttributeString('wrapper') ?>>
            <span <?= $this->getRenderAttributeString('alert_title') ?>><?= $settings['alert_title'] ?></span>
            <?php if (!empty($settings['alert_description'])) :
                $this->addRenderAttribute('alert_description', 'class', 'elementor-alert-description');

                $this->addInlineEditingAttributes('alert_description');
                ?>
                <span <?= $this->getRenderAttributeString('alert_description') ?>>
                    <?= $settings['alert_description'] ?>
                </span>
            <?php endif ?>
            <?php if ('show' === $settings['show_dismiss']) : ?>
                <button type="button" class="elementor-alert-dismiss">
                    <span aria-hidden="true">&times;</span>
                    <span class="elementor-screen-only"><?= __('Dismiss alert') ?></span>
                </button>
            <?php endif ?>
        </div>
        <?php
    }

    /**
     * Render alert widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        ?>
        <# if ( settings.alert_title ) {
            view.addRenderAttribute( {
                alertTitle: { class: 'elementor-alert-title' },
                alertDescription: { class: 'elementor-alert-description' }
            } );

            view.addInlineEditingAttributes( 'alert_title', 'none' );
            view.addInlineEditingAttributes( 'alert_description' );
            #>
            <div class="elementor-alert elementor-alert-{{ settings.alert_type }}" role="alert">
                <span {{{ view.getRenderAttributeString( 'alert_title' ) }}}>{{{ settings.alert_title }}}</span>
                <span {{{ view.getRenderAttributeString( 'alert_description' ) }}}>
                    {{{ settings.alert_description }}}
                </span>
                <# if ( 'show' === settings.show_dismiss ) { #>
                    <button type="button" class="elementor-alert-dismiss">
                        <span aria-hidden="true">&times;</span>
                        <span class="elementor-screen-only"><?= __('Dismiss alert') ?></span>
                    </button>
                <# } #>
            </div>
        <# } #>
        <?php
    }
}
