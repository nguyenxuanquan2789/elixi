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

class WidgetCountdown extends WidgetBase
{
    public function getName()
    {
        return 'countdown';
    }

    public function getTitle()
    {
        return __('Countdown');
    }

    public function getIcon()
    {
        return 'eicon-countdown';
    }

    public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['countdown', 'number', 'timer', 'time', 'date'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_countdown',
            [
                'label' => __('Countdown'),
            ]
        );

        $this->addControl(
            'due_date',
            [
                'label' => __('Due Date'),
                'type' => ControlsManager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime('+1 month')),
                'description' => sprintf(__('Date set according to your timezone: %s.'), Utils::getTimezoneString()),
            ]
        );

        $this->addControl(
            'label_display',
            [
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'block' => __('Block'),
                    'inline' => __('Inline'),
                ],
                'default' => 'block',
                'prefix_class' => 'elementor-countdown--label-',
            ]
        );

        $this->addResponsiveControl(
            'inline_align',
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-wrapper' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'label_display' => 'inline',
                ],
            ]
        );

        $this->addControl(
            'show_days',
            [
                'label' => __('Days'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_hours',
            [
                'label' => __('Hours'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_minutes',
            [
                'label' => __('Minutes'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_seconds',
            [
                'label' => __('Seconds'),
                'type' => ControlsManager::SWITCHER,
                'separator' => '',
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_labels',
            [
                'label' => __('Label'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'custom_labels',
            [
                'label' => __('Custom Label'),
                'type' => ControlsManager::SWITCHER,
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'label_days',
            [
                'label' => __('Days'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Days'),
                'placeholder' => __('Days'),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_days' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'label_hours',
            [
                'label' => __('Hours'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Hours'),
                'placeholder' => __('Hours'),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_hours' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'label_minutes',
            [
                'label' => __('Minutes'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Minutes'),
                'placeholder' => __('Minutes'),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_minutes' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'label_seconds',
            [
                'label' => __('Seconds'),
                'type' => ControlsManager::TEXT,
                'separator' => '',
                'default' => __('Seconds'),
                'placeholder' => __('Seconds'),
                'condition' => [
                    'show_labels!' => '',
                    'custom_labels!' => '',
                    'show_seconds' => 'yes',
                ],
            ]
        );

        $this->addControl(
            'expire_actions',
            [
                'label' => __('Actions After Expire'),
                'type' => ControlsManager::SELECT2,
                'options' => [
                    'redirect' => __('Redirect'),
                    'hide' => __('Hide'),
                    'message' => __('Show Message'),
                ],
                'label_block' => true,
                'separator' => 'before',
                'render_type' => 'none',
                'multiple' => true,
            ]
        );

        $this->addControl(
            'message_after_expire',
            [
                'label' => __('Message'),
                'type' => ControlsManager::TEXTAREA,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'message',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'expire_redirect_url',
            [
                'label' => __('Redirect URL'),
                'type' => ControlsManager::URL,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'redirect',
                        ],
                    ],
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_box_style',
            [
                'label' => __('Boxes'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'container_width',
            [
                'label' => __('Container Width'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['%', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'box_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'box_border',
                'label' => __('Border'),
                'selector' => '{{WRAPPER}} .elementor-countdown-item',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'box_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'box_spacing',
            [
                'label' => __('Space Between'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body:not(.lang-rtl) {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body.lang-rtl {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
                    'body.lang-rtl {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );

        $this->addResponsiveControl(
            'box_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_content_style',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'heading_digits',
            [
                'label' => __('Digits'),
                'type' => ControlsManager::HEADING,
            ]
        );

        $this->addControl(
            'digits_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'digits_typography',
                'selector' => '{{WRAPPER}} .elementor-countdown-digits',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addControl(
            'heading_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .elementor-countdown-label',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_expire_message_style',
            [
                'label' => __('Message'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'expire_actions',
                            'operator' => 'contains',
                            'value' => 'message',
                        ],
                    ],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'text-align: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .elementor-countdown-expire--message',
            ]
        );

        $this->addResponsiveControl(
            'message_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    private function getStrftime($instance)
    {
        $string = '';
        if ($instance['show_days']) {
            $string .= $this->renderCountdownItem($instance, 'label_days', 'elementor-countdown-days');
        }
        if ($instance['show_hours']) {
            $string .= $this->renderCountdownItem($instance, 'label_hours', 'elementor-countdown-hours');
        }
        if ($instance['show_minutes']) {
            $string .= $this->renderCountdownItem($instance, 'label_minutes', 'elementor-countdown-minutes');
        }
        if ($instance['show_seconds']) {
            $string .= $this->renderCountdownItem($instance, 'label_seconds', 'elementor-countdown-seconds');
        }

        return $string;
    }

    private $_default_countdown_labels;

    private function _initDefaultCountdownLabels()
    {
        $this->_default_countdown_labels = [
            'label_months' => __('Months'),
            'label_weeks' => __('Weeks'),
            'label_days' => __('Days'),
            'label_hours' => __('Hours'),
            'label_minutes' => __('Minutes'),
            'label_seconds' => __('Seconds'),
        ];
    }

    public function getDefaultCountdownLabels()
    {
        if (!$this->_default_countdown_labels) {
            $this->_initDefaultCountdownLabels();
        }

        return $this->_default_countdown_labels;
    }

    private function renderCountdownItem($instance, $label, $part_class)
    {
        $string = '<div class="elementor-countdown-item">' .
            '<span class="elementor-countdown-digits ' . $part_class . '"></span>';

        if ($instance['show_labels']) {
            $default_labels = $this->getDefaultCountdownLabels();
            $label = ($instance['custom_labels']) ? $instance[$label] : $default_labels[$label];
            $string .= ' <span class="elementor-countdown-label">' . $label . '</span>';
        }

        $string .= '</div>';

        return $string;
    }

    private function getActions($settings)
    {
        if (empty($settings['expire_actions'])) {
            return false;
        }
        $actions = [];

        foreach ($settings['expire_actions'] as $action) {
            $action_to_run = ['type' => $action];
            if ('redirect' === $action) {
                if (empty($settings['expire_redirect_url']['url'])) {
                    continue;
                }
                $action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
                $action_to_run['redirect_is_external'] = $settings['expire_redirect_url']['is_external'];
            }
            $actions[] = $action_to_run;
        }

        return $actions;
    }

    protected function render()
    {
        $instance = $this->getSettingsForDisplay();
        $due_date = $instance['due_date'];
        $string = $this->getStrftime($instance);
        $actions = $this->getActions($instance);

        $due_date = strtotime($due_date);
        ?>
        <div class="elementor-countdown-wrapper"
            data-date="<?= $due_date ?>"
            data-expire-actions='<?= json_encode($actions) ?>'>
            <?= $string ?>
        </div>
        <?php
        if ($actions && is_array($actions)) {
            foreach ($actions as $action) {
                if ('message' !== $action['type']) {
                    continue;
                }
                echo '<div class="elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>';
            }
        }
    }
}
