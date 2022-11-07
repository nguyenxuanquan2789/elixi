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

class WidgetCategories extends WidgetProductBase 
{
	use CarouselTrait;

	public function getName() {
		return 'categories';
	}

	public function getTitle() {
		return __('Categories list');
	}
	
	public function getIcon() {
		return 'eicon-post-list';
	}

	public function getCategories()
    {
        return ['premium'];
    }

    public function getKeywords()
    {
        return ['category', 'categories', 'carousel', 'slider'];
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
			$repeater = new Repeater();

			$repeater->addControl(
				'category',
				[
					'label' => __('Category'),
					'type' => ControlsManager::SELECT,
					'options' => $this->getCategoryOptions(),
				]
			);
			$repeater->addControl(
				'image',
				[
					'label' => __('Add Image'),
					'type' => ControlsManager::MEDIA,
					'seo' => 'true',
					'default' => [
						'url' => Utils::getPlaceholderImageSrc(),
					],
				]
			);
			$this->addControl(
				'category_list',
				[
					'label' => '',
					'type' => ControlsManager::REPEATER,
					'fields' => $repeater->getControls(),
					'default' => [],
					'title_field' => '<# if (image.url) { #>' .
						'<img src="{{ elementor.imagesManager.getImageUrl(image) }}" class="ce-repeater-thumb"><# } #>' .
						'category ID : {{{ category }}}',
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
			'layout_section',
			[
				'label' => __('Layout'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'layout',
				[
					'label' => __('Layout'),
					'type' => ControlsManager::SELECT,
					'default' => '1',
					'options' => [
						'1' => __('Image inline'),
						'2' => __('Image above'),
						'3' => __('Image background'),
					],
					'separator' => 'before',
				]
			);
			$this->addControl(
				'image_position',
				[
					'label' => __('Image position'),
					'type' => ControlsManager::SELECT,
					'default' => 'left',
					'prefix_class' => 'image-position-',
					'options' => [
						'left' => __('Left'),
						'right' => __('Right'),
					],
					'condition' => [
						'layout' => '1'
					],
				]
			);
			$this->addControl(
				'layout_style',
				[
					'label' 		=> __('No-padding layout style'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '', 
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
					'prefix_class' => 'style-no-padding-',
				]
			);
			$this->addControl(
				'show_count',
				[
					'label' 		=> __('Show Count Products'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '', 
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
				]
			);
			$this->addControl(
				'show_subcategories',
				[
					'label' 		=> __('Show Subcategories'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '', 
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
				]
			);
			$this->addControl(
				'limit_subcategories',
				[
					'label' => __( 'Limit subcategories' ),
					'type' => ControlsManager::NUMBER,
					'min' => 1,
					'max' => 10,
					'step' => 1,
					'default' => 3,
					'condition'    	=> [
						'show_subcategories' => 'yes',
					],
				]
			);
			$this->addControl(
				'show_link',
				[
					'label' 		=> __('Show Link View'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> '', 
					'label_on'      => __('Yes'),
                    'label_off'     => __('No'),
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
            'default_slides_desktop' => 3,
            'default_slides_tablet' => 2,
            'default_slides_mobile' => 1,
        ]);
		$this->startControlsSection(
            'section_style_brand',
            [
                'label' => __('Category items'),
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
						'{{WRAPPER}} .elementor-categories-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .elementor-categories-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'layout_style!' => 'yes'
					],
				]
			);
			$this->addGroupControl(
				GroupControlBorder::getType(),
				[
					'name' => 'border',
					'selector' => '{{WRAPPER}} .elementor-categories-item',
					'separator' => 'before',
					'condition' => [
						'layout_style!' => 'yes'
					],
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
						'{{WRAPPER}} .elementor-categories-item:hover, {{WRAPPER}} .elementor-categories-item:focus' => 'border-color: {{VALUE}};',
					],
					'condition' => [
						'layout_style!' => 'yes'
					],
				]
			);
			$this->addResponsiveControl(
				'border_radius',
				[
					'label' => __('Border Radius'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => array('px', '%'),
					'selectors' => [
						'{{WRAPPER}} .elementor-categories-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'layout' => '1'
				]
            ]
        );
			$this->addResponsiveControl(
				'image_size',
				[
					'label' => __('Max width'),
					'type' => ControlsManager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500, 
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => ['px', '%'],
					'selectors' => [
						'{{WRAPPER}} .elementor-categories-image' => 'max-width: {{SIZE}}{{UNIT}}',
					],      
				]
			);
		$this->endControlsSection();
		$this->startControlsSection(
            'section_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
			$this->addControl(
				'alignment',
				[
					'label' => __('Alignment'),
					'label_block' => false,
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
					'separator' => '',
					'selectors' => [
						'{{WRAPPER}} .elementor-categories-content' => 'text-align: {{VALUE}};',
					],
				]
			);	
			$this->addControl(
				'content_position',
				[
					'label' => __('Vertical Align'),
					'type' => ControlsManager::SELECT,
					'default' => 'middle',
					'prefix_class' => 'content-position-',
					'options' => [
						'top' => __('Top'),
						'middle' => __('Middle'),
						'bottom' => __('Bottom'),
					],
					'condition' => [
						'layout' => '3'
					],
				]
			);
			$this->addResponsiveControl(
				'padding_content',
				[
					'label' => __('Padding'),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-categories-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'name_typography',
					'label' => __('Category name typography'),
					'scheme' => SchemeTypography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .elementor-categories-name',
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'sub_typography',
					'label' => __('Subcategory typography'),
					'scheme' => SchemeTypography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .elementor-categories-subcategories li a',
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'count_typography',
					'label' => __('Count product typography'),
					'scheme' => SchemeTypography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .elementor-categories-count',
				]
			);
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' => 'button_typography',
					'label' => __('View button typography'),
					'scheme' => SchemeTypography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .elementor-categories-link',
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

		$id_lang = \Context::getContext()->language->id;
		$slides = [];

		$categories = array();
		foreach($settings['category_list'] as $cate_select){
			$category = new \Category($cate_select['category'], $id_lang);
			$categories[] = array(
				'name' => $category->name,
				'image' => $cate_select['image'],
				'count_product' => $settings['show_count'] ? $category->getProducts(null, null, null, null, null, true) : '',
				'subcategories' => $settings['show_subcategories'] ? $category->getSubCategories($id_lang , true) : [],
				'link' => $settings['show_link'] ? $category->getLink() : ''
			);
		}

		foreach ($categories as $category) :
			ob_start();
			?>
			<div class="elementor-categories-item layout-<?= $settings['layout'] ?>">
			<?php if($settings['layout'] == '1') : ?>
				<!-- Layout 1 -->
				<div class="elementor-categories-image">
					<a href="<?= $category['link'] ?>">
						<?= GroupControlImageSize::getAttachmentImageHtml($category, 'image', 'auto') ?>
					</a>
				</div>
				<div class="elementor-categories-content">
					<a class="elementor-categories-name" href="<?= $category['link'] ?>}"><?= $category['name'] ?></a>
					<?php if($category['count_product'] != '') : ?>
						<p class="elementor-categories-count"><?= $category['count_product'] ?>&nbsp;<?= __('Products') ?></p>
					<?php endif; ?>
					<?php if(count($category['subcategories']) > 0) : 
						$limit = 99;
						if((int)$settings['limit_subcategories']) $limit = (int)$settings['limit_subcategories'];
					?>
						<ul class="elementor-categories-subcategories">
						<?php foreach($category['subcategories'] as $key => $subcategory) : 
							if($key >= $limit) break;
							$sub = new \Category( $subcategory['id_category'] , $id_lang );
						?>
							<li><a href="<?= $sub->getLink() ?>"><?= $subcategory['name'] ?></a></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if($category['link'] != '') : ?>
						<a class="elementor-categories-link" href="<?= $category['link'] ?>"><?= __('View more') ?></a>
					<?php endif; ?>
				</div>
			<?php elseif($settings['layout'] == '2') : ?> 
				<!-- Layout 2 -->
				<div class="elementor-categories-image">
					<a href="<?= $category['link'] ?>">
						<?= GroupControlImageSize::getAttachmentImageHtml($category, 'image', 'auto') ?>
					</a>
				</div>
				<div class="elementor-categories-content">
					<a class="elementor-categories-name" href="<?= $category['link'] ?>}"><?= $category['name'] ?></a>
					<?php if($category['count_product'] != '') : ?>
						<p class="elementor-categories-count"><?= $category['count_product'] ?>&nbsp;<?= __('Products') ?></p>
					<?php endif; ?>
					<?php if(count($category['subcategories']) > 0) : 
						$limit = 99;
						if((int)$settings['limit_subcategories']) $limit = (int)$settings['limit_subcategories'];
					?>
						<ul class="elementor-categories-subcategories">
						<?php foreach($category['subcategories'] as $key => $subcategory) : 
							if($key >= $limit) break;
							$sub = new \Category( $subcategory['id_category'] , $id_lang );
						?>
							<li><a href="<?= $sub->getLink() ?>"><?= $subcategory['name'] ?></a></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if($category['link'] != '') : ?>
						<a class="elementor-categories-link" href="<?= $category['link'] ?>"><?= __('View more') ?></a>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<!-- Layout 3 -->
				<div class="elementor-categories-image">
					<a href="<?= $category['link'] ?>">
						<?= GroupControlImageSize::getAttachmentImageHtml($category, 'image', 'auto') ?>
					</a>
				</div>
				<div class="elementor-categories-content">
					<a class="elementor-categories-name" href="<?= $category['link'] ?>}"><?= $category['name'] ?></a>
					<?php if($category['count_product'] != '') : ?>
						<p class="elementor-categories-count"><?= $category['count_product'] ?>&nbsp;<?= __('Products') ?></p>
					<?php endif; ?>
					<?php if(count($category['subcategories']) > 0) : 
						$limit = 99;
						if((int)$settings['limit_subcategories']) $limit = (int)$settings['limit_subcategories'];
					?>
						<ul class="elementor-categories-subcategories">
						<?php foreach($category['subcategories'] as $key => $subcategory) : 
							if($key >= $limit) break;
							$sub = new \Category( $subcategory['id_category'] , $id_lang );
						?>
							<li><a href="<?= $sub->getLink() ?>"><?= $subcategory['name'] ?></a></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if($category['link'] != '') : ?>
						<a class="elementor-categories-link" href="<?= $category['link'] ?>"><?= __('View more') ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			</div>
			<?php
			$slides[] = ob_get_clean();
		endforeach;
		$this->renderCarousel($settings, $slides);
	} 

}