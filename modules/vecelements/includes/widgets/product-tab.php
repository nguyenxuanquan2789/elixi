<?php  

/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetProductTab extends WidgetProductBase
{
	use CarouselTrait;
	public function getName() {
		return 'product-tab';
	}
	public function getTitle() {
		return __('Tab Products');
	}
	public function getIcon() { 
		return 'eicon-product-tabs';
	}
	public function getKeywords()
    {
        return ['product', 'tab'];
    }
 
	protected function _registerControls() {

		$this->startControlsSection(
			'tab_products_section',
			[
				'label' => __( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
		$repeater->addControl(
            'tab_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('Accordion Title'),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $repeater->addControl(
            'listing',
            [
                'label' => __('Listing'),
                'type' => ControlsManager::SELECT,
                'default' => 'category',
                'options' => $this->getListingOptions(),
                'separator' => 'before',
            ]
        );

        $repeater->addControl(
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
                    'listing' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'category_id',
            [
                'label' => __('Category'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'options' => $this->getCategoryOptions(),
                'default' => 2,
                'condition' => [
                    'listing' => 'category',
                ],
            ]
        );

        $repeater->addControl(
            'order_by',
            [
                'label' => __('Order By'),
                'type' => ControlsManager::SELECT,
                'default' => 'position',
                'options' => [
                    'name' => __('Name'),
                    'price' => __('Price'),
                    'position' => __('Popularity'),
                    'quantity' => __('Sales Volume'),
                    'date_add' => __('Arrival'),
                    'date_upd' => __('Update'),
                ],
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'order_dir',
            [
                'label' => __('Order Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'asc',
                'options' => [
                    'asc' => __('Ascending'),
                    'desc' => __('Descending'),
                ],
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'randomize',
            [
                'label' => __('Randomize'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes'),
                'label_off' => __('No'),
                'condition' => [
                    'listing' => ['category', 'products'],
                ],
            ]
        );

        $repeater->addControl(
            'limit',
            [
                'label' => __('Product Limit'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 8,
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );
        $this->addControl(
            'tabs',
            [
                'label' => __('Tab Items'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                	[
                        'tab_title' => __('Tab #1'),
                        'listing' => 'new-products',
                        'order_by' => 'position',
                        'order_dir' => 'asc',
                        'limit' => 8,
                    ],
                    [
                        'tab_title' => __('Tab #2'),
                        'listing' => 'new-products',
                        'order_by' => 'position',
                        'order_dir' => 'asc',
                        'limit' => 8,
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
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
				'enable_ajax',
				[
					'label' 		=> __('Enable ajax tab'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> __('Enable Slider'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
			$this->addResponsiveControl(
                'grid_column',
                [
                    'label' => __('Grid column'),
                    'type' => ControlsManager::SELECT,
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ],
                    'default' => '4',
                    'condition' => [
                        'enable_slider!' => 'yes',
                    ],
                ]
            );
			$product_display = array(
				'0' => 'Default',
				'grid1' => 'Style 1',
				'grid2' => 'Style 2',
				'grid3' => 'Style 3',
				'grid4' => 'Style 4',
				'grid5' => 'Style 5',
				'grid6' => 'Style 6',
				'list' => 'Style 7'
			);
			$this->addControl(
				'product_display',
				[
					'label' => __( 'Product display' ),
					'type' => ControlsManager::SELECT,
					'options' => $product_display,
					'default' => '0',
					'description' => __('Default: use setting from theme options module.'),
				]
			);
		$this->endControlsSection();
		//Slider Setting
		

		$this->startControlsSection(
			'section_tp_style',
			[
				'label' 		=> __('Title Style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			
			$this->addControl(
				'title_align',
				[
					'label' => __( 'Title alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'left',
					'selectors' => [
						'{{WRAPPER}} .nav-tabs' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'title_size',
					'selector' => '{{WRAPPER}} .nav-tabs li a',
					'separator' => 'none',
				]
			);
			$this->addResponsiveControl(
				'title_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .nav-tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
			$this->addResponsiveControl(
				'title_space',
				[
					'label' => __( 'Title space' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .nav-tabs li a' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'title_space_bottom',
				[
					'label' => __( 'Title space bottom' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .nav-tabs' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->startControlsTabs('tabs_title_style');
				$this->startControlsTab(
					'title_normal',
					[
						'label' => __( 'Normal' ),
					]
				);
					$this->addControl(
						'title_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .nav-tabs li a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_color',
						[
							'label' => __( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .nav-tabs li a' => 'background-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
				$this->startControlsTab(
					'title_active',
					[
						'label' => __( 'Active' ),
					]
				);
					$this->addControl(
						'title_active_color',
						[
							'label' => __( 'Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .nav-tabs li a.active, {{WRAPPER}} .nav-tabs li a:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_active_color',
						[
							'label' => __( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .nav-tabs li a.active, {{WRAPPER}} .nav-tabs li a:hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'border_active_color',
						[
							'label' => __( 'Border color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .nav-tabs li a.active, {{WRAPPER}} .nav-tabs li a:hover' => 'border-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
			$this->endControlsTabs();

			$this->addGroupControl(
	            GroupControlBorder::getType(),
	            [
	                'name' => 'border',
	                'selector' => '{{WRAPPER}} .nav-tabs li a',
	            ]
	        );
			$this->addControl(
				'border_radius',
				[
					'label' => __( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .nav-tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .nav-tabs li a',
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
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		if (empty($this->context->currency->id)) {
            return;
        }
		$settings = $this->getSettingsForDisplay();
		$id_int = \Tools::substr($this->getIdInt(), 0, 4);

		$grid_type = 'grid1';
        if($settings['product_display']){
            $grid_type = $settings['product_display'];
        }else{
            $option_product = \Configuration::get('vecthemeoptionsgrid_type');
            if($option_product){
                $grid_type = 'grid'. $option_product;
            }
        }
		if(!isset($vectheme)){
            $this->context->smarty->assign('vectheme', $this->getVecthemeOptions());
        }
        
        $ajaxtab = false;
        if($settings['enable_ajax']){
        	$ajaxtab = true;
        }
        $class_tab = [];

        if($settings['enable_slider']){
        	$class_tab[]= 'elementor-block-carousel slick-block';
			$class_tab[] = 'items-desktop-'. ($settings['slides_to_show'] ? $settings['slides_to_show'] : $settings['default_slides_desktop']);
			$class_tab[] = 'items-tablet-'. ($settings['slides_to_show_tablet'] ? $settings['slides_to_show_tablet'] : $settings['default_slides_tablet']);
			$class_tab[] = 'items-mobile-'. ($settings['slides_to_show_mobile'] ? $settings['slides_to_show_mobile'] : $settings['default_slides_mobile']);
			$class_tab[] = 'slick-arrows-' . $settings['arrows_position'];
        }else{
			$class_tab[] = 'items-desktop-'. ($settings['grid_column'] ? $settings['grid_column'] : 4);
			$class_tab[] = 'items-tablet-'. ($settings['grid_column_tablet'] ? $settings['grid_column_tablet'] : 3);
			$class_tab[] = 'items-mobile-'. ($settings['grid_column_mobile'] ? $settings['grid_column_mobile'] : 2);
		}
		$tab_class = implode(' ', $class_tab);
		$this->context->smarty->assign('tab_class', $tab_class);
		?>
		<div class="product-tabs-widget" data-ajax="<?= $ajaxtab; ?>">
			<ul class="nav nav-tabs">
				<?php foreach ( $settings['tabs'] as $index => $tab ) : ?>
					<li data-id="<?= $id_int.$index ?>" class="nav-item">
						<a class="nav-link  <?php if(!$index) { ?>active<?php } ?>" href="#tab-pane-<?= $id_int.$index ?>" data-toggle="tab" role="tab"><?= $tab['tab_title'] ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-content">
				<?php foreach ( $settings['tabs'] as $index => $tab ) :
					$products = array();
					$tab_data = array();
					
					if($settings['enable_ajax'] && $index){
						$tab_data = array(
							'listing' => $tab['listing'],
							'order_by' => $tab['order_by'],
							'order_dir' => $tab['order_dir'],
							'limit' => $tab['limit'],
							'category_id' => $tab['category_id'],
							'products' => $tab['products'],
							'tab_class' => $tab_class
						);
					}else{
						if ($tab['randomize'] && ('category' === $tab['listing'] || 'products' === $tab['listing'])) {
				            $tab['order_by'] = 'rand';
				        }
				        $products = $this->getProducts(
				            $tab['listing'],
				            $tab['order_by'],
				            $tab['order_dir'],
				            $tab['limit'],
				            $tab['category_id'],
				            $tab['products']
				        );
					}					

					?>
					<div class="tab-pane <?php if($settings['enable_slider']): ?>elementor-image-carousel-wrapper elementor-slick-slider<?php endif; ?> <?php if(!$index) { ?>fade in active<?php } ?>" id="tab-pane-<?= $id_int.$index ?>" <?php if($tab_data) { ?> data-tab_content='<?= json_encode($tab_data); ?>'<?php } ?>>
						<?php 
							if($products){ 
								echo $this->context->smarty->fetch( _VEC_PATH_ . 'views/templates/front/widgets/product-tab.tpl', ['products' => $products] ); 
							}
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

}