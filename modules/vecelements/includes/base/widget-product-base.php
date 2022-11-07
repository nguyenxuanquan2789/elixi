<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

abstract class WidgetProductBase extends WidgetBase
{
    protected $context;

    protected $catalog;

    protected $show_prices;

    protected $parentTheme;

    protected $imageSize;

    protected $currency;

    protected $usetax;

    protected $noImage;

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();
        $this->catalog = \Configuration::get('PS_CATALOG_MODE');
        $this->show_prices = !\Configuration::isCatalogMode();
        $this->parentTheme = !empty($this->context->shop->theme) ? $this->context->shop->theme->get('parent') : '';
        $this->imageSize = \ImageType::{'getFormattedName'}('home');
        $this->loading = stripos($this->getName(), 'carousel') === false ? 'lazy' : 'auto';

        if ($this->context->controller instanceof \AdminController) {
            isset($this->context->customer->id) or $this->context->customer = new \Customer();
        } else {
            if (!$this->catalog) {
                $imageRetriever = new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link);
                $this->noImage = method_exists($imageRetriever, 'getNoPictureImage') ? $imageRetriever->getNoPictureImage($this->context->language) : null;
            }
        }
        parent::__construct($data, $args);
    }

    protected function getVecthemeOptions(){
        $prefix_name = 'vecthemeoptions';
        $options = array(
            'header_sticky'                 => \Configuration::get($prefix_name . 'header_sticky'),
            //Product grid
			'grid_type'                     => isset($_GET['grid_type']) ? $_GET['grid_type'] : \Configuration::get($prefix_name . 'grid_type'),
			'rotator'                       => \Configuration::get($prefix_name . 'second_img'),
			'name_length'                   => (\Configuration::get($prefix_name . 'grid_name_length') == 'cut' && \Configuration::get($prefix_name . 'grid_name_cut') > 0) ? (\Configuration::get($prefix_name . 'grid_name_cut')) : 128,
            //Page title
            'ptitle_size'                   => \Configuration::get($prefix_name . 'ptitle_size'),
            //Category page
            'category_layout'               => isset($_GET['category_layout']) ? $_GET['category_layout'] : \Configuration::get($prefix_name . 'category_layout'),
            'category_thumbnail'            => isset($_GET['category_thumbnail']) ? $_GET['category_thumbnail'] : \Configuration::get($prefix_name . 'category_thumbnail'),
            'category_description'          => isset($_GET['category_description']) ? $_GET['category_description'] : \Configuration::get($prefix_name . 'category_description'),
            'category_description_bottom'   => isset($_GET['category_description_bottom']) ? $_GET['category_description_bottom'] : \Configuration::get($prefix_name . 'category_description_bottom'),
            'category_sub'                  => isset($_GET['category_sub']) ? $_GET['category_sub'] :\Configuration::get($prefix_name . 'category_sub'),
            'category_pagination'           => isset($_GET['category_pagination']) ? $_GET['category_pagination'] :\Configuration::get($prefix_name . 'category_pagination'),
            'category_filter'               => isset($_GET['category_filter']) ? $_GET['category_filter'] :\Configuration::get($prefix_name . 'category_filter'),
            'category_column'               => isset($_GET['column']) ? $_GET['column'] :\Configuration::get($prefix_name . 'category_column'),
            //Product page
            'product_layout'                => isset($_GET['product_layout']) ? $_GET['product_layout'] :\Configuration::get($prefix_name . 'product_layout'),
            'main_layout'                   => isset($_GET['product_main']) ? $_GET['product_main'] :\Configuration::get($prefix_name . 'main_layout'),
            'product_image'                 => isset($_GET['product_image']) ? $_GET['product_image'] :\Configuration::get($prefix_name . 'product_image'),
            'information_layout'            => isset($_GET['product_infor']) ? $_GET['product_infor'] :\Configuration::get($prefix_name . 'information_layout'),

            'zoom_active'                   =>\Configuration::get($prefix_name . 'zoom'),
            'thumbnail_items'               => (int)\Configuration::get($prefix_name . 'thumbnail_items') ?\Configuration::get($prefix_name . 'thumbnail_items'): 4,
            //404 page
            '404_content'                   =>\Configuration::get($prefix_name . '404_content'),
            '404_image'                     =>\Configuration::get($prefix_name . '404_image') ?\Configuration::get($prefix_name . '404_image') : '',
            '404_text1'                     =>\Configuration::get($prefix_name . '404_text1', $this->context->language->id) ?\Configuration::get($prefix_name . '404_text1', $this->context->language->id) : '',
            '404_text2'                     =>\Configuration::get($prefix_name . '404_text2', $this->context->language->id) ?\Configuration::get($prefix_name . '404_text2', $this->context->language->id) : '',
		);
        if (isset($this->context->cookie->shop_view)) {
            $options['shop_view'] = $this->context->cookie->shop_view;
        }
        //echo '<pre>'; print_r($options);die;
        return $options;
    }

    public function getCategories()
    {
        return ['premium'];
    }

    protected function getSkinOptions()
    {
        $opts = [];

        $pattern = 'templates/catalog/_partials/miniatures/*product*.tpl';
        $tpls = $this->parentTheme ? glob(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/$pattern") : [];
        $tpls += glob(_PS_THEME_DIR_ . $pattern);

        foreach ($tpls as $tpl) {
            $opt = basename($tpl, '.tpl');
            $opts[$opt] = 'product' === $opt ? __('Default') : \Tools::ucFirst($opt);
        }
        unset($opts['pack-product']);
    
        $opts['custom'] = __('Custom');

        return $opts;
    }

    protected function getListingOptions()
    {
        $opts = [
            'category' => __('Featured Products'),
            'prices-drop' => __('Prices Drop'),
            'new-products' => __('New Products'),
        ];
        if (!$this->catalog) {
            $opts['best-sales'] = __('Best Sales');
        }
        $opts['products'] = __('Custom Products');

        return $opts;
    }

    protected function getCategoryOptions()
    {
        $categories = [];

        if (is_admin()) {
            foreach (\Category::getSimpleCategories($this->context->language->id) as &$cat) {
                $categories[$cat['id_category']] = "#{$cat['id_category']} {$cat['name']}";
            }
        }
        return $categories;
    }

    protected function getAjaxProductsListUrl()
    {
        if (version_compare(_PS_VERSION_, '1.7.6', '<')) {
            $url = 'ajax_products_list.php?';
            $args = [];
        } else {
            $url = 'index.php?';
            $args = [
                'controller' => 'AdminProducts',
                'token' => \Tools::getAdminTokenLite('AdminProducts'),
                'ajax' => 1,
                'action' => 'productsList',
            ];
        }
        return $url . http_build_query($args + [
            'forceJson' => 1,
            'disableCombination' => 1,
            'excludeVirtuals' => 0,
            'exclude_packs' => 0,
            'limit' => 20,
        ]);
    }

    protected function registerListingControls($limit = 'limit')
    {
        $this->addControl(
            'listing',
            [
                'label' => __('Listing'),
                'type' => ControlsManager::SELECT,
                'default' => 'category',
                'options' => $this->getListingOptions(),
                'separator' => 'before',
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
                    'listing' => 'products',
                ],
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
                    'listing' => 'category',
                ],
            ]
        );

        $this->addControl(
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

        $this->addControl(
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

        $this->addControl(
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

        $this->addControl(
            $limit,
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
    }

    protected function getImageSizeOptions()
    {
        $opts = [];
        $sizes = \ImageType::getImagesTypes('products');

        foreach ($sizes as &$size) {
            $opts[$size['name']] = "{$size['name']} - {$size['width']} x {$size['height']}";
        }
        if (empty($opts[$this->imageSize])) {
            // set first image size as default when home doesn't exists
            $this->imageSize = key($opts);
        }
        return $opts;
    }

    protected function registerMiniatureSections()
    {
        $this->startControlsSection(
            'section_content',
            [
                'label' => __('Content'),
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addResponsiveControl(
            'image_size',
            [
                'label' => __('Image Size'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getImageSizeOptions(),
                'default' => $this->imageSize,
            ]
        );

        $this->addControl(
            'show_second_image',
            [
                'label' => __('Second Image'),
                'description' => __('Show second image on hover if exists'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'show_category',
            [
                'label' => __('Category'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'show_description',
            [
                'label' => __('Description'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
            ]
        );

        $this->addControl(
            'description_length',
            [
                'label' => __('Max. Length'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'condition' => [
                    'show_description!' => '',
                ],
            ]
        );

        $this->addControl(
            'show_regular_price',
            [
                'label' => __('Regular Price'),
                'type' => $this->catalog ? ControlsManager::HIDDEN : ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_atc',
            [
                'label' => __('Add to Cart'),
                'type' => $this->catalog ? ControlsManager::HIDDEN : ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_qv',
            [
                'label' => __('Quick View'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Show'),
                'label_off' => __('Hide'),
                'default' => 'yes',
            ]
        );

        $this->addControl(
            'show_badges',
            [
                'label' => __('Badges'),
                'type' => ControlsManager::SELECT2,
                'options' => [
                    'sale' => __('Sale'),
                    'new' => __('New'),
                    'pack' => __('Pack'),
                ],
                'default' => ['sale', 'new', 'pack'],
                'label_block' => true,
                'multiple' => true,
            ]
        );

        $this->addControl(
            'badge_sale_text',
            [
                'label' => __('Sale Text'),
                'type' => ControlsManager::TEXT,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'sale',
                        ],
                    ],
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_atc',
            [
                'label' => __('Add to Cart'),
                'condition' => [
                    'skin' => 'custom',
                    'show_atc' => $this->catalog ? 'hide' : 'yes',
                ],
            ]
        );

        $this->addControl(
            'atc_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Add to Cart'),
            ]
        );

        $this->addControl(
            'atc_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
            ]
        );

        $this->addControl(
            'atc_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'atc_icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'atc_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'atc_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-atc .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_qv',
            [
                'label' => __('Quick View'),
                'condition' => [
                    'skin' => 'custom',
                    'show_qv!' => '',
                ],
            ]
        );

        $this->addControl(
            'qv_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Quick View'),
            ]
        );

        $this->addControl(
            'qv_icon',
            [
                'label' => __('Icon'),
                'type' => ControlsManager::ICON,
                'label_block' => false,
                'default' => '',
            ]
        );

        $this->addControl(
            'qv_icon_align',
            [
                'label' => __('Icon Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before'),
                    'right' => __('After'),
                ],
                'condition' => [
                    'qv_icon!' => '',
                ],
            ]
        );

        $this->addControl(
            'qv_icon_indent',
            [
                'label' => __('Icon Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'qv_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-quick-view .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function registerMiniatureStyleSections()
    {
        $this->startControlsSection(
            'section_style_image',
            [
                'label' => __('Image'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'hover_animation',
            [
                'label' => __('Hover Animation'),
                'type' => ControlsManager::SELECT,
                'options' => [
                    '' => __('None'),
                    'grow' => __('Grow'),
                    'shrink' => __('Shrink'),
                    'rotate' => __('Rotate'),
                    'grow-rotate' => __('Grow Rotate'),
                    'float' => __('Float'),
                    'sink' => __('Sink'),
                    'bob' => __('Bob'),
                    'hang' => __('Hang'),
                    'buzz-out' => __('Buzz Out'),
                ],
                'prefix_class' => 'elementor-img-hover-',
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'image_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .elementor-image',
            ]
        );

        $this->addControl(
            'image_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            [
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addControl(
            'content_align',
            [
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
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
                    '{{WRAPPER}} .elementor-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'content_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'content_min_height',
            [
                'label' => __('Min Height'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-content' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
            ]
        );

        $this->startControlsTabs('content_style_tabs');

        $this->startControlsTab(
            'content_style_title',
            [
                'label' => __('Title'),
            ]
        );

        $this->addControl(
            'title_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'title_multiline',
            [
                'label' => __('Allow Multiline'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes'),
                'label_off' => __('No'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-title' => 'overflow: visible; white-space: normal;',
                ],
            ]
        );

        $this->addControl(
            'title_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'title_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-title',

            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_category',
            [
                'label' => __('Category'),
                'condition' => [
                    'show_category!' => '',
                ],
            ]
        );

        $this->addControl(
            'category_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-category' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'category_multiline',
            [
                'label' => __('Allow Multiline'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes'),
                'label_off' => __('No'),
                'selectors' => [
                    '{{WRAPPER}} .elementor-category' => 'overflow: visible; white-space: normal;',
                ],
            ]
        );

        $this->addControl(
            'category_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'category_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-category',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_description',
            [
                'label' => __('Description'),
                'condition' => [
                    'show_description!' => '',
                ],
            ]
        );

        $this->addControl(
            'description_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-description' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'description_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'description_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-description',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'content_style_price',
            [
                'label' => __('Price'),
            ]
        );

        $this->addControl(
            'price_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'price_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'price_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-price-wrapper',
            ]
        );

        $this->addControl(
            'heading_style_regular_price',
            [
                'label' => __('Regular Price'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ],
            ]
        );

        $this->addControl(
            'regular_price_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-regular' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ],
            ]
        );

        $this->addResponsiveControl(
            'regular_price_size',
            [
                'label' => _x('Size', 'Typography Control'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-regular' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_regular_price' => $this->catalog ? 'hide' : 'yes',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_atc',
            [
                'label' => __('Add to Cart'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'custom',
                    'show_atc' => $this->catalog ? 'hide' : 'yes',
                ],
            ]
        );

        $this->addControl(
            'atc_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'atc_align',
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
                    'justify' => [
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor-atc--align-',
                'default' => 'justify',
            ]
        );

        $this->addControl(
            'atc_size',
            [
                'label' => __('Size'),
                'type' => ControlsManager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => __('Extra Small'),
                    'sm' => __('Small'),
                    'md' => __('Medium'),
                    'lg' => __('Large'),
                    'xl' => __('Extra Large'),
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'atc_typography',
                'label' => __('Typography'),
                'selector' => '{{WRAPPER}} .elementor-atc .elementor-button',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            ]
        );

        $this->startControlsTabs('atc_style_tabs');

        $this->startControlsTab(
            'atc_style_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'atc_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'atc_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'background-color: {{VALUE}};',
                ],
                'default' => '#000',
            ]
        );

        $this->addControl(
            'atc_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'atc_style_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'atc_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'atc_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'atc_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'atc_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'atc_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-atc .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_qv',
            [
                'label' => __('Quick View'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'show_qv' => 'yes',
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'qv_typography',
                'label' => __('Typography'),
                'selector' => '{{WRAPPER}} .elementor-quick-view',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
            ]
        );

        $this->startControlsTabs('qv_style_tabs');

        $this->startControlsTab(
            'qv_style_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'qv_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'qv_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'qv_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'qv_style_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'qv_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'qv_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'qv_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addControl(
            'qv_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
                'separator' => 'before',
            ]
        );

        $this->addControl(
            'qv_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-quick-view' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_badges',
            [
                'label' => __('Badges'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'skin',
                            'value' => 'custom',
                        ],
                        [
                            'name' => 'show_badges[0]',
                            'operator' => 'in',
                            'value' => ['sale', 'new', 'pack'],
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badges_top',
            [
                'label' => __('Top Distance'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => -2,
                        'max' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badges-left' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-badges-right' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'badges_left',
            [
                'label' => __('Left Distance'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => -2,
                        'max' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badges-left' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'badge_sale_position',
                            'value' => 'left',
                        ],
                        [
                            'name' => 'badge_new_position',
                            'value' => 'left',
                        ],
                        [
                            'name' => 'badge_pack_position',
                            'value' => 'left',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badges_right',
            [
                'label' => __('Right Distance'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => -20,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => -2,
                        'max' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badges-right' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'badge_sale_position',
                            'value' => 'right',
                        ],
                        [
                            'name' => 'badge_new_position',
                            'value' => 'right',
                        ],
                        [
                            'name' => 'badge_pack_position',
                            'value' => 'right',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'bagdes_spacing',
            [
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_badges[1]',
                            'operator' => 'in',
                            'value' => ['new', 'pack'],
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badges_min_width',
            [
                'label' => __('Min Width'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
            ]
        );

        $this->addControl(
            'badges_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addControl(
            'badges_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'badges_typography',
                'selector' => '{{WRAPPER}} .elementor-badge',
            ]
        );

        $this->startControlsTabs('badge_style_tabs');

        $this->startControlsTab(
            'badge_style_sale',
            [
                'label' => __('Sale'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'sale',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badge_sale_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left'),
                    ],
                    'right' => [
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right'),
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->addControl(
            'badge_sale_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-sale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'badge_sale_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-sale' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'badge_style_new',
            [
                'label' => __('New'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'new',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badge_new_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left'),
                    ],
                    'right' => [
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right'),
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->addControl(
            'badge_new_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-new' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'badge_new_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-new' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'badge_style_pack',
            [
                'label' => __('Pack'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_badges',
                            'operator' => 'contains',
                            'value' => 'pack',
                        ],
                    ],
                ],
            ]
        );

        $this->addControl(
            'badge_pack_position',
            [
                'label' => __('Position'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'icon' => 'eicon-h-align-left',
                        'title' => __('Left'),
                    ],
                    'right' => [
                        'icon' => 'eicon-h-align-right',
                        'title' => __('Right'),
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->addControl(
            'badge_pack_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-pack' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'badge_pack_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-badge-pack' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();
    }

    public function onImport($widget)
    {
        static $id_product;

        if (null === $id_product) {
            $products = \Product::getProducts(\Context::getContext()->language->id, 0, 1, 'id_product', 'ASC', false, true);
            $id_product = !empty($products[0]['id_product']) ? $products[0]['id_product'] : '';
        }

        // Check Category ID
        if (!empty($widget['settings']['category_id'])) {
            $category = new \Category($widget['settings']['category_id']);

            if (!$category->id) {
                $widget['settings']['category_id'] = \Context::getContext()->shop->id_category;
            }
        }

        // Check Product ID
        if (!empty($widget['settings']['product_id'])) {
            $product = new \Product($widget['settings']['product_id']);

            if (!$product->id) {
                $widget['settings']['product_id'] = $id_product;
            }
        }

        // Check Product IDs
        if (!empty($widget['settings']['products'])) {
            $table = _DB_PREFIX_ . 'product';
            $prods = [];
            $ids = [];

            foreach ($widget['settings']['products'] as &$prod) {
                $ids[] = (int) $prod['id'];
            }
            $ids = implode(', ', $ids);
            $rows = \Db::getInstance()->executeS("SELECT id_product FROM $table WHERE id_product IN ($ids)");

            foreach ($rows as &$row) {
                $prods[$row['id_product']] = true;
            }

            foreach ($widget['settings']['products'] as &$prod) {
                if ($prod['id'] && empty($prods[$prod['id']])) {
                    $prod['id'] = $id_product;
                }
            }
        }
        return $widget;
    }

    protected function getProduct($id)
    {
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $assembler = new \ProductAssembler($this->context);
        $result = ['id_product' => $id];

        try {
            if (!$assembledProduct = $assembler->assembleProduct($result)) {
                return false;
            }
            return $presenter->present(
                $presenterFactory->getPresentationSettings(),
                $assembledProduct,
                $this->context->language
            );
        } catch (\Exception $ex) {
            return false;
        }
    }

    protected function getProducts($listing, $order_by, $order_dir, $limit, $id_category = 2, $products = [])
    {
        $tpls = [];

        if ('products' === $listing) {
            // Custom Products
            if ('rand' === $order_by) {
                shuffle($products);
            }
            foreach ($products as &$product) {
                if ($product['id']) {
                    $tpls[] = $this->getProduct($product['id']);
                }
            }
            return $tpls;
        }

        $translator = $this->context->getTranslator();
        $query = new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery();
        $query->setResultsPerPage($limit <= 0 ? 8 : (int) $limit);
        $query->setQueryType($listing);

        switch ($listing) {
            case 'category':
                $category = new \Category((int) $id_category);
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider($translator, $category);
                $query->setSortOrder(
                    'rand' == $order_by
                    ? \PrestaShop\PrestaShop\Core\Product\Search\SortOrder::random()
                    : new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir)
                );
                break;
            case 'prices-drop':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'new-products':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'best-sales':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
        }
        $result = $searchProvider->runQuery(new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext($this->context), $query);

        $assembler = new \ProductAssembler($this->context);
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $translator
        );

        foreach ($result->getProducts() as $rawProduct) {
            $tpls[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $tpls;
    }

    protected function getSaleProducts($source, $order_by, $order_dir, $limit, $id_category = 2, $products = [])
    {
        $tpls = [];
        $limit = $limit ? $limit : 4;
		$orderby = $order_by;
		$orderway = $order_dir;

		$id_lang = $this->context->language->id;

		$front   = true;
		if ( ! in_array( $this->context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}
        $translator = $this->context->getTranslator();

		switch ($source) {
			case 'all_products':
                $translator = $this->context->getTranslator();
                $query = new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery();
                $query->setResultsPerPage($limit <= 0 ? 8 : (int) $limit);
                $query->setQueryType('prices-drop');

                $searchProvider = new \PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));

                $result = $searchProvider->runQuery(new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext($this->context), $query);
				$products = $result->getProducts();
				break;
            case 'cate_products':
                $products = $this->getSaleProductInCategory((int) $this->context->language->id, 0, $limit, $orderby , $orderway, false, false, $id_category);	
                break;
			case 'select_products':
				foreach ($products as &$product) {
                    if ($product['id']) {
                        $tpls[] = $this->getProduct($product['id']);
                    }
                }	
                return $tpls;
				break;
		}

		$assembler = new \ProductAssembler($this->context);
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $translator
        );
        $tpls = array();
        if(!empty($products))
        foreach ($products as $rawProduct) {
            $tpls[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $tpls;
    }

    /**
     * Use this method to return the result of a product miniature template.
     *
     * @since 1.0.0
     * @access protected
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     *
     * @param array $settings
     * @param $product
     *
     * @return string
     */
    protected function fetchMiniature(array &$settings, $product)
    {
        $article = 'article-' . $product['id_product'];
        $image_size = !empty($settings['image_size']) ? $settings['image_size'] : $this->imageSize;
        $show_atc = $this->show_prices && !empty($settings['show_atc']);
        $min_qty = empty($product['product_attribute_minimal_quantity']) ? $product['minimal_quantity'] : $product['product_attribute_minimal_quantity'];
        $badges = [];

        
        $link = $product['url'];
        $cover = !empty($product['cover']) || !$this->noImage ? $product['cover'] : $this->noImage;
        $cover_url = [
            'desktop' => $cover['bySize'][$image_size]['url'],
        ];
        if (!empty($settings['image_size_tablet']) && $settings['image_size_tablet'] != $image_size) {
            $cover_url['tablet'] = $cover['bySize'][$settings['image_size_tablet']]['url'];
        }
        if (!empty($settings['image_size_mobile']) && $settings['image_size_mobile'] != $settings['image_size_tablet']) {
            $cover_url['mobile'] = $cover['bySize'][$settings['image_size_mobile']]['url'];
        }
        $cover_alt = !empty($product['cover']['legend']) ? $product['cover']['legend'] : $product['name'];
        $cover_size = $cover['bySize'][$image_size];
        $atc_url = $product['add_to_cart_url'];
        $on_sale = !empty($product['has_discount']);
        $regular_price = $product['regular_price'];

        if ($on_sale && in_array('sale', $settings['show_badges'])) {
            $badges['sale'] = !empty($settings['badge_sale_text'])
            ? $settings['badge_sale_text']
            : $product['percentage' === $product['discount_type'] ? 'discount_percentage' : 'discount_amount_to_display']
            ;
        }
        if (!empty($product['flags']['new']['label']) && in_array('new', $settings['show_badges'])) {
            $badges['new'] = $product['flags']['new']['label'];
        }
        if (!empty($product['flags']['pack']['label']) && in_array('pack', $settings['show_badges'])) {
            $badges['pack'] = $product['flags']['pack']['label'];
        }
        

        if ($show_atc && empty($atc_url)) {
            $args = [
                'add' => 1,
                'id_product' => (int) $product['id_product'],
                'ipa' => (int) $product['id_product_attribute'],
                'token' => \Tools::getToken(false),
            ];
            $atc_url = $this->context->link->getPageLink('cart', true, null, $args);
        }

        if (!empty($settings['show_description'])) {
            $description = strip_tags($product['description_short']);

            if (!empty($settings['description_length']) && \Tools::strlen($description) > $settings['description_length']) {
                $description = rtrim(\Tools::substr($description, 0, \Tools::strpos($description, ' ', $settings['description_length'])), '-,.') . '...';
            }
        }
        $this->addRenderAttribute($article, [
            'data-id-product' => $product['id_product'],
            'data-id-product-attribute' => $product['id_product_attribute'],
        ]);

        ob_start();
        ?>
        <article class="elementor-product-miniature js-product-miniature" <?= $this->getRenderAttributeString($article) ?>>
            <a class="elementor-product-link" href="<?= esc_attr($link) ?>">
                <div class="elementor-image">
                    <picture class="elementor-cover-image">
                        <?php if (isset($cover_url['mobile'])) : ?>
                            <source media="(max-width: 767px)" srcset="<?= esc_attr($cover_url['mobile']) ?>">
                        <?php endif ?>
                        <?php if (isset($cover_url['tablet'])) : ?>
                            <source media="(max-width: 991px)" srcset="<?= esc_attr($cover_url['tablet']) ?>">
                        <?php endif ?>
                        <img src="<?= esc_attr($cover_url['desktop']) ?>" loading="<?= $this->loading ?>" alt="<?= esc_attr($cover_alt) ?>"
                            width="<?= (int) $cover_size['width'] ?>" height="<?= (int) $cover_size['height'] ?>">
                    </picture>
                    <?php
                    if (!empty($settings['show_second_image']) && !empty($product['images'])) :
                        foreach ($product['images'] as $image) :
                            if ($image['id_image'] != $cover['id_image']) :
                                ?>
                                <picture class="elementor-second-image">
                                    <?php if (isset($cover_url['mobile'])) : ?>
                                        <source media="(max-width: 767px)" srcset="<?= esc_attr($image['bySize'][$settings['image_size_mobile']]['url']) ?>">
                                    <?php endif ?>
                                    <?php if (isset($cover_url['tablet'])) : ?>
                                        <source media="(max-width: 991px)" srcset="<?= esc_attr($image['bySize'][$settings['image_size_tablet']]['url']) ?>">
                                    <?php endif ?>
                                    <img src="<?= esc_attr($image['bySize'][$image_size]['url']) ?>" loading="lazy" alt="<?= esc_attr($image['legend']) ?>"
                                        width="<?= (int) $image['bySize'][$image_size]['width'] ?>" height="<?= (int) $image['bySize'][$image_size]['height'] ?>">
                                </picture>
                                <?php
                                break;
                            endif;
                        endforeach;
                    endif;
                    ?>
                    <?php if (!empty($settings['show_qv'])) : ?>
                        <div class="elementor-button elementor-quick-view" data-link-action="quickview">
                            <div class="elementor-button-inner">
                                <span class="elementor-button-icon elementor-align-icon-<?= $settings['qv_icon_align'] ?>">
                                    <i class="<?= esc_attr($settings['qv_icon']) ?>"></i>
                                </span>
                                <span class="elementor-button-text"><?= $settings['qv_text'] ?></span>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <?php foreach (['left', 'right'] as $position) : ?>
                    <div class="elementor-badges-<?= $position ?>">
                    <?php foreach ($badges as $badge => $label) : ?>
                        <?php if ($position == $settings["badge_{$badge}_position"]) : ?>
                            <div class="elementor-badge elementor-badge-<?= $badge ?>"><?= $label ?></div>
                        <?php endif ?>
                    <?php endforeach ?>
                    </div>
                <?php endforeach ?>
                <div class="elementor-content">
                    <?php if (!empty($settings['show_category'])) : ?>
                        <h4 class="elementor-category"><?= $product['category_' . ('name')] ?></h4>
                    <?php endif ?>
                    <h3 class="elementor-title"><?= $product['name'] ?></h3>
                    <?php if (!empty($description)) : ?>
                        <div class="elementor-description"><?= $description ?></div>
                    <?php endif ?>
                    <?php if ($this->show_prices && $product['show_price']) : ?>
                        <div class="elementor-price-wrapper">
                            <?php if ($on_sale && !empty($settings['show_regular_price'])) : ?>
                                <span class="elementor-price-regular"><?= $regular_price ?></span>
                            <?php endif ?>
                            <span class="elementor-price"><?= $product['price'] ?></span>
                        </div>
                    <?php endif ?>
                </div>
            </a>
            <?php if ($show_atc) : ?>
                <form class="elementor-atc" action="<?= esc_attr($atc_url) ?>">
                    <input type="hidden" name="qty" value="<?= (int) $min_qty ?>">
                    <button type="submit" class="elementor-button elementor-size-<?= $settings['atc_size'] ?>" data-button-action="add-to-cart">
                        <?php if (!empty($settings['atc_icon'])) : ?>
                            <span class="elementor-atc-icon elementor-align-icon-<?= $settings['atc_icon_align'] ?>">
                                <i class="<?= $settings['atc_icon'] ?>"></i>
                            </span>
                        <?php endif ?>
                        <span class="elementor-button-text"><?= $settings['atc_text'] ?></span>
                    </button>
                </form>
            <?php endif ?>
        </article>
        <?php
        return ob_get_clean();
    }

    protected function _addRenderAttributes()
    {
        parent::_addRenderAttributes();

        if ($this->getSettings('skin') != 'custom') {
            if ($wrapfix = Helper::getWrapfix()) {
                $this->addRenderAttribute('_wrapper', 'class', $wrapfix);
            }
        }
    }

    public function renderPlainContent()
    {
    }

    public function getSaleProductInCategory(
        $id_lang,
        $page_number = 0,
        $nb_products = 10,
        $order_by = null,
        $order_way = null,
        $beginning = false,
        $ending = false,
        $id_category = 2
    ) {
        
        if ($page_number < 0) {
            $page_number = 0;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!\Validate::isOrderBy($order_by) || !\Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }

        $current_date = date('Y-m-d H:i:00');
        $ids_product = $this->getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending));
        $tab_id_product = array();
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int)$product['id_product'];
            } else {
                $tab_id_product[] = (int)$product;
            }
        }
		
        $front = true;
        if (!in_array($this->context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql_groups = '';
        if (\Group::isFeatureActive()) {
            $groups = \FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `'._DB_PREFIX_.'category_product` cp
				JOIN `'._DB_PREFIX_.'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` '.(count($groups) ? 'IN ('.implode(',', $groups).')' : '= 1').')
				WHERE cp.`id_product` = p.`id_product`)';
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]).'.`'.pSQL($order_by[1]).'`';
        }

        $sql = '
		SELECT
			p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
			IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
			pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
			pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
			DATEDIFF(
				p.`date_add`,
				DATE_SUB(
					"'.date('Y-m-d').' 00:00:00",
					INTERVAL '.(\Validate::isUnsignedInt(\Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? \Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY
				)
			) > 0 AS new
		FROM `'._DB_PREFIX_.'product` p
		'.\Shop::addSqlAssociation('product', 'p').'
		LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop
			ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')
		'.\Product::sqlStock('p', 0, false, $this->context->shop).'
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
			p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = '.(int)$id_lang.\Shop::addSqlRestrictionOnLang('pl').'
		)
		LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.(int)$this->context->shop->id.')
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')
		LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
		LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (p.`id_product` = cp.`id_product`)
		WHERE product_shop.`active` = 1
		AND cp.`id_category` = '.(int)$id_category.'
        
		AND product_shop.`show_price` = 1
		'.($front ? ' AND p.`visibility` IN ("both", "catalog")' : '').'
        '.((!$beginning && !$ending) ? ' AND p.`id_product` IN ('.((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0).')' : '').'
		'.$sql_groups.'
		ORDER BY '.(isset($order_by_prefix) ? pSQL($order_by_prefix).'.' : '').pSQL($order_by).' '.pSQL($order_way).'
		LIMIT '.(int)($page_number * $nb_products).', '.(int)$nb_products;

        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            \Tools::orderbyPrice($result, $order_way);
        }

        return \Product::getProductsProperties($id_lang, $result);
    }

    public function getProductIdByDate($beginning, $ending)
    {

        $id_address = $this->context->cart->{\Configuration::get('PS_TAX_ADDRESS_TYPE')};
        $ids = \Address::getCountryAndState($id_address);
        $id_country = isset($ids['id_country']) ? (int)$ids['id_country'] : (int)\Configuration::get('PS_COUNTRY_DEFAULT');

        return \SpecificPrice::getProductIdByDate(
            $this->context->shop->id,
            $this->context->currency->id,
            $id_country,
            $this->context->customer->id_default_group,
            $beginning,
            $ending,
            0,
            false
        );
    }
}
