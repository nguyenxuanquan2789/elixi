<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or exit;

define('_CE_CF_DOMAIN_', 'Modules.Contactform.Shop');

/**
 * Contact Form widget
 *
 * @since 1.0.0
 */
class WidgetContactForm extends WidgetBase
{
    protected static $col_width = [
        '100' => '100%',
        '80' => '80%',
        '75' => '75%',
        '66' => '66%',
        '60' => '60%',
        '50' => '50%',
        '40' => '40%',
        '33' => '33%',
        '25' => '25%',
        '20' => '20%',
    ];

    protected $context;

    protected $translator;

    protected $locale;

    protected $locale_fo;

    protected $upload;

    protected $gdpr;

    protected $gdpr_msg;

    protected $gdpr_cfg;

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'contact-form';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Contact Form');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-form-horizontal';
    }

    /**
     * Get widget categories.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['premium'];
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
        return ['submit', 'send', 'message'];
    }

    protected function getContactOptions()
    {
        $contacts = \Contact::getContacts($this->context->language->id);
        $opts = [
            '0' => __('Select'),
        ];
        foreach ($contacts as $contact) {
            $opts[$contact['id_contact']] = $contact['name'];
        }
        return $opts;
    }

    protected function getModuleLink($module)
    {
        if (empty($this->context->employee->id)) {
            return '#';
        }
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $module;
    }

    protected function getToken()
    {
        if (empty($this->context->cookie->contactFormTokenTTL) || $this->context->cookie->contactFormTokenTTL < time()) {
            $this->context->cookie->contactFormToken = md5(uniqid());
            $this->context->cookie->contactFormTokenTTL = time() + 600;
        }
        return $this->context->cookie->contactFormToken;
    }

    protected function trans($id, array $params = [], $domain = _CE_CF_DOMAIN_, $locale = null)
    {
        try {
            return $this->translator->trans($id, $params, $domain, $locale ? $locale : $this->locale);
        } catch (\Exception $ex) {
            return $id;
        }
    }

    /**
     * Register contact form widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_form_content',
            [
                'label' => __('Form Fields'),
            ]
        );

        $this->addControl(
            'subject_id',
            [
                'label' => $this->trans('Subject Heading'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getContactOptions(),
                'default' => '0',
            ]
        );

        $this->addControl(
            'show_upload',
            [
                'label' => $this->trans('Attach File'),
                'type' => $this->upload ? ControlsManager::SWITCHER : ControlsManager::HIDDEN,
                'default' => 'yes',
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
            ]
        );

        $this->addControl(
            'input_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
                'default' => 'sm',
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'show_labels',
            [
                'label' => __('Label'),
                'type' => ControlsManager::SWITCHER,
                'default' => 'yes',
                'label_off' => __('Hide'),
                'label_on' => __('Show'),
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_subject_content',
            [
                'label' => $this->trans('Subject Heading'),
                'condition' => [
                    'subject_id' => '0',
                ],
            ]
        );

        $this->addControl(
            'subject_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Subject Heading', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'subject_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_email_content',
            [
                'label' => $this->trans('Email address'),
            ]
        );

        $this->addControl(
            'email_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Email address', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'email_placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('your@email.com', [], 'Shop.Forms.Help', $this->locale_fo),
            ]
        );

        $this->addResponsiveControl(
            'email_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_upload_content',
            [
                'label' => $this->trans('Attach File'),
                'condition' => [
                    'show_upload' => $this->upload ? 'yes' : 'hide',
                ],
            ]
        );

        $this->addControl(
            'upload_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Attach File', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'upload_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_message_content',
            [
                'label' => $this->trans('Message'),
            ]
        );

        $this->addControl(
            'message_label',
            [
                'label' => __('Label'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Message', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'message_placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('How can we help?', [], 'Shop.Forms.Help', $this->locale_fo),
            ]
        );

        $this->addResponsiveControl(
            'message_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->addControl(
            'message_rows',
            [
                'label' => __('Rows'),
                'type' => ControlsManager::NUMBER,
                'default' => '4',
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_content',
            [
                'label' => __('Button'),
            ]
        );

        $this->addControl(
            'button_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Send', [], _CE_CF_DOMAIN_, $this->locale_fo),
            ]
        );

        $this->addControl(
            'button_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
                'default' => 'sm',
            ]
        );

        $this->addResponsiveControl(
            'button_width',
            [
                'label' => __('Column Width'),
                'type' => ControlsManager::SELECT,
                'options' => self::$col_width,
                'default' => '100',
            ]
        );

        $this->addResponsiveControl(
            'button_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => __('Left'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'end' => [
                        'title' => __('Right'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'stretch' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'stretch',
                'prefix_class' => 'elementor%s-button-align-',
            ]
        );

        $this->addControl(
            'icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
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
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_additional_options',
            [
                'label' => __('Additional Options'),
                'type' => ControlsManager::SECTION,
            ]
        );

        $this->addControl(
            'configure_module',
            [
                'raw' => __('Contact Form') . '<br><br>' .
                    '<a class="elementor-button elementor-button-default" href="' .
                        esc_attr($this->getModuleLink('contactform')) . '" target="_blank">' .
                        '<i class="fa fa-external-link"></i> ' . __('Configure Module') .
                    '</a>',
                'type' => ControlsManager::RAW_HTML,
                'classes' => 'elementor-control-descriptor',
            ]
        );

        empty($this->gdpr) or $this->addControl(
            'configure_gdpr',
            [
                'raw' => __('GDPR') . '<br><br>' .
                    '<a class="elementor-button elementor-button-default" href="' .
                        esc_attr($this->gdpr_cfg) . '" target="_blank">' .
                        '<i class="fa fa-external-link"></i> ' . __('Configure Module') .
                    '</a>',
                'type' => ControlsManager::RAW_HTML,
                'classes' => 'elementor-control-descriptor',
            ]
        );

        $this->addControl(
            'custom_messages',
            [
                'label' => __('Messages'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('Default'),
                    'yes' => __('Custom'),
                ],
            ]
        );

        $this->addControl(
            'success_message',
            [
                'label' => __('Success'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('Your message has been successfully sent to our team.', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'custom_messages!' => '',
                ],
            ]
        );

        $this->addControl(
            'error_message',
            [
                'label' => __('Error'),
                'label_block' => true,
                'type' => ControlsManager::TEXT,
                'placeholder' => $this->trans('An error occurred while sending the message.', [], _CE_CF_DOMAIN_, $this->locale_fo),
                'condition' => [
                    'custom_messages!' => '',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_form',
            [
                'label' => __('Form'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'column_gap',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group' => 'padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); margin-right: calc(-{{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->addControl(
            'row_gap',
            [
                'label' => __('Rows Gap'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_label',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Label'),
                'separator' => 'before',
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'label_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addControl(
            'label_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group label' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'label_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-field-group label',
                'condition' => [
                    'show_labels!' => '',
                ],
            ]
        );

        empty($this->gdpr) or $this->addControl(
            'heading_style_checkbox',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Checkbox'),
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'checkbox_spacing',
            [
                'label' => __('Spacing'),
                'type' => $this->gdpr ? ControlsManager::SLIDER : ControlsManager::HIDDEN,
                'default' => [
                    'size' => 5,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'selectors' => !$this->gdpr ? [] : [
                    '{{WRAPPER}} input[type=checkbox]' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_field_style',
            [
                'label' => __('Field'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'field_typography',
                'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->addControl(
            'field_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
            ]
        );

        $this->addControl(
            'field_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'field_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-field-group .elementor-field:not([type=file])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .elementor-button',
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
                    '{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-color: {{VALUE}};',
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
            'button_hover_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_background_hover_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'button_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'button_hover_animation',
            [
                'label' => __('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_messages_style',
            [
                'label' => __('Messages'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'messages_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-message',
            ]
        );

        $this->addControl(
            'heading_style_success',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Success'),
            ]
        );

        $this->addControl(
            'success_message_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->addControl(
            'heading_style_error',
            [
                'type' => ControlsManager::HEADING,
                'label' => __('Error'),
            ]
        );

        $this->addControl(
            'error_message_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render contact form widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    protected function render()
    {
        $settings = $this->getSettingsForDisplay();
        $id = $this->getId();
        $token = $this->getToken();
        $show_labels = (bool) $settings['show_labels'];
        $input_classes = 'elementor-field elementor-size-' . esc_attr($settings['input_size']);

        $this->addRenderAttribute('form', [
            'action' => $this->context->link->getModuleLink('vecelements', 'ajax', [], null, null, null, true),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'data-success' => $settings['custom_messages'] ? $settings['success_message'] : '',
            'data-error' => $settings['custom_messages'] ? $settings['error_message'] : '',
        ]);
        $this->addRenderAttribute('email', [
            'id' => 'from-' . $id,
            'value' => isset($this->context->customer->email) ? $this->context->customer->email : '',
            'placeholder' => $settings['email_placeholder'] ? $settings['email_placeholder'] : ($this->trans('your@email.com', [], 'Shop.Forms.Help')
            ),
        ]);
        $this->addRenderAttribute('message', [
            'id' => 'message-' . $id,
            'placeholder' => $settings['message_placeholder'] ? $settings['message_placeholder'] : (
                $this->trans('How can we help?', [], 'Shop.Forms.Help')
            ),
            'rows' => (int) $settings['message_rows'],
        ]);
        $this->addRenderAttribute('button', 'class', 'elementor-button elementor-size-' . $settings['button_size']);

        if ($settings['button_hover_animation']) {
            $this->addRenderAttribute('button', 'class', 'elementor-animation-' . $settings['button_hover_animation']);
        }
        ?>
        <form class="elementor-contact-form" <?= $this->getRenderAttributeString('form') ?>>
            <div class="elementor-form-fields-wrapper">
                <input type="hidden" name="url">
                <?php if ($token) : ?>
                    <input type="hidden" name="token" value="<?= esc_attr($token) ?>">
                <?php endif ?>
                <?php if ($settings['subject_id']) : ?>
                    <input type="hidden" name="id_contact" value="<?= (int) $settings['subject_id'] ?>">
                <?php else : ?>
                    <div class="elementor-field-group elementor-column elementor-col-<?= (int) $settings['subject_width'] ?> elementor-field-type-select">
                        <?php if ($show_labels) : ?>
                            <label class="elementor-field-label" for="id-contact-<?= $id ?>">
                                <?= $settings['subject_label'] ? $settings['subject_label'] : $this->trans('Subject Heading') ?>
                            </label>
                        <?php endif ?>
                        <div class="elementor-select-wrapper">
                            <select name="id_contact" id="id-contact-<?= $id ?>" class="elementor-field elementor-field-textual elementor-size-<?= esc_attr($settings['input_size']) ?>">
                            <?php foreach (\Contact::getContacts($this->context->language->id) as $contact) : ?>
                                <option value="<?= (int) $contact['id_contact'] ?>"><?= $contact['name'] ?></option>
                            <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                <?php endif ?>
                <div class="elementor-field-group elementor-column elementor-col-<?= (int) $settings['email_width'] ?> elementor-field-type-email">
                    <?php if ($show_labels) : ?>
                        <label class="elementor-field-label" for="from-<?= $id ?>">
                            <?= $settings['email_label'] ? $settings['email_label'] : $this->trans('Email address') ?>
                        </label>
                    <?php endif ?>
                    <input type="email" name="from" <?= $this->getRenderAttributeString('email') ?> class="<?= $input_classes ?> elementor-field-textual" required>
                </div>
                <?php if ($this->upload && $settings['show_upload']) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-<?= (int) $settings['upload_width'] ?> elementor-field-type-file">
                        <?php if ($show_labels) : ?>
                            <label class="elementor-field-label" for="file-upload-<?= $id ?>">
                                <?= $settings['upload_label'] ? $settings['upload_label'] : $this->trans('Attach File') ?>
                            </label>
                        <?php endif ?>
                        <input type="file" name="fileUpload" id="file-upload-<?= $id ?>" class="<?= $input_classes ?>">
                    </div>
                <?php endif ?>
                <div class="elementor-field-group elementor-column elementor-col-<?= (int) $settings['message_width'] ?> elementor-field-type-textarea">
                    <?php if ($show_labels) : ?>
                        <label class="elementor-field-label" for="message-<?= $id ?>">
                            <?= $settings['message_label'] ? $settings['message_label'] : $this->trans('Message') ?>
                        </label>
                    <?php endif ?>
                    <textarea name="message" <?= $this->getRenderAttributeString('message') ?> class="<?= $input_classes ?> elementor-field-textual" required></textarea>
                </div>
                <?php if ($this->gdpr) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-gdpr">
                        <label class="elementor-field-label">
                            <input type="checkbox" name="<?= $this->gdpr ?>" value="1" required><span class="elementor-checkbox-label"><?= $this->gdpr_msg ?></span>
                        </label>
                    </div>
                <?php endif ?>
                <div class="elementor-field-group elementor-column elementor-col-<?= (int) $settings['button_width'] ?> elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" <?= $this->getRenderAttributeString('button') ?>>
                        <span class="elementor-button-inner">
                            <span class="elementor-button-text"><?= $settings['button_text'] ? $settings['button_text'] : $this->trans('Send') ?></span>
                            <?php if (!empty($settings['icon'])) : ?>
                                <span class="elementor-align-icon-<?= esc_attr($settings['icon_align']) ?>">
                                    <i class="<?= esc_attr($settings['icon']) ?>"></i>
                                </span>
                            <?php endif ?>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
    }

    /**
     * Render contact form widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _contentTemplate()
    {
        $this->locale = $this->locale_fo;
        ?>
        <#
        var contacts = <?= json_encode(\Contact::getContacts($this->context->language->id)) ?>,
            email_placeholder = settings.email_placeholder || <?= json_encode($this->trans('your@email.com', [], 'Shop.Forms.Help')) ?>,
            message_placeholder = settings.message_placeholder || <?= json_encode($this->trans('How can we help?', [], 'Shop.Forms.Help')) ?>,
            upload = <?= $this->upload ? 1 : 0 ?>;
        #>
        <form class="elementor-contact-form">
            <div class="elementor-form-fields-wrapper">
                <# if (settings.subject_id <= 0) { #>
                    <div class="elementor-field-group elementor-column elementor-col-{{ settings.subject_width }} elementor-field-type-select">
                        <# if (settings.show_labels) { #>
                            <label class="elementor-field-label">{{ settings.subject_label || <?= json_encode($this->trans('Subject Heading')) ?> }}</label>
                        <# } #>
                        <div class="elementor-select-wrapper">
                            <select name="id_contact" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                            <# _.each(contacts, function(contact) { #>
                                <option>{{ contact.name }}</option>
                            <# }) #>
                            </select>
                        </div>
                    </div>
                <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.email_width }} elementor-field-type-email">
                    <# if (settings.show_labels) { #>
                        <label class="elementor-field-label">{{ settings.email_label || <?= json_encode($this->trans('Email address')) ?> }}</label>
                    <# } #>
                    <input type="email" name="from" placeholder="{{ email_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}">
                </div>
                <# if (upload && settings.show_upload) { #>
                    <div class="elementor-field-group elementor-column elementor-col-{{ settings.upload_width }} elementor-field-type-file">
                        <# if (settings.show_labels) { #>
                            <label class="elementor-field-label">{{ settings.upload_label || <?= json_encode($this->trans('Attach File')) ?> }}</label>
                        <# } #>
                        <input type="file" name="fileUpload" class="elementor-field elementor-size-{{ settings.input_size }} ?>">
                    </div>
                <# } #>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.message_width }} elementor-field-type-textarea">
                    <# if (settings.show_labels) { #>
                        <label class="elementor-field-label">{{ settings.message_label || <?= json_encode($this->trans('Message')) ?> }}</label>
                    <# } #>
                    <textarea name="message" placeholder="{{ message_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" rows="{{ settings.message_rows }}"></textarea>
                </div>
                <?php if ($this->gdpr) : ?>
                    <div class="elementor-field-group elementor-column elementor-col-100 elementor-field-type-gdpr">
                        <label class="elementor-field-label">
                            <input type="checkbox"><span class="elementor-checkbox-label"><?= $this->gdpr_msg ?></span>
                        </label>
                    </div>
                <?php endif ?>
                <div class="elementor-field-group elementor-column elementor-col-{{ settings.button_width }} elementor-field-type-submit">
                    <button type="submit" name="submitMessage" value="Send" class="elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_hover_animation }}">
                        <span class="elementor-button-inner">
                            <span class="elementor-button-text">{{ settings.button_text || <?= json_encode($this->trans('Send')) ?> }}</span>
                            <# if (settings.icon) { #>
                                <span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
                                    <i class="{{ settings.icon }}"></i>
                                </span>
                            <# } #>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <?php
        $this->locale = null;
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->translator = $this->context->getTranslator();

        $id_lang = (int) \Tools::getValue('id_lang');
        $lang = $id_lang ? new \Language($id_lang) : null;
        $this->locale_fo = isset($lang->locale) ? $lang->locale : null;

        $this->upload = \Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD');
        $this->initGDPR($id_lang);

        parent::__construct($data, $args);
    }

    protected function initGDPR($id_lang)
    {
        empty($id_lang) && $id_lang = $this->context->language->id;

        if (\Module::isEnabled('psgdpr') && \Module::getInstanceByName('psgdpr') &&
            call_user_func('GDPRConsent::getConsentActive', $id_module = \Module::getModuleIdByName('contactform'))
        ) {
            $this->gdpr = 'psgdpr_consent_checkbox';
            $this->gdpr_msg = call_user_func('GDPRConsent::getConsentMessage', $id_module, $id_lang);
            $this->gdpr_cfg = $this->getModuleLink('psgdpr&page=dataConsent');
        } elseif (\Module::isEnabled('gdprpro') && \Configuration::get('gdpr-pro_consent_contact_enable')) {
            $this->gdpr = 'gdpr_consent_chkbox';
            $this->gdpr_msg = \Configuration::get('gdpr-pro_consent_contact_text', $id_lang);
            $this->gdpr_cfg = empty($this->context->employee) ? '#' : $this->context->link->getAdminLink('AdminGdprConfig');
        }

        // Strip <p> tags from GDPR message
        empty($this->gdpr_msg) or $this->gdpr_msg = preg_replace('~</?p\b.*?>~i', '', $this->gdpr_msg);
    }
}
