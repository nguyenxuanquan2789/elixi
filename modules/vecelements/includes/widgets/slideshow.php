<?php  

namespace VEC;

defined('_PS_VERSION_') or die;

use Context;
use Tools;

class WidgetSlideshow extends WidgetBase { 
	public function getName() 
	{
		return 'slideshow';
	}

	public function getTitle() 
	{
		return 'Slideshow';
	}
	public function getIcon() 
	{
		return 'eicon-slider-album';
	}
	public function getCategories() 
	{
		return ['premium'];
	}
	protected function _registerControls() {
		$animations = array(
			'' => __('Default' ), 
			'bounceIn' => 'bounceIn',
			'bounceInDown' => 'bounceInDown',
			'bounceInLeft' => 'bounceInLeft',
			'bounceInRight' => 'bounceInRight',
			'bounceInUp' => 'bounceInUp',
			'fadeIn' => 'fadeIn',
			'fadeInDown' => 'fadeInDown',
			'fadeInLeft' => 'fadeInLeft',
			'fadeInRight' => 'fadeInRight',
			'fadeInUp' => 'fadeInUp',
			'zoomIn' => 'zoomIn',
			'zoomInDown' => 'zoomInDown',
			'zoomInLeft' => 'zoomInLeft',
			'zoomInRight' => 'zoomInRight',
			'zoomInUp' => 'zoomInUp',
			'rotateIn' => 'rotateIn',
			'rotateInDownLeft' => 'rotateInDownLeft',
			'rotateInDownRight' => 'rotateInDownRight',
			'rotateInUpLeft' => 'rotateInUpLeft',
			'rotateInUpRight' => 'rotateInUpRight',
			'pulse' => 'pulse',
			'flipInX' => 'flipInX',
			'jackInTheBox' => 'jackInTheBox',
			'rollIn' => 'rollIn',
		);
		//Tab Content
		$this->startControlsSection(
			'content_section',
			[
				'label' => __('Content'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addResponsiveControl( 
				'height_slideshow',
				[
					'label' => __('Height slideshow' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 500,
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-slideshow .slider-item' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$repeater = new Repeater();
			$repeater->startControlsTabs( 'slideshow_content' );
			$repeater->startControlsTab( 'content',
				[
					'label' => __( 'Content'),
				]
			);
				$repeater->addControl(
					'slideshow_image', 
					[
						'label' => __('Add Image'),
	                    'type' => ControlsManager::MEDIA,
	                    'seo' => 'true',
	                    'default' => [
	                        'url' => Utils::getPlaceholderImageSrc(),
	                    ],
					]
				);
				$repeater->addResponsiveControl(
					'max-width',
					[
						'label' => __('Content max width'),
						'type' => ControlsManager::SLIDER, 
						'size_units' => array('px', '%'),
						'range' => array(
							'px' => array(
								'min' => 0,
								'max' => 2000,
							),
						),
						'default' => [
							'unit' => 'px',
						],
						'selectors' => [
							'{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner .slideshow-content' => 'max-width: {{SIZE}}{{UNIT}};',
						],
					],
				);
				$repeater->addResponsiveControl(
					'x', 
					[
	                    'label' => __('X Position', 'Background Control'),
	                    'type' => ControlsManager::SLIDER, 
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'left: {{SIZE}}%;',
	                    ],
					]
				);
				$repeater->addResponsiveControl(
					'y', 
					[
	                    'label' => __('Y Position', 'Background Control'),
	                    'type' => ControlsManager::SLIDER,                        
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [ 
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'top: {{SIZE}}%',
	                    ],
					]
				);
				$repeater->addControl(
					'title1', 
					[
	                    'label' => __('Title 1'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'title2', 
					[
	                    'label' => __('Title 2'),
	                    'type' => ControlsManager::TEXT,
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'title3', 
					[
	                    'label' => __('Title 3'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'subtitle', 
					[
	                    'label' => __('Subtitle'),
	                    'type' => ControlsManager::TEXT,                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'button', 
					[
	                    'label' => __('Button text'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'link', 
					[
	                    'label' => __('Link'),
	                    'type' => ControlsManager::URL,
	                    'placeholder' => __('https://your-link.com'),
					]
				);
			$repeater->endControlsTab();
			$repeater->startControlsTab( 'style',
				[
					'label' => __( 'Style'),
				]
			);
				$repeater->addResponsiveControl(
					'alignment', 
					[
						'label' => __('Alignment', 'elementor'),
		                'type' => ControlsManager::CHOOSE,
		                'options' => array(
		                    'left' => array(
		                        'title' => __('Left', 'elementor'),
		                        'icon' => 'fa fa-align-left',
		                    ),
		                    'center' => array(
		                        'title' => __('Center', 'elementor'),
		                        'icon' => 'fa fa-align-center',
		                    ),
		                    'right' => array(
		                        'title' => __('Right', 'elementor'),
		                        'icon' => 'fa fa-align-right',
		                    ),
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'text-align: {{VALUE}};',
		                ),
		                'separator' => 'after'
					]
				);
				//Start title 1
				$repeater->addControl(
					'heading1', 
					[
	                	'label' => __('Title 1'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title1_color', 
					[
		            	'label' => __('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title1_typography',
						'selector' => '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title1',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title1_space',
	    			[
	                    'label' => __('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title1_animation', 
					[
	                	'label' => __('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start title 2
				$repeater->addControl(
					'heading2', 
					[
	                	'label' => __('Title 2'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title2_color', 
					[
		            	'label' => __('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title2' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title2_typography',
						'selector' => '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title2',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title2_space',
	    			[
	                    'label' => __('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title2_animation', 
					[
	                	'label' => __('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start title 3
				$repeater->addControl(
					'heading3', 
					[
	                	'label' => __('Title 3'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title3_color', 
					[
		            	'label' => __('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title3' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title3_typography',
						'selector' => '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title3',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title3_space',
	    			[
	                    'label' => __('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .title3' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title3_animation', 
					[
	                	'label' => __('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start subtitle
				$repeater->addControl(
					'heading4', 
					[
	                	'label' => __('Subtitle'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'subtitle_color', 
					[
		            	'label' => __('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'subtitle_typography',
						'selector' => '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .subtitle',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'subtitle_space',
	    			[
	                    'label' => __('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'subtitle_animation', 
					[
	                	'label' => __('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start button
				$repeater->addControl(
					'heading5', 
					[
	                	'label' => __('Button'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'button_typography',
						'selector' => '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button',
						'separator' => 'none',
					]
				);
				$repeater->addControl(
					'button_color', 
					[
		            	'label' => __('Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => [
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}}  a.slideshow-button' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addControl(
		            'background_color',
		            array(
		                'label' => __('Background Color', 'elementor'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => array(
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_4,
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button' => 'background-color: {{VALUE}};',
		                ),
		            )
		        );
		        $repeater->addControl(
					'button_colorh', 
					[
		            	'label' => __('Hover Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => [
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button:hover' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addControl(
		            'background_colorh',
		            array(
		                'label' => __('Hover Background Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => array(
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_4,
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .elementor-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button:hover' => 'background-color: {{VALUE}};',
		                ),
		            )
		        );
		        $repeater->addGroupControl(
		            GroupControlBorder::getType(),
		            array(
		                'name' => 'border',
		                'label' => __('Border'),
		                'placeholder' => '1px',
		                'default' => '1px',
		                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} a.slideshow-button',
		            )
		        );

		        $repeater->addControl(
		            'border_radius',
		            array(
		                'label' => __('Border Radius'),
		                'type' => ControlsManager::DIMENSIONS,
		                'size_units' => array('px', '%'),
		                'selectors' => array(
		                    '{{WRAPPER}} {{CURRENT_ITEM}} a.slideshow-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		                ),
		            )
		        );

		        $repeater->addGroupControl(
		            GroupControlBoxShadow::getType(),
		            array(
		                'name' => 'button_box_shadow',
		                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} a.slideshow-button',
		            )
		        );

		        $repeater->addResponsiveControl(
		            'text_padding',
		            array(
		                'label' => __('Text Padding'),
		                'type' => ControlsManager::DIMENSIONS,
		                'size_units' => array('px', 'em', '%'),
		                'selectors' => array(
		                    '{{WRAPPER}} {{CURRENT_ITEM}} a.slideshow-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		                ),
		                'separator' => 'before',
		            )
		        );
				$repeater->addControl(
					'button_animation', 
					[
	                	'label' => __('Animation'),
		                'type' => ControlsManager::ANIMATION,
					]
				);
			$repeater->endControlsTab();
			$repeater->endControlsTabs();
			$this->addControl(
	            'slideshow_list',
	            [
	                'label' => 'Slideshow items',
	                'type' => ControlsManager::REPEATER,
	                'fields' => $repeater->getControls(),
	                'default' => [],
	            ]
	        );
            
        $this->endControlsSection();

		//Tab Setting
		$this->startControlsSection(
			'setting_section',
			[
				'label' => __('Slider'),
				'tab' => ControlsManager::TAB_SETTINGS,
			]
		);
			$this->addControl(
	            'navigation',
	            [
	                'label' => __('Navigation'),
	                'type' => ControlsManager::SELECT,
	                'default' => 'both',
	                'options' => [
	                    'both' => __('Arrows and Dots'),
	                    'arrows' => __('Arrows'),
	                    'dots' => __('Dots'),
	                    'none' => __('None'),
	                ],
	                'frontend_available' => true,
	            ]
	        );
			$this->addControl(
				'autoplay',
				[
					'label' => __('Autoplay'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' => 'false',  
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
                    'frontend_available' => true,
				]
			);
			$this->addControl(
				'autoplay_speed',
				[
					'label'     	=> __('AutoPlay Transition Speed (ms)'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 3000,
					'frontend_available' => true,
				]
			);
			$this->addControl(
				'pause_on_hover',
				[
					'label' 		=> __('Pause on Hover'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'yes',
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
                    'frontend_available' => true,
				]
			);

			$this->addControl(
				'infinite',
				[
					'label'        	=> __('Infinite Loop'),
					'type'         	=> ControlsManager::SWITCHER,
					'default'      	=> 'no',
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
                    'frontend_available' => true,
				]
			);
			$this->addControl(
				'transition_speed',
				[
					'label'     	=> __('Transition Speed (ms)'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 500,
					'frontend_available' => true,
				]
			);
			$this->addControl(
	            'effect',
	            [
	                'label' => __('Effect'),
	                'type' => ControlsManager::SELECT,
	                'default' => 'slide',
	                'options' => [
	                    'slide' => __('Slide'),
	                    'fade' => __('Fade'),
	                ],
	                'condition' => [
	                    'slides_to_show' => '1',
	                    'center_mode' => '',
	                ],
	                'frontend_available' => true,
	            ]
	        );
		
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {

		$settings = $this->getSettingsForDisplay(); 	
		$this->addRenderAttribute(
			'slideshow', 
			[
				'class' => ['elementor-slideshow', 'slick-block', 'items-desktop-1', 'items-tablet-1', 'items-mobile-1'],
			]
		);

		if ( $settings['slideshow_list'] ) { ?>
			<div class="elementor-slideshow-wrapper">
				<div <?php echo $this->getRenderAttributeString('slideshow'); ?>>
				<?php foreach (  $settings['slideshow_list'] as $item ) :
					$image = Tools::safeOutput(Helper::getMediaLink($item['slideshow_image']['url']));

					$this->addRenderAttribute('class-item', 'class', ['slideshow-item','elementor-repeater-item-' . $item['_id']]); ?>
					<div <?php echo $this->getRenderAttributeString('class-item'); ?>>

						<div class="slider-item" style="background:url(<?= $image ?>);background-size: cover; background-position: center;">
							<div class="desc-banner">
								<div class="container">				
									<div class="slideshow-content">
										<?php if(isset($item['title1']) && $item['title1'] != '') : ?>
											<div class="title1" data-animation="animated <?= $item['title1_animation'] ?>">
												<?= $item['title1'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title2'] != '') : ?>
											<div class="title2" data-animation="animated <?= $item['title2_animation'] ?>">
												<?= $item['title2'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title3'] != '') : ?>
											<div class="title3" data-animation="animated <?= $item['title3_animation'] ?>">
												<?= $item['title3'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['subtitle']) && $item['subtitle'] != '') : ?>
											<div class="subtitle" data-animation="animated <?= $item['subtitle_animation'] ?>">
												<?= $item['subtitle'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['link']['url']) && $item['link']['url'] != '' && $item['button'] != '') : ?>
											<a class="slideshow-button" href="<?= $item['link']['url'] ?>" data-animation="animated <?= $item['button_animation'] ?>">
												<?= $item['button'] ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
			<?php 
		}  
		

	} 

	protected function _contentTemplate() {
		?>
		<#
			
		#>
		<div class="elementor-slideshow-wrapper">
			<div class="elementor-slideshow">
				<# console.log(settings); _.each( settings.slideshow_list, function( slide ) { #>
					<div class="slideshow-item elementor-repeater-item-{{{slide._id}}}">
						<div class="slider-item" style="background: url({{{ baseDir + slide.slideshow_image.url }}});background-size: cover; background-position: center;">
							<div class="desc-banner">
								<div class="container">
									<div class="slideshow-content">
										<# console.log(slide); #>
											<div class="title1" data-animation="animated {{{ slide.title1_animation }}}">
												{{{ slide.title1 }}}
											</div>
										<# 
										if ( slide.title2 ) { #>
											<div class="title2" data-animation="animated {{{ slide.title2_animation }}}">
												{{{ slide.title2 }}}
											</div>
										<# }
										if ( slide.title3 ) { #>
											<div class="title3" data-animation="animated {{{ slide.title3_animation }}}">
												{{{ slide.title3 }}}
											</div>
										<# }
										if ( slide.subtitle ) { #>
											<div class="subtitle" data-animation="animated {{{ slide.subtitle_animation }}}">
												{{{ slide.subtitle }}}
											</div>
										<# }
										if ( slide.button && slide.link ) { #>
											<a class="slideshow-button" href="{{{ slide.link.url }}}" data-animation="animated {{{ slide.button_animation }}}">
												{{{ slide.button }}}
											</a>
										<# } #>
									</div>
								</div>
							</div>
						</div>
					</div>
				<# }) #>
			</div>
		</div>
		<?php
	}
	
}