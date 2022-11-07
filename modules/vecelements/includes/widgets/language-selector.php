<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

/**
 * Language Selector widget
 *
 * @since 2.5.0
 */
class WidgetLanguageSelector extends WidgetBase
{
    use NavTrait;

    /**
     * Get widget name.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'language-selector';
    }

    /**
     * Get widget title.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Language Selector');
    }

    /**
     * Get widget icon.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-global-settings';
    }

    /**
     * Get widget categories.
     *
     * @since 2.5.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['theme-elements'];
    }

    /**
     * Get widget keywords.
     *
     * @since 2.5.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['language', 'selector', 'chooser'];
    }

    /**
     * Register language selector widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.5.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_layout',
            [
                'label' => __('Language Selector'),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'default' => 'dropdown',
                'options' => [
                    'classic' => __('Classic'),
                    'dropdown' => __('Dropdown'),
                ],
                'separator' => 'after',
            ]
        );

        $this->addControl(
            'content',
            [
                'label' => __('Content'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'default' => ['symbol', 'name'],
                'options' => [
                    'flag' => __('Country Flag'),
                    'code' => __('ISO Code'),
                    'name' => __('Language'),
                ],
                'multiple' => true,
            ]
        );


        $this->addControl(
            'align_items',
            [
                'label' => __('Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
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
                    ]
                ],
                'prefix_class' => 'align-',
            ]
        );
        $this->addResponsiveControl(
            'dropdown_width',
            [
                'label' => __( 'Dropdown width' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'default' => [
                    'size' => 130,
                    'unit' => 'px',
                ], 
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => 'dropdown'
                ]
            ]
        );
        $this->addControl(
            'dropdown_position',
            [
                'label' => __( 'Dropdown Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Left'),
                    'right' => __( 'Right'),
                ],
                'prefix_class' => 'language-dropdown-',
            ]
        );
        $this->addControl(
            'align_submenu',
            [
                'label' => __('Dropdown Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->endControlsSection();

        // Start style selector

        $this->startControlsSection(
            'section_style_nav',
            [
                'label' => $this->getTitle(),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => isset($args['condition']) ? $args['condition'] : [],
            ]
        );
        $this->addResponsiveControl(
            'flag_size',
            [
                'label' => __( 'Flag size' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-toggle img,{{WRAPPER}} .language-classic img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->addResponsiveControl(
            'flag_space',
            [
                'label' => __( 'Flag space' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-toggle img,{{WRAPPER}} .language-classic img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' 			=> 'text_typo',
                'selector' 		=> '{{WRAPPER}} .language-widget .vec-dropdown-toggle, {{WRAPPER}} .language-widget > a',
            ]
        );

        $this->startControlsTabs('tabs_menu_item_style');

        $this->startControlsTab(
            'tab_menu_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'color_menu_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->addControl(
            'backgroundcolor_menu_item',
            [
                'label' => __('Background color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_menu_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $this->addControl(
            'color_menu_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a.active' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->addControl(
            'background_menu_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle.active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a.active' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .language-widget > a:hover' => 'background-color: {{VALUE}}',

                ],
            ]
        );
        $this->addControl(
            'menu_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle.active, ' .
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle:hover, ' .
                    '{{WRAPPER}} .language-widget > a.active, ' .
                    '{{WRAPPER}} .language-widget > a:hover, ' .
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle:hover' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_dropdown',
                'label' => __('Border'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .language-widget .vec-dropdown-toggle ,{{WRAPPER}} .language-widget > a', 
            )
        );
        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle ,{{WRAPPER}} .language-widget > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->addResponsiveControl(
            'padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .vec-dropdown-toggle, {{WRAPPER}} .language-widget > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->addResponsiveControl(
            'space',
            [
                'label' => __( 'Item space' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget > a' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'skin' => 'classic'
                ]
            ]
        );
        $this->endControlsSection();

        // Start style dropdown

        $this->startControlsSection(
            'section_style_dropdown',
            [
                'label' => __('Dropdown'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'dropdown',
                ]
            ]
        );
        $this->addResponsiveControl(
            'flag_size_dropdown',
            [
                'label' => __( 'Flag size' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-menu img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->addResponsiveControl(
            'flag_space_dropdown',
            [
                'label' => __( 'Flag space' ),
                'type' => ControlsManager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .language-widget .dropdown-menu img' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'dropdown_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'exclude' => ['line_height'],
                'selector' => '{{WRAPPER}} .dropdown-menu  a',
            ]
        );

        $this->startControlsTabs('tabs_dropdown_item_style');

        $this->startControlsTab(
            'tab_dropdown_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'color_dropdown_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'background_color_dropdown_item',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_dropdown_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $this->addControl(
            'color_dropdown_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a.active, ' .
                    '{{WRAPPER}} .dropdown-menu a:hover ' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'background_color_dropdown_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a.active, ' .
                    '{{WRAPPER}} .dropdown-menu a:hover ' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
        $this->addControl(
            'dropdown_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .dropdown-menu a.active, ' .
                    '{{WRAPPER}} .dropdown-menu a:hover' => 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .dropdown-menu a',
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'dropdown_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'dropdown_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .dropdown-menu',
            ]
        );
        $this->addResponsiveControl(
            'padding_dropdown',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->addResponsiveControl(
            'margin_dropdown',
            [
                'label' => __('Margin'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->endControlsSection();
    }

    /**
     * Render language selector widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 2.5.0
     * @access protected
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    protected function render()
    {
        $params = array();
        $settings = $this->getSettings();
        $params['settings'] = $settings;
        $languages = \Language::getLanguages(true, $this->context->shop->id);

        if (count($languages) <= 1 || !$settings['content']) {
            return;
        }
        $this->lang_flag = in_array('flag', $settings['content']);
        $this->lang_code = in_array('code', $settings['content']);
        $this->lang_name = in_array('name', $settings['content']);

        $id_lang = $this->context->language->id;
        $current_language = [
            'id_lang' => $id_lang,
            'name' => $this->context->language->name,
            'iso_code' => $this->context->language->iso_code,
            'name_simple' => preg_replace( '/\s\(.*\)$/', '', $this->context->language->name )
        ];
        foreach ($languages as &$lang) {
            $lang['current'] = $id_lang == $lang['id_lang'];
            $lang['url'] = $this->context->link->getLanguageLink($lang['id_lang']);
            $lang['name_simple'] = preg_replace( '/\s\(.*\)$/', '', $lang['name'] );
        }
        $params['languages'] =  $languages;
        $params['current_language'] =  $current_language;
        $params['use_flag'] = $this->lang_flag; 
        $params['use_code'] = $this->lang_code; 
        $params['use_name'] = $this->lang_name; 

        echo $this->fetch( _VEC_TEMPLATES_ . 'front/widgets/language-selector.tpl', $params );
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }

    protected function fetch( $templatePath, $params ) {
		$context = \Context::getContext();
		
		$smarty = $context->smarty;
		
        if ( is_object( $context->smarty ) ) {
            $smarty = $context->smarty->createData( $context->smarty );
        }
		
		$smarty->assign( $params );
		
        $template = $context->smarty->createTemplate( $templatePath, null, null, $smarty );

        return $template->fetch();
	}
}
