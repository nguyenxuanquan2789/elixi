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
 * Shopping Cart widget
 *
 * @since 2.5.0
 */
class WidgetShoppingCart extends WidgetBase{

	protected $context;

	public function getName() {
		return 'shopping-cart';
	}
	public function getTitle() {
		return __('Shopping Cart' );
	}

	public function getIcon() {
		return 'eicon-cart';
	}

	public function getCategories() {
		return [ 'theme-elements' ];
	}

	protected function _registerControls() {
		$this->startControlsSection(
			'content_section',
			[
				'label' => __('Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'button_layout',
				[
					'label' => __('Button Layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon',
					'options' => [
						'icon' => __('Icon'),
						'icon_text' => __('Icon & Text'),
					],
					'prefix_class' => 'button-layout-',
				]
			);
			$icon_options = [
				'vecicon-shopping_bag1',
				'vecicon-shopping_bag2',
				'vecicon-shopping_bag3',
				'vecicon-shopping_bag4',
				'vecicon-shopping_basket1',
				'vecicon-shopping_basket2',
				'vecicon-shopping_cart1',
				'vecicon-shopping_cart2',
				'vecicon-shopping_cart3',
				'vecicon-shopping_cart4',
			];
			$this->addControl(
				'cart_icon',
				[
					'label' => __('Cart icon'),
					'type' => ControlsManager::ICON,
					'default' => 'vecicon-shopping_bag1',
					'label_block' => true,
					'icon_type' => 'vecicon',
					'include' => $icon_options,
					'condition' => array(
	                    'button_layout!' => 'text',
	                ),
				]
			);
		$this->endControlsSection();
		// Start for styles
		$this->startControlsSection(
			'style_section',
			[
				'label' => __('Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
            	'icon_size',
	            [
	                'label' => __('Icon size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 21,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .blockcart > a > .shopping-cart-icon' => 'font-size: {{SIZE}}{{UNIT}}',
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
					'selector' 		=> '{{WRAPPER}} .blockcart > a .cart-products-total',
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
	                    '{{WRAPPER}} .blockcart > a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .blockcart > a' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .blockcart > a:hover' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .blockcart > a:hover' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .blockcart > a:hover' => 'border-color: {{VALUE}};',
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
	                'selector' => '{{WRAPPER}} .blockcart > a',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => __('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .blockcart > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	                    '{{WRAPPER}} .blockcart > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .blockcart > a',
	            )
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
					'label' => __('Count Position Top'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .blockcart .cart-products-count' => 'top: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_right',
				[
					'label' => __('Count Position Left'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .blockcart .cart-products-count' => 'left: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_size',
				[
					'label' => __('Count Size'),
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
						'{{WRAPPER}} .blockcart .cart-products-count' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_font_size',
				[
					'label' => __('Count Font Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .blockcart .cart-products-count' => 'font-size: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_text_color',
				[
					'label' => __('Count Text Color'),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .blockcart .cart-products-count' => 'color: {{VALUE}};',
					],
					'separator' => 'none'
				]
			);

			$this->addControl(
				'count_background_color',
				[
					'label' => __('Count Background Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .blockcart .cart-products-count' => 'background-color: {{VALUE}};',
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

		if( \Module::isEnabled('vecshoppingcart') ) {
			$settings = $this->getSettings();
			$icon = $settings['cart_icon'];
			$module = \Module::getInstanceByName( 'vecshoppingcart' );
			echo $module->renderWidget( null, [ 'icon' => $icon ] );
		}
	} 
	
}