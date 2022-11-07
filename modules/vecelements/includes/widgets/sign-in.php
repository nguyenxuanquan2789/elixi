<?php

namespace VEC;

defined('_PS_VERSION_') or die;

use Context;

class WidgetSignIn extends WidgetBase { 

	public function getName() 
    {
		return 'sign-in';
	}
	public function getTitle() 
    {
		return __('Sign in');
	}

	public function getIcon() 
    {
		return 'eicon-lock-user';
	}

	public function getCategories() 
    {
		return ['theme-elements'];
	}

	protected function _registerControls() {
		$this->startControlsSection(
			'content_section',
			[
				'label' => __( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			
			$this->addControl(
				'button_layout',
				[
					'label' => __( 'Button layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon_text',
					'options' => [
						'icon' => __( 'Icon'),
						'text' => __( 'Text'),
						'icon_text' => __( 'Icon & Text'),
					],
					'prefix_class' => 'button-layout-'
				]
			);
			$icon_options = [
				'vecicon-person1',
				'vecicon-person2',
				'vecicon-person3',
				'vecicon-person4',
				'vecicon-person5',
				'vecicon-person6',
			];
			$this->addControl(
				'account_icon',
				[
					'label' => __( 'Account icon'),
					'type' => ControlsManager::ICON,
					'default' => 'vecicon-person1',
					'label_block' => true,
					'icon_type' => 'vecicon',
					'include' => $icon_options,
					'condition' => array(
	                    'button_layout!' => 'text',
	                ),
				]	
			);
		$this->endControlsSection();
		// Start for style
		$this->startControlsSection(
			'style_section',
			[
				'label' => __( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
            	'icon_size',
	            [
	                'label' => __('Icon size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 14,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .vec-customersignin .my-account i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	                'condition' => [
	                    'button_layout!' => 'text' 
	                ]
	            ]
	        );
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .vec-customersignin .my-account',
				]
			);
	        $this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => __('Normal'),
	            )
	        );

	        $this->addControl(
	            'button_text_color',
	            array(
	                'label' => __('Text Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account' => 'color: {{VALUE}};',
	                )
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,	                
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account' => 'background-color: {{VALUE}};',
	                )
	            )
	        );

	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => __('Hover'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => __('Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account:hover' => 'color: {{VALUE}};',
	                )
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account:hover' => 'background-color: {{VALUE}};',
	                )
	            )
	        );

	        $this->addControl(
	            'button_hover_border_color',
	            array(
	                'label' => __('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'condition' => array(
	                    'border_border!' => '',
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account:hover' => 'border-color: {{VALUE}};',
	                )
	            )
	        );

	        $this->endControlsTab();

	        $this->endControlsTabs();

	        $this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border',
	                'label' => __('Border'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} .vec-customersignin .my-account'
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => __('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'none'
	            )
	        );
			$this->addControl(
	            'padding',
	            array(
	                'label' => __('Padding'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', 'em', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-customersignin .my-account' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .vec-customersignin .my-account'
	            )
	        );
		$this->endControlsSection();
		$this->startControlsSection(
			'dropdown_section',
			[
				'label' => __( 'Dropdown style' ),
				'tab' => ControlsManager::TAB_STYLE,
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
					'prefix_class' => 'vec-dropdown-',
				]
			);
			$this->addControl(
            	'dropdown_width',
	            [
	                'label' => __('Dropdown width'),
	                'type' => ControlsManager::SLIDER,
	                'range' => [
						'px' => [
							'min' => 100,
							'max' => 200, 
						],
					],
					'default' => [
						'size' => 170,
						'unit' => 'px',
					], 
	                'selectors' => [
	                    '{{WRAPPER}} .vec-customersignin .dropdown-menu' => 'min-width: {{SIZE}}{{UNIT}}',
	                ],
	            ]
	        );
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {
		if (is_admin()){
            return print '<div class="ce-remote-render"></div>';
        }

        if( \Module::isEnabled('veccustomersignin') ) {
            $settings = $this->getSettings();
            $icon = $settings['account_icon'];
            $module = \Module::getInstanceByName( 'veccustomersignin' );
            echo $module->renderWidget( null, [ 'icon' => $icon ] );
        }
		
	} 
	
}