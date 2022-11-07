<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetProductSale extends WidgetProductBase 
{ 
	use CarouselTrait;
	public function getName() {
		return 'product-sale';
	}

	public function getTitle() {
		return __('Product Sale');
	}

	public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

	public function getKeywords()
    {
        return ['product', 'carousel', 'sale', 'special'];
    }

	protected function _registerControls() { 
		 
		// Resource
		$this->startControlsSection(
			'products_section',
			[
				'label' => __( 'Resource' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'product_type',
				[
					'label' => __( 'Resource' ),
					'type' => ControlsManager::SELECT,
					'description' => __( 'Select resource' ),
					'options' => [
						'all_products' => __( 'All sale Products' ),
						'cate_products' => __( 'Sale products from category' ),
						'select_products' => __( 'Select Products' ), 
					] ,
					'default' => 'all_products',
				]
			);
			$this->addControl(
				'category_id',
				[
					'label' => __('Category'),
					'label_block' => true,
					'type' => ControlsManager::SELECT2,
					'options' => $this->getCategoryOptions(),
					'default' => 2,
					'condition' => [
						'product_type' => 'cate_products',
					],
				]
			);
			$this->addControl(
            'products',
	            [
	                'type' => ControlsManager::REPEATER,
	                'item_actions' => [
	                    'add' => !is_admin() ? true : [
	                        'product' => $this->getAjaxProductsListUrl(),
	                        'placeholder' => __('Add Product'),
	                    ],
	                    'duplicate' => false,
	                ],
	                'fields' => [
	                    [
	                        'name' => 'id',
	                        'type' => ControlsManager::HIDDEN,
	                        'default' => '',
	                    ],
	                ],
	                'title_field' =>
	                    '<# var prodImg = elementor.getProductImage( id ), prodName = elementor.getProductName( id ); #>' .
	                    '<# if ( prodImg ) { #><img src="{{ prodImg }}" class="ce-repeater-thumb"><# } #>' .
	                    '<# if ( prodName ) { #><span title="{{ prodName }}">{{{ prodName }}}</span><# } #>',
	                'condition' => [
	                    'product_type' => 'select_products',
	                ],
	            ]
	        );
			$this->addControl(
				'limit',
				[
					'label' 		=> __('Limit'),
					'type' 			=> ControlsManager::NUMBER,
					'default' 		=> 6,
					'separator' 	=> 'before',
					'condition'    	=> [
						'product_type!' => 'select_products',
					],
				]
			);

			$this->addControl(
				'order',
				[
					'label' 		=> __('Order'),
					'type' 			=> ControlsManager::SELECT,
					'default' 		=> 'DESC',
					'options' 		=> [
						'DESC' 		=> __('DESC'),
						'ASC' 		=> __('ASC'),
					],
					'condition'    	=> [
						'product_type!' => 'select_products',
					],
				]
			); 
			

			$this->addControl(
				'orderby',
				[
					'label' 		=> __('Order By'),
					'type' 			=> ControlsManager::SELECT,
					'default' 		=> 'position',
					'options' 		=> [
						'date' 			=> __('Date'),
						'id' 			=> __('ID'),
						'title' 		=> __('Title'), 
						'rand' 			=> __('Random'),  
						'position'		=> __('Popularity'),
					],
					'condition'    	=> [
						'product_type!' => 'select_products',
					],
				]
			); 
		
		$this->endControlsSection();
		
		$this->startControlsSection(
			'layout_section',
			[
				'label' => __( 'Layout' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> __('Enable Slider'),
					'type' 			=> ControlsManager::HIDDEN,
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
			
			$this->addControl(
				'product_display',
				[
					'label' => __( 'Product display' ),
					'type' => ControlsManager::SELECT,
					'options' 		=> [
						'design1' 			=> __('Grid'),
						'design2' 			=> __('List'),
					],
					'default' => 'design1',
					'toggle' => true,
				]
			);
			
			$this->addControl(
				'show_countdown',
				[
					'label' => __( 'Show countdown' ),
					'type' => ControlsManager::SWITCHER,
					'label_on' => __( 'Show' ),
					'label_off' => __( 'Hide' ),
					'prefix_class' => 'show-countdown-',
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			$designs_countdown = array('1' => 'Design 1','2' => 'Design 2');
			$this->addControl(
				'design_countdown',
				[
					'label' => __( 'design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs_countdown, 
					'prefix_class' => 'design-countdown-',
					'frontend_available' => true,
					'default' => '1',
					'condition' => [
						'show_countdown' => 'yes',
					],
				]
			);
			$this->addControl(
				'title',
				[
					'label'   		=> __('Text before countdown'),
					'type'    		=> ControlsManager::TEXT,
					'label_block' 	=> true,
					'condition' => [
						'show_countdown' => 'yes',
					],
				]
			);

		$this->endControlsSection();
		$this->registerCarouselSection([
            'default_slides_desktop' => 4,
            'default_slides_tablet' => 3,
            'default_slides_mobile' => 2,
        ]);

		$this->registerNavigationStyleSection();
	}

	protected function render() {
		if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }
        if (empty($this->context->currency->id)) {
            return;
        }
		$settings = $this->getSettingsForDisplay(); 

		$context = \Context::getContext();

		$products = $this->getSaleProducts(
			$settings['product_type'],
            $settings['orderby'],
            $settings['order'],
            $settings['limit'],
            $settings['category_id'],
            $settings['products']
		);

		$settings['widget_name'] = 'product-sale-widget';
		
		if ( ! $products ) {
			echo '<p>There is no sale product.</p>'; return false;
		}

		$tpl = _VEC_TEMPLATES_ . 'front/widgets/product-sale/'. $settings['product_display'] .'.tpl';
		$this->context->smarty->assign('title', $settings['title']);
		// Store product temporary if exists
		isset($this->context->smarty->tpl_vars['product']) && $tmp = $this->context->smarty->tpl_vars['product'];

		foreach ($products as &$product) {
			$this->context->smarty->assign('product', $product);
			$slides[] = $this->context->smarty->fetch($tpl);
		}
		// Restore product if exists
		isset($tmp) && $this->context->smarty->tpl_vars['product'] = $tmp;
		
		$this->context->smarty->assign('title', $settings['title']);

		$this->renderCarousel($settings, $slides);
		
	}
}