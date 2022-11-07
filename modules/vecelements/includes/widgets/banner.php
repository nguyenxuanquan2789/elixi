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
 * Elementor image carousel widget.
 *
 * Elementor widget that displays a set of images in a rotating carousel or
 * slider.
 *
 * @since 1.0.0
 */
class WidgetBanner extends WidgetBase {

	public function getName() 
	{
		return 'banner';
	}

	public function getTitle() 
	{
		return 'Banner';
	}

	public function getIcon() 
	{ 
		return 'eicon-text-area';
	}

	public function getKeywords()
    {
        return ['image', 'photo', 'banner'];
    }
 
	protected function _registerControls() {

		$this->startControlsSection(
			'section_banner',
			[
				'label' 		=> __('Banner'),
			]
		);

		$this->addControl(
			'image',
			[
				'label'   		=> __('Image'),
				'type'    		=> ControlsManager::MEDIA,
				'seo'			=> true,
				'dynamic' => [
                    'active' => true,
                ],
				'default' 		=> [
					'url' => Utils::getPlaceholderImageSrc()
				],
			]
		);
		$this->addControl(
			'title',
			[
				'label'   		=> __('Title'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
				'default'		=> 'Title 1'
			]
		);
		
		$this->addControl(
			'title2',
			[
				'label'   		=> __('Title 2'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
				'default'		=> 'Title 2'
			]
		);

		$this->addControl(
			'subtitle',
			[
				'label'   		=> __('Subtitle'),
				'type'    		=> ControlsManager::TEXTAREA,
				'default'		=> 'Subtitle'
			]
		);

		$this->addControl(
			'link',
			[
				'label'   		=> __('Link'),
				'type'    		=> ControlsManager::URL,
				'placeholder' 	=> __('https://your-link.com'),
				'default'		=> array(
					'url' => '#',
				)
			]
		);
		$this->addControl(
			'button_link',
			[
				'label'   		=> __('Button text'),
				'type'    		=> ControlsManager::TEXT,
				'label_block' 	=> true,
				'description'   => __('Leave it empty if you dont want to use button link.'),
				'default'		=> 'Button'
			]
		);

		$this->endControlsSection();
		
		$this->startControlsSection(
			'section_style',
			[
				'label' 		=> __('General'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'hor_align',
				[
					'label' => __('Horizontal Alignment'),
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
					'default' => 'left',
					'toggle' => true,
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->addControl(
				'ver_align',
				[
					'label' => __('Vertical Alignment'),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'flex-start' => [
							'title' => __('Top'),
							'icon' => 'eicon-v-align-top',
						],
						'center' => [
							'title' => __('Middle'),
							'icon' => 'eicon-v-align-middle',
						],
						'flex-end' => [
							'title' => __('Bottom'),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'center',
					'toggle' => true,
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'justify-content: {{VALUE}};',
					],
				]
			);
			
			$this->addControl(
				'padding',
				[
					'label' => __('Padding'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);


        $this->endControlsSection();

		$this->startControlsSection(
			'section_title_style',
			[
				'label' 		=> __('Title 1'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'title_color',
				[
					'label' 		=> __('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-title',
				]
			);
			$this->addResponsiveControl(
				'title_spacing',
				[
					'label' => __('Spacing'),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'section_title2_style',
			[
				'label' 		=> __('Title 2'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
		
			$this->addControl(
				'title2_color',
				[
					'label' 		=> __('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-title2' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title2_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-title2',
				]
			);
			$this->addResponsiveControl(
				'title2_spacing',
				[
					'label' => __('Spacing'),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-title2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

		$this->startControlsSection(
			'section_subtitle_style',
			[
				'label' 		=> __('Subtitle'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);

			$this->addControl(
				'subtitle_color',
				[
					'label' 		=> __('Color'),
					'type' 			=> ControlsManager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-text' => 'color: {{VALUE}};',
					],
				]
			);

			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'subtitle_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-text',
				]
			);
			$this->addResponsiveControl(
				'subtitle_spacing',
				[
					'label' => __('Spacing'),
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
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .banner-content .banner-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

        $this->endControlsSection();

        $this->startControlsSection(
			'section_button',
			[
				'label' 		=> __('Button link'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'button_typo',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-button',
				]
			);
			$this->addResponsiveControl(
				'button_padding',
				[
					'label' 		=> __('Padding'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'button_border_radius',
				[
					'label' 		=> __('Border Radius'),
					'type' 			=> ControlsManager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .home-banner .banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' 			=> 'button_border',
					'selector' 		=> '{{WRAPPER}} .home-banner .banner-button',
				]
			);
			$this->startControlsTabs('tabs_banner_style');
				$this->startControlsTab(
					'tab_button_normal',
					[
						'label' 		=> __('Normal'),
					]
				);
					$this->addControl(
						'button_color',
						[
							'label' 		=> __('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button' => 'color: {{VALUE}};',
							],
						]
					);

					$this->addControl(
						'button_background',
						[
							'label' 		=> __('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button' => 'background-color: {{VALUE}};',
							],
						]
					);
					
					
				$this->endControlsTab();
				$this->startControlsTab(
					'tab_hover_normal',
					[
						'label' 		=> __('Hover'),
					]
				);
					$this->addControl(
						'button_hover_color',
						[
							'label' 		=> __('Color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover , {{WRAPPER}} .home-banner .banner-button:focus' => 'color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'button_hover_background',
						[
							'label' 		=> __('Background color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover, {{WRAPPER}} .home-banner .banner-button:focus' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'button_hover_border_color',
						[
							'label' 		=> __('Border color'),
							'type' 			=> ControlsManager::COLOR,
							'selectors' 	=> [
								'{{WRAPPER}} .home-banner .banner-button:hover, {{WRAPPER}} .home-banner .banner-button:focus' => 'border-color: {{VALUE}};',
							],
						]
					);
					
				$this->endControlsTab();
			$this->endControlsTabs();
        $this->endControlsSection();

        $this->startControlsSection(
			'section_hover',
			[
				'label' 		=> __('Hover image'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'hover_opacity',
				[
					'label' 		=> __('Opacity'),
					'type' 			=> ControlsManager::SLIDER,
					'range' 		=> [
						'px' => [
							'max' => 1,
							'min' => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' 	=> [
						'body {{WRAPPER}} .home-banner img:hover' => 'opacity: {{SIZE}};',
					],
				]
			);
			$this->addControl(
				'hover_animation',
				[
					'label' => __('Hover animation'),
					'type' => ControlsManager::SELECT2,
					'multiple' => false,
					'options' => [
						'animation1'  => __('Animation 1'),
						'animation2' => __('Animation 2'),
						'animation3' => __('Animation 3'),
					],
					'default' =>  'animation1' ,
				]
			);
		
		$this->endControlsSection();

	}

	protected function render() {
		$settings 		= $this->getSettings();

		$title 			= $settings['title'];
		$title2 		= $settings['title2'];
		$subtitle 		= $settings['subtitle'];
        $link 			= $settings['link'];
        $button_link 	= $settings['button_link'];

		$this->addRenderAttribute('banner', 'class', ['home-banner', $settings['hover_animation']]);

		$this->addRenderAttribute('content', 'class', 'banner-content');
		$this->addRenderAttribute('title', 'class', 'banner-title');
		$this->addRenderAttribute('title2', 'class', 'banner-title2');
		$this->addRenderAttribute('subtitle', 'class', 'banner-text'); 
		$html = '';

		$html .= '<figure '.$this->getRenderAttributeString('banner').'>';
			if(! empty($link['url'])) {
				$this->addRenderAttribute('link', 'class', 'rt-banner-link');
				$this->addRenderAttribute('link', 'href', $link['url']);

				if($link['is_external']) {
					$this->addRenderAttribute('link', 'target', '_blank');
				}

				$html .= '<a ' . $this->getRenderAttributeString('link') . '>';
			} 
			$html .= GroupControlImageSize::getAttachmentImageHtml($settings);
			if(! empty($link['url'])) {
				$html .= '</a>';
			}
				$html .= '<figcaption>';
					$html .= '<div '. $this->getRenderAttributeString('content').'>';
						$html .= '<p '. $this->getRenderAttributeString('title') .'>'. $title .'</p>';
						$html .= '<p '. $this->getRenderAttributeString('title2') .'>'. $title2 .'</p>';
						$html .= '<p '. $this->getRenderAttributeString('subtitle') .'>'. $subtitle .'</p>';
						if(!empty($button_link) && !empty($link['url'])) { 
							$html .= '<div><a class="banner-button" href="'. $link['url'] .'">'. $button_link .'</a></div>';
						}
					$html .= '</div>';
				$html .= '</figcaption>';
			
		$html .= '</figure>';

	    echo $html;
	}

	/**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'poselements', basename(__FILE__, '.php'));
    }

}