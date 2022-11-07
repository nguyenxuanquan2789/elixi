<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetCompare extends WidgetBase
{  

	public function getName() {
		return 'compare';
	}
	public function getTitle() {
		return __( 'Compare' );
	}

	public function getIcon() {
		return 'eicon-sync';
	}

	public function getCategories() {
		return ['theme-elements'];
	}

	public function getKeywords()
    {
        return ['header', 'compare'];
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
					'label' => __( 'Button Layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon_text',
					'options' => [
						'icon' => __( 'Icon'),
						'icon_text' => __( 'Icon & Text'),
						'text' => __( 'Text'),
					],
					'prefix_class' => 'button-layout-',
				]
			);
			$icon_options = [
				'vecicon-refresh1',
				'vecicon-repeat1',
				'vecicon-repeat2',
				'vecicon-sync',
				'vecicon-sync2',
				'vecicon-shuffle1',
				'vecicon-shuffle2',
				'vecicon-shuffle3',
			];
			$this->addControl(
				'compare_icon',
				[
					'label' => __( 'Compare icon'),
					'type' => ControlsManager::ICON,
					'default' => 'vecicon-repeat1',
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
	                    '{{WRAPPER}} .btn-compare-top i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	                'condition' => [
	                    'button_layout!' => 'text' 
	                ],
	            ]
	        );
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .btn-compare-top',
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
	                    '{{WRAPPER}} .btn-compare-top' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,	                
	                'selectors' => array(
	                    '{{WRAPPER}} .btn-compare-top' => 'background-color: {{VALUE}};',
	                ),
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
	                    '{{WRAPPER}} .btn-compare-top:hover' => 'color: {{VALUE}};',
	                ),
	                'scheme' => array(
	                    'type' => SchemeColor::getType(),
	                    'value' => SchemeColor::COLOR_1,
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .btn-compare-top:hover' => 'background-color: {{VALUE}};',
	                ),
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
	                    '{{WRAPPER}} .btn-compare-top:hover' => 'border-color: {{VALUE}};',
	                ),
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
	                'selector' => '{{WRAPPER}} .btn-compare-top',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => __('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .btn-compare-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	                    '{{WRAPPER}} .btn-compare-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .btn-compare-top',
	            )
	        );
	    $this->endControlsSection();
		$this->startControlsSection(
			'count_section',
			[
				'label' => __( 'Count' ),
				'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'button_layout' => 'icon',
				],
			]
		);    
	        $this->addControl(
	            'heading_cart_count',
	            [
	                'label' => __('Count style'),
	                'type' => ControlsManager::HEADING,
	            ]
	        );
	        $this->addControl(
				'count_top',
				[
					'label' => __( 'Count Position Top'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'top: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_left',
				[
					'label' => __( 'Count Position Left'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'left: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_size',
				[
					'label' => __( 'Count Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'default' => [
						'size' => 18,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_font_size',
				[
					'label' => __( 'Count Font Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'font-size: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_text_color',
				[
					'label' => __( 'Count Text Color'),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'separator' => 'none'
				]
			);

			$this->addControl(
				'count_background_color',
				[
					'label' => __( 'Count Background Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .btn-compare-top .compare-count' => 'background-color: {{VALUE}};', 
					],
					'separator' => 'none'
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

		if( \Module::isEnabled('veccompare') ) {
			$settings = $this->getSettings();
			$icon = $settings['compare_icon'];

			echo $this->fetch( 'module:veccompare/views/templates/hook/compare-top.tpl', [ 'icon' => $icon ] );
		}
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