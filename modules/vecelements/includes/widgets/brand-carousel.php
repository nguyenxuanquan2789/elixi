<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or exit;

class WidgetBrandCarousel extends WidgetBase 
{
	use CarouselTrait;

	public function getName() {
		return 'brand-carousel';
	}

	public function getTitle() {
		return __('Brand carousel');
	}
	
	public function getIcon() {
		return 'eicon-barcode';
	}

	public function getCategories()
    {
        return ['premium', 'maintenance-premium'];
    }

    public function getKeywords()
    {
        return ['brand', 'manufacturer', 'carousel', 'slider'];
    }
	
	protected function _registerControls() {
		
		//Display
		$this->startControlsSection(
			'item_section',
			[
				'label' => __('Content'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'manufacturer',
				[
					'label' => __('Manufacturers'),
					'type' => ControlsManager::SELECT,
					'default' => 'all',
					'options' => [
						'all' => __('All manufacturers'),
						'select' => __('Select manufacturers'),
					],
					'separator' => 'before',
				]
			);
			$this->addControl(
				'manufacturer_list',
				[
					'label' => __('Select manufacturers'),
					'type' => ControlsManager::SELECT2,
					'multiple' => true,
					'options' => $this->getManufacturers(),
					'condition' => [
						'manufacturer' => 'select'
					]
				]
			);
			$this->addControl(
				'limit',
				[
					'label'     	=> __('Limit'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 8,
					'condition' => [
						'manufacturer' => 'all'
					]
				]
			);
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> __('Enable Slider'),
					'type' 			=> ControlsManager::HIDDEN,
					'default' 		=> 'yes', 
				]
			);		
		$this->endControlsSection();
		$this->registerCarouselSection([
            'default_slides_desktop' => 6,
            'default_slides_tablet' => 4,
            'default_slides_mobile' => 2,
        ]);
		$this->startControlsSection(
            'section_style_brand',
            [
                'label' => __('Brand items'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
			$this->addResponsiveControl(
				'padding',
				[
					'label' => __('Padding'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-manufacturer-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'margin',
				[
					'label' => __('Margin'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-manufacturer-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .elementor-manufacturer-image',
					'separator' => 'before',
				]
			);
			$this->addControl(
				'button_hover_border_color',
				[
					'label' => __('Border Color hover'),
					'type' => ControlsManager::COLOR,
					'condition' => [
						'border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-manufacturer-image:hover, {{WRAPPER}} .elementor-manufacturer-image:focus' => 'border-color: {{VALUE}};',
					],
				]
			);
		$this->endControlsSection();
		$this->registerNavigationStyleSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {
		$settings = $this->getSettingsForDisplay();
		$manufacturers = array();
		if($settings['manufacturer'] == 'all'){
			$manufacturers = \Manufacturer::getManufacturers();

			if(empty($manufacturers)) 
				return __('There is no manufacturer. Please add manufacturers first.');
		}else{
			if(count($settings['manufacturer_list']) > 0){
				$manufacturers = $this->getManufacturersById(implode("," , $settings['manufacturer_list']));
			}
			
			if(empty($manufacturers)) 
				return __('There is no manufacturer. Please select manufacturers.');
		}
		
		$slides = array();
        foreach ($manufacturers as $manufacturer) {
			$link = \Context::getContext()->link->getManufacturerLink($manufacturer['id_manufacturer'], $manufacturer['link_rewrite']);
			$image = \Context::getContext()->link->getManufacturerImageLink($manufacturer['id_manufacturer']);

            ob_start();
            ?>
			<div class="elementor-manufacturer-wrapper">
				<div class="elementor-manufacturer-image">
					<a href="<?= $link ?>">
						<img loading="lazy" src="<?= $image ?>" alt="<?= $manufacturer['name'] ?>"/>
					</a>
				</div>
			</div>
            <?php
            $slides[] = ob_get_clean();
        }

        $this->renderCarousel($settings, $slides);
	} 
	function getManufacturers(){
		$output = array();
		$manufacturers = \Manufacturer::getManufacturers();

		if(!count($manufacturers)) return $output;

		foreach($manufacturers as $manu){
			$output[$manu['id_manufacturer']] = $manu['name'];
		}

		return $output;
	}
	public function getManufacturersById($ids)
    {
        $idLang = (int) \Configuration::get('PS_LANG_DEFAULT');
        
        if (!\Group::isFeatureActive()) {
            $allGroup = true;
        }

        $manufacturers = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT m.* 
		FROM `' . _DB_PREFIX_ . 'manufacturer` m'
        . \Shop::addSqlAssociation('manufacturer', 'm') .
        'INNER JOIN `' . _DB_PREFIX_ . 'manufacturer_lang` ml ON (m.`id_manufacturer` = ml.`id_manufacturer` AND ml.`id_lang` = ' . (int) $idLang . ')' .
        'WHERE m.`id_manufacturer` IN ('. $ids .
        ') AND m.`active` = 1 ' .
        'ORDER BY m.`name` ASC LIMIT 99');

        if ($manufacturers === false) {
            return false;
        }
		$rewriteSettings = (int) \Configuration::get('PS_REWRITING_SETTINGS');
        for ($i = 0; $i < count($manufacturers); ++$i) {
            $manufacturers[$i]['link_rewrite'] = ($rewriteSettings ? \Tools::link_rewrite($manufacturers[$i]['name']) : 0);
        }

        return $manufacturers;
    }
}