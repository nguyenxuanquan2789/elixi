<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

use Context;
use DB;
use Meta;


class WidgetLinksList extends WidgetBase { 

	public function getName() 
	{
		return 'links_list';
	}
	public function getTitle() 
	{
		return __( 'Links' );
	}

	public function getIcon() {
		return 'eicon-editor-list-ul';
	}

	public function getCategories() 
	{
		return ['premium'];
	}

	protected function _registerControls() {
		
		$this->startControlsSection(
			'content_section',
			[
				'label' => __( 'Title' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'title',
				[
					'label' => __( 'Title' ),
					'type' => ControlsManager::TEXT, 
					'dynamic' => [
						'active' => true,
					],
					'separator' => 'none',
				]
			);
			$this->addControl(
	            'link',
	            array(
	                'label' => __('Link'),
	                'type' => ControlsManager::URL,
	                'placeholder' => 'http://your-link.com',
	                'default' => array(
	                    'url' => '',
	                ),
	            )
	        );
	        $this->addResponsiveControl(
            	'text_align',
	            [
	                'label' => __('Alignment'),
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
	                'selectors' => [
	                    '{{WRAPPER}} .vec-links-widget' => 'text-align: {{VALUE}};',
	                ],
	            ]
	        );
	        $repeater = new Repeater();
	        $repeater->addControl(
	            'title',
	            [
	                'label' => __('Title'),
	                'type' => ControlsManager::TEXT,
	                'label_block' => true,
	                'default' => __('Link title'),
	                'label_block'=> true,
	            ]
	        );
	        $repeater->addControl(
	            'type_link',
	            [
                    'label' => __('Type link'),
                    'type' => ControlsManager::SELECT,
                    'options' => [
                    	'page' => __('Content pages'),
                    	'static' => __('Static pages'),
                    	'custom' => __('Custom'),
                    ],
                    'default' => 'custom'
	            ]
	        );
	        $repeater->addControl(
	            'page_link',
	            [
                    'label' => __('Content pages'),
                    'type' => ControlsManager::SELECT2,
                    'options' => $this->getCMSPages(1),
                    'condition' => array(
	                    'type_link' => 'page',
	                ),
	                'label_block' => true,
	            ]
	        );
	        $repeater->addControl(
	            'static_link',
	            [
                    'label' => __('Static pages'),
                    'type' => ControlsManager::SELECT2,
                    'options' => $this->getPagesOption(),
                    'condition' => array(
	                    'type_link' => 'static',
	                ),
	                'label_block' => true,
	            ]
	        );
	        $repeater->addControl(
	            'custom_link',
	            [
                	'label' => __('Custom Link'),
	                'type' => ControlsManager::URL,
	                'placeholder' => 'http://your-link.com',
	                'default' => array(
	                    'url' => '',
	                ),
	                'condition' => array(
	                    'type_link' => 'custom',
	                ),
	            ]
	        );
	        $this->addControl(
	            'links',
	            [
	                'label' => 'Links list',
	                'type' => ControlsManager::REPEATER,
	                'fields' => $repeater->getControls(),
	                'default' => [
	                    
	                ],
	                'title_field' => '{{{ title }}}',
	            ]
	        );
			$this->addControl(
				'display_link',
				[
					'label' => __('Display'),
					'type' => ControlsManager::SELECT,
					'default' => 'block',
					'options' => [ 
						'block' => __('Block'),
						'inline' => __('Inline (auto)')
					],
					'prefix_class' => 'display-',
				]
			);
		$this->endControlsSection();
		
		$this->startControlsSection(
			'title_section',
			[
				'label' => __( 'Title' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$designs_title = array('1' => 'Classic','2' => 'Border Title');
			$this->addControl(
				'design',
				[
					'label' => __( 'Select design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs_title,
					'prefix_class' => 'title-',
					'frontend_available' => true,
					'default' => '1'
				]
			);
			$this->addControl(
	            'border_title_color',
	            array(
	                'label' => __('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .links-widget-title:after' => 'background: {{VALUE}};', 
	                ),
					'condition' => [
						'design' => '2',
					],
	            )
	        );
			$this->addControl(
				'space_title_size',
				[
					'label' => __( 'Title Spacing'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 100,
						]
					],
					'default' => [
						'size' => 20,
						'unit' => 'px',
					],
					'selectors' => [
						'{{WRAPPER}} .vec-links-widget .links-widget-title' => 'margin-bottom: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'title_typo',
					'selector' 		=> '{{WRAPPER}} .vec-links-widget .links-widget-title',
				]
			);
			$this->addControl(
	            'title_color',
	            array(
	                'label' => __('Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-links-widget .links-widget-title a,{{WRAPPER}} .vec-links-widget .links-widget-title span' => 'color: {{VALUE}};',
	                ),
	            )
	        );
		$this->endControlsSection();
		$this->startControlsSection(
			'links_section',
			[
				'label' => __( 'Links' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		); 
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'links_typo',
					'selector' 		=> '{{WRAPPER}} .vec-links-widget .links-widget-content a',
				]
			);
			$this->addControl(
	            'padding_links',
	            array(
	                'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .vec-links-widget .links-widget-content a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', 
					],
	            )
	        ); 
			$this->addControl(
	            'margin_links',
	            array(
					'label' => __( 'margin' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .vec-links-widget .links-widget-content a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
	            )
	        );
			$this->addGroupControl(
	            GroupControlBorder::getType(),
	            array(
	                'name' => 'border_links',
	                'label' => __('Border'),
	                'placeholder' => '1px',
	                'default' => '1px',
	                'selector' => '{{WRAPPER}} .vec-links-widget .links-widget-content a'
	            )
	        );
			 $this->addControl(
	            'link_border_radius_links',
	            array(
	                'label' => __('Border Radius', 'elementor'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-links-widget .links-widget-content a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
		
			$this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => __('Normal'),
	            )
	        );

	        $this->addControl(
	            'content_text_color',
	            array(
	                'label' => __('Text Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-links-widget .links-widget-content a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-links-widget .links-widget-content a' => 'background-color: {{VALUE}};',
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
	                    '{{WRAPPER}} .vec-links-widget .links-widget-content a:hover' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_hover_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .vec-links-widget .links-widget-content a:hover' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );
	        $this->endControlsTab();

	        $this->endControlsTabs();
			
		$this->endControlsSection();

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {

		$settings = $this->getSettings(); 
		$context = Context::getContext();
		$context->smarty->assign(
			array(
				'title'         => $settings['title'],
				'title_url'     => $settings['link'],
				'list_links'    => $settings['links'],
				'id'			=> $this->getId()
			)
		);
		echo $context->smarty->fetch( _VEC_PATH_ . 'views/templates/front/widgets/links-list.tpl' );
		
	} 

	public function getCMSPages($id_cms_category = 1, $id_shop = false, $id_lang = false)	
    {	
        $output = [];	
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;	
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;	
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`	
            FROM `'._DB_PREFIX_.'cms` c	
            INNER JOIN `'._DB_PREFIX_.'cms_shop` cs	
            ON (c.`id_cms` = cs.`id_cms`)	
            INNER JOIN `'._DB_PREFIX_.'cms_lang` cl	
            ON (c.`id_cms` = cl.`id_cms` AND cs.`id_shop` = cl.`id_shop`)	
            WHERE c.`id_cms_category` = '.(int)$id_cms_category.'	
            AND cl.`id_shop` = '.(int)$id_shop.'	
            AND cl.`id_lang` = '.(int)$id_lang.'	
            AND c.`active` = 1	
            ORDER BY `position`';	
        $pages = Db::getInstance()->executeS($sql);	
        foreach ($pages as $page){	
            $output[Context::getContext()->link->getCMSLink($page['id_cms'])] = (isset($spacer) ? $spacer : '').$page['meta_title'];	
        } 	
        return $output;	
    }	
    	
    public function getPagesOption($id_lang = null)	
    {	
        $context = Context::getContext();	
        $output = [];	
        if (is_null($id_lang)) $id_lang = (int)$context->cookie->id_lang;	
        $contact = Meta::getMetaByPage('contact', $id_lang);	
        if($contact){	
            $output[$context->link->getPageLink($contact['page'])] = $contact['title'];	
        };	
        $sitemap = Meta::getMetaByPage('sitemap', $id_lang);	
        if($sitemap){	
            $output[$context->link->getPageLink($sitemap['page'])] = $sitemap['title'];	
        };	
        $stores = Meta::getMetaByPage('stores', $id_lang);	
        if($stores){	
            $output[$context->link->getPageLink($stores['page'])] = $stores['title'];	
        };	
        $myaccount = Meta::getMetaByPage('my-account', $id_lang);	
        if($myaccount){	
            $output[$context->link->getPageLink($myaccount['page'])] = $myaccount['title'];	
        };	
        $pricesDrop = Meta::getMetaByPage('prices-drop', $id_lang);	
        if($pricesDrop){	
            $output[$context->link->getPageLink($pricesDrop['page'])] = $pricesDrop['title'];	
        };	
        $newProduct = Meta::getMetaByPage('new-products', $id_lang);	
        if($newProduct){	
            $output[$context->link->getPageLink($newProduct['page'])] = $newProduct['title'];	
        };	
        $bestSales = Meta::getMetaByPage('best-sales', $id_lang);	
        if($bestSales){	
            $output[$context->link->getPageLink($bestSales['page'])] = $bestSales['title'];	
        };	
        return $output;	
    }
}