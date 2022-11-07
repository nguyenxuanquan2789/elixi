<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetProductCarousel extends WidgetProductBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'product-carousel';
    }

    public function getTitle()
    {
        return __('Products');
    }

    public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

    public function getKeywords()
    {
        return ['product', 'carousel', 'slider'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            [
                'label' => __('Product Carousel'),
            ]
        );

        $this->registerListingControls();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_product_layout',
            [
                'label' => __('Product layout'),
            ]
        );
            $this->addControl(
                'enable_slider',
                [
                    'label' => __('Use carousel'),
                    'type' => ControlsManager::SWITCHER,
                    'default' => 'yes',
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
            $this->addControl(
                'product_display',
                [
                    'label' => __('Product display'),
                    'type' => ControlsManager::SELECT,
                    'options' => [
                        '0' => 'Default',
                        'grid1' => 'Style 1',
                        'grid2' => 'Style 2',
                        'grid3' => 'Style 3',
                        'grid4' => 'Style 4',
                        'grid5' => 'Style 5',
                        'list' => 'list'
                    ],
                    'default' => '0',
                    'separator' => 'before',
                    'description' => __('Default: use setting from theme options module.'),
                ]
            );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'default_slides_desktop' => 4,
            'default_slides_tablet' => 3,
            'default_slides_mobile' => 2,
        ]);

        $this->registerNavigationStyleSection();

        //$this->registerCarouselArrowsConfig();
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }
        if (empty($this->context->currency->id)) {
            return;
        }

        $out_put = '';

        $settings = $this->getSettingsForDisplay();

        if ($settings['randomize'] && ('category' === $settings['listing'] || 'products' === $settings['listing'])) {
            $settings['order_by'] = 'rand';
        }

        $products = $this->getProducts(
            $settings['listing'],
            $settings['order_by'],
            $settings['order_dir'],
            $settings['limit'],
            $settings['category_id'],
            $settings['products']
        );

        if (empty($products)) {
            return;
        }

        $slides = [];

        // Theme Skin PS 1.7+
        $grid_type = 'grid1';
        if($settings['product_display']){
            $grid_type = $settings['product_display'];
        }else{
            $option_product = \Configuration::get('vecthemeoptionsgrid_type');
            if($option_product){
                $grid_type = 'grid'. $option_product;
            }
        }

        $tpl = 'catalog/_partials/miniatures/_product/'. $grid_type .'.tpl';

        if(!isset($vectheme)){
            $this->context->smarty->assign('vectheme', $this->getVecthemeOptions());
        }
        if (!file_exists(_PS_THEME_DIR_ . "templates/$tpl") &&
            !file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl")
        ) {
            return;
        }
        if($settings['enable_slider'] == 'yes'){
            // Store product temporary if exists
            isset($this->context->smarty->tpl_vars['product']) && $tmp = $this->context->smarty->tpl_vars['product'];

            foreach ($products as &$product) {
                $this->context->smarty->assign('product', $product);
                $slides[] = $this->context->smarty->fetch($tpl);
            }
            // Restore product if exists
            isset($tmp) && $this->context->smarty->tpl_vars['product'] = $tmp;
            

            $this->renderCarousel($settings, $slides);
        }else{
            $this->context->smarty->assign('columns', array(
                'desktop' => $settings['grid_column'],
                'tablet'  => $settings['grid_column_tablet'] ? $settings['grid_column_tablet'] : 3,
                'mobile'  => $settings['grid_column_mobile'] ? $settings['grid_column_mobile'] : 2,
            ));

            $out_put .= $this->context->smarty->fetch( _VEC_PATH_ . 'views/templates/front/widgets/products.tpl', ['products' => $products] );
            echo $out_put;
        }
        
    }
}
