<?php

namespace VEC;

defined('_PS_VERSION_') or die;

use Context;

class WidgetMegamenu extends WidgetBase { 

	public function getName() 
	{
		return 'megamenu';
	}
	public function getTitle() 
	{
		return __( 'Main menu' );
	}

	public function getIcon() 
	{
		return 'eicon-nav-menu';
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
			'layout',
			[ 
	        	'label' => __('Menu type'),
	            'type' => ControlsManager::SELECT,
	            'default' => 'hmenu',
	            'options' => [
	            	'hmenu' => __( 'Horizontal menu' ),
	            	'vmenu' => __( 'Vertical menu' ),
					'mobilemenu' => __( 'Mobile menu' ),
	            ],
        	]
        );
		$this->addControl(
			'm_vertical',
	        array(
				'label' => __('Show vertical menu in mobile menu'),
				'type' => ControlsManager::SWITCHER,
				'default' => 'yes',
				'condition' => array(
					'layout' => 'mobilemenu'
				),
				'label_on'     => 'Yes',
				'label_off'    => 'No',
			)
		);
		$this->addControl(
			'vertical_title',
	        array(
				'label' => __('Vertical menu title'),
				'type' => ControlsManager::TEXT,
				'default' => __('Categories'),
				'condition' => array(
					'layout' => 'vmenu'
				),
			)
		);
		$this->endControlsSection();
		// Start for style
		$this->startControlsSection(
            'section_menu_icon',
            [
                'label' => __('Menu icon'),
                'tab' => ControlsManager::TAB_STYLE,
				 'condition' => [
                    'layout' => 'mobilemenu',
                ],
            ]
        );
            $this->addControl(
				'icon_size',
				[
					'label' => __('Icon Size'),
					'type' => ControlsManager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'size' => 28,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} #menu-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->addControl(
				'icon_color',
				[
					'label' => __( 'Icon Color' ),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #menu-icon i' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
				]
			);
			$this->addControl(
				'icon_hover_color',
				[
					'label' => __( 'Icon Hover Color' ),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} #menu-icon i:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
				]
			);
        $this->endControlsSection();
		$this->startControlsSection(
			'style_section',
			[
				'label' => __( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$this->addGroupControl(
				GroupControlTypography::getType(), 
				[
					'name' => 'typography',
					'selector' => '{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a',
				]
			);


			$this->startControlsTabs( 'tabs_style' );

				$this->startControlsTab(
					'tab_normal',
					[
						'label' => __( 'Normal' ),
					]
				);

					$this->addControl(
						'text_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'background_color',
						[
							'label' => __( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

				$this->startControlsTab(
					'tab_hover',
					[
						'label' => __( 'Hover & Active' ),
					]
				);

					$this->addControl(
						'hover_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .vec-menu-vertical .menu-item:hover > a,{{WRAPPER}} .vec-menu-horizontal .menu-item.home > a, {{WRAPPER}} .vec-menu-vertical .menu-item.home > a, {{WRAPPER}} .vec-menu-horizontal .menu-item.active > a, {{WRAPPER}} .vec-menu-vertical .menu-item.active > a' => 'color: {{VALUE}};'
							],
						]
					);

					$this->addControl(
						'background_hover_color',
						[
							'label' => __( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .vec-menu-vertical .menu-item:hover > a,{{WRAPPER}} .vec-menu-horizontal .menu-item.home > a, {{WRAPPER}} .vec-menu-vertical .menu-item.home > a, {{WRAPPER}} .vec-menu-horizontal .menu-item.active > a, {{WRAPPER}} .vec-menu-vertical .menu-item.active > a' => 'background-color: {{VALUE}};',
							],
						] 
					);

					$this->addControl(
						'hover_border_color',
						[
							'label' => __( 'Border Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-horizontal .menu-item:hover > a, {{WRAPPER}} .vec-menu-vertical .menu-item:hover > a,{{WRAPPER}} .vec-menu-horizontal .menu-item.home > a, {{WRAPPER}} .vec-menu-vertical .menu-item.home > a, {{WRAPPER}} .vec-menu-horizontal .menu-item.active > a, {{WRAPPER}} .vec-menu-vertical .menu-item.active > a' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

			$this->endControlsTabs();

			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a',
					'separator' => 'before',
				]
			);

			$this->addControl(
				'border_radius',
				[
					'label' => __( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],					
				]
			);

			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'box_shadow',
					'selector' => '{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a',
				]
			);

			$this->addControl(
				'text_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-horizontal .menu-item > a, {{WRAPPER}} .vec-menu-vertical .menu-item > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->addControl(
				'margin',
				[
					'label' => __( 'Margin' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-horizontal .menu-item > a , {{WRAPPER}} .vec-menu-vertical .menu-item > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		
		$this->endControlsSection();
		
		$this->startControlsSection(
			'section_vertical_title',
			[
				'label' => __( 'Vertical Title' ),
				'type' => ControlsManager::SECTION,
				'tab' => ControlsManager::TAB_STYLE,
				'condition'    => [
					'layout' => [ 'vmenu' ],
				],
			]
		);
		
			$this->addControl(
				'title_icon_size',
				[
					'label' => __( 'Icon Size' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .title_vertical i' => 'font-size: {{SIZE}}{{UNIT}}', 
					],
					'separator' => 'before',
				]
			);		
		
			$this->addControl(
				'title_icon_size_margin',
				[
					'label' => __( 'Icon Margin Right' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .title_vertical i' => 'margin-right: {{SIZE}}{{UNIT}}',  
					]
				]
			);
		
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .vec-menu-vertical .title_vertical',
				]
			);

			$this->startControlsTabs( 'title_tabs_style' );

				$this->startControlsTab(
					'title_tab_normal',
					[
						'label' => __( 'Normal' ),
					]
				);

					$this->addControl(
						'title_text_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .vec-menu-vertical .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'title_background_color',
						[
							'label' => __( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-vertical .title_vertical' => 'background-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

				$this->startControlsTab(
					'title_tab_hover',
					[
						'label' => __( 'Hover & Active' ),
					]
				);

					$this->addControl(
						'title_hover_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-vertical:hover .title_vertical' => 'fill: {{VALUE}}; color: {{VALUE}};'
							],
						]
					);

					$this->addControl(
						'title_background_hover_color',
						[
							'label' => __( 'Background Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-vertical:hover .title_vertical' => 'background-color: {{VALUE}};', 
							],
						]
					);

					$this->addControl(
						'title_hover_border_color',
						[
							'label' => __( 'Border Color' ),
							'type' => ControlsManager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .vec-menu-vertical:hover .title_vertical' => 'border-color: {{VALUE}};',
							],
						]
					);

				$this->endControlsTab();

			$this->endControlsTabs();

			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'title_border',
					'selector' => '{{WRAPPER}} .vec-menu-vertical .title_vertical',
					'separator' => 'before',
				]
			);

			$this->addControl(
				'title_border_radius',
				[
					'label' => __( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .title_vertical' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'title_box_shadow',
					'selector' => '{{WRAPPER}} .vec-menu-vertical .title_vertical',
				]
			);

			$this->addControl(
				'title_text_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .title_vertical' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

		$this->endControlsSection();
		$this->startControlsSection(
			'section_vertical_content',
			[
				'label' => __( 'Vertical Content' ),
				'type' => ControlsManager::SECTION,
				'tab' => ControlsManager::TAB_STYLE,
				'condition'    => [
					'layout' => [ 'vmenu' ],
				],
			]
		);
			$this->addControl(
				'content_border_radius',
				[
					'label' => __( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .menu-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					]
				]
			);

			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'content_box_shadow',
					'selector' => '{{WRAPPER}} .vec-menu-vertical .menu-content',
				]
			);

			$this->addControl(
				'content_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .menu-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
			$this->addControl(
				'content_margin',
				[
					'label' => __( 'Margin' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-vertical .menu-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_subtitle',
			[
				'label' => __( 'Subtitle style' ),
				'type' => ControlsManager::SECTION,
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addGroupControl(
				GroupControlTypography::getType(), 
				[
					'name' => 'subtitle_typography',
					'selector' => '{{WRAPPER}} .vec-menu-horizontal .menu-item > a .menu-subtitle, {{WRAPPER}} .vec-menu-vertical .menu-item > a .menu-subtitle',
				]
			);
			$this->addControl(
				'subtitle_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-horizontal .menu-item > a .menu-subtitle, {{WRAPPER}} .vec-menu-vertical .menu-item > a .menu-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
			$this->addControl(
				'subtitle_positi',
				[
					'label' => __('Horizontal position'),
					'type' => ControlsManager::SLIDER,
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'size' => 0,
						'unit' => '%', 
					],
					'size_units' => ['%'],
					'selectors' => [
						'{{WRAPPER}} .vec-menu-horizontal .menu-item > a .menu-subtitle, {{WRAPPER}} .vec-menu-vertical .menu-item > a .menu-subtitle' => 'left: {{SIZE}}{{UNIT}}',
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
		$context = Context::getContext();
		$settings = $this->getSettings();
		if($settings['layout'] == 'hmenu'){
			if( \Module::isEnabled('vecmegamenu') ) {
				$module = \Module::getInstanceByName( 'vecmegamenu' );
				echo $module->hookDisplayMegamenu();
			}else{
				echo __('Megamenu is not active.');
			}
		}else if($settings['layout'] == 'vmenu'){
			if( \Module::isEnabled('vecvegamenu') ) {
				$params = array();
				$params['title'] = $settings['vertical_title'];
				$module = \Module::getInstanceByName( 'vecvegamenu' );
				echo $module->hookDisplayVegamenu($params);
			}else{
				echo __('Vertical menu is not active.');
			}
		}else{
			$vmenu = '';
			$vmenu = $settings['m_vertical'];
			$context->smarty->assign(
				array(
					'vmenu'      => $vmenu,
				)
			);
			$output = $context->smarty->fetch( _VEC_PATH_ . '/views/templates/front/widgets/menu-mobile.tpl' );
			echo $output;
		}
		

	} 
}