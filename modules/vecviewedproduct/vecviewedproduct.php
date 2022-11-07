<?php
/**
* 2007-2018 PrestaShop.
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VecViewedproduct extends Module implements WidgetInterface
{
    private $templateFile;
    private $currentProductId;

    public function __construct()
    {
        $this->name = 'vecviewedproduct';
        $this->author = 'ThemeVec';
        $this->version = '1.0.2';
        $this->tab = 'front_office_features';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = array(
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        );

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Viewed products block', array(), 'Modules.Viewedproduct.Admin');
        $this->description = $this->trans(
            'Display a kind of showcase on your product pages with recently viewed products.',
            array(),
            'Modules.Viewedproduct.Admin'
        );

        $this->templateFile = 'module:vecviewedproduct/views/templates/hook/vecviewedproduct.tpl';
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('PRODUCTS_VIEWED_NBR', 8)
            && $this->registerHook('displayFooterProduct')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('actionObjectProductDeleteAfter')
            && $this->registerHook('actionObjectProductUpdateAfter')
        ;
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->_clearCache($this->templateFile);
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        $this->_clearCache($this->templateFile);
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitBlockViewed')) {
            if (0 === (int) Tools::getValue('vecviewed_limit') || 0 === (int) Tools::getValue('vecviewed_items') || 0 === (int) Tools::getValue('vecviewed_speed') || 0 === (int) Tools::getValue('vecviewed_pause')) {
                $output .= $this->displayError($this->trans('Invalid number.', array(), 'Modules.Viewedproduct.Admin'));
            } else {
                $languages = Language::getLanguages(false);
                $values = array();
                
                foreach ($languages as $lang){
                    $values['vecviewed_title'][$lang['id_lang']] = Tools::getValue('vecviewed_title_'.$lang['id_lang']);
                }

                Configuration::updateValue('vecviewed_title', $values['vecviewed_title']);

                Configuration::updateValue('vecviewed_items', Tools::getValue('vecviewed_items'));
                Configuration::updateValue('vecviewed_speed', Tools::getValue('vecviewed_speed'));
                Configuration::updateValue('vecviewed_auto', Tools::getValue('vecviewed_auto'));
                Configuration::updateValue('vecviewed_pause', Tools::getValue('vecviewed_pause'));
                Configuration::updateValue('vecviewed_arrows', Tools::getValue('vecviewed_arrows'));
                Configuration::updateValue('vecviewed_dots', Tools::getValue('vecviewed_dots'));
                Configuration::updateValue('vecviewed_limit', Tools::getValue('vecviewed_limit'));

                $this->_clearCache($this->templateFile);

                $output .= $this->displayConfirmation($this->trans(
                    'The settings have been updated.',
                    array(),
                    'Admin.Notifications.Success'
                ));
            }
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $id_lang = (int) Context::getContext()->language->id;
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('General Settings'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Module title'),
                        'name' => 'vecviewed_title',
                        'desc' => $this->l('This title will be displayed on front-office.')
                    ),
                    array(
                        'type' => 'text',
                        'label' =>  $this->l('Products limit :'),
                        'name' => 'vecviewed_limit',
                        'class' => 'fixed-width-sm',
                        'desc' =>  $this->l('Set the number of products which you would like to see displayed in this module'),
                    ),
                    
                    
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        $fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Slider configurations'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                        'type' => 'text',
                        'label' => $this->l('Slides to show'),
                        'name' => 'vecviewed_items',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->l('Show number of product visible in screen.'),
                ),
                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Auto play'),
                    'name' => 'vecviewed_auto',
                    'class' => 'fixed-width-xs',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Slide speed'),
                    'name' => 'vecviewed_speed',
                    'class' => 'fixed-width-sm',
                    'suffix' => 'milliseconds',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Time auto'),
                    'name' => 'vecviewed_pause',
                    'class' => 'fixed-width-sm',
                    'suffix' => 'milliseconds',
                    'desc' => $this->l('This field only is valuable when auto play function is enable. Default is 3000ms.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show Next/Back control'),
                    'name' => 'vecviewed_arrows',
                    'class' => 'fixed-width-xs',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show pagination control'),
                    'name' => 'vecviewed_dots',
                    'class' => 'fixed-width-xs',
                    'desc' => $this->l(''),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )       
        );

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $configFormLang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $configFormLang ? $configFormLang : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBlockViewed';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
            '&configure=' . $this->name .
            '&tab_module=' . $this->tab .
            '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array(
            'vecviewed_items'      => Configuration::get('vecviewed_items'),
            'vecviewed_speed'      => Configuration::get('vecviewed_speed'),
            'vecviewed_auto'       => Configuration::get('vecviewed_auto'),
            'vecviewed_pause'      => Configuration::get('vecviewed_pause'),
            'vecviewed_arrows'      => Configuration::get('vecviewed_arrows'),
            'vecviewed_dots'       => Configuration::get('vecviewed_dots'),
            'vecviewed_limit'      => Configuration::get('vecviewed_limit'),
        );
        
        
        foreach ($languages as $lang)
        {   
            $fields['vecviewed_title'][$lang['id_lang']] = Tools::getValue('vecviewed_title_'.$lang['id_lang'], Configuration::get('vecviewed_title', $lang['id_lang']));
        }
        return $fields;
    }

    public function getCacheId($name = null)
    {
        $key = implode('|', $this->getViewedProductIds());

        return parent::getCacheId('vecviewedproduct|' . $key);
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (isset($configuration['product']['id_product'])) {
            $this->currentProductId = $configuration['product']['id_product'];
        }

        if ('displayProductAdditionalInfo' === $hookName) {
            $this->addViewedProduct($this->currentProductId);

            return;
        }

        if (!isset($this->context->cookie->viewed) || empty($this->context->cookie->viewed)) {
            return;
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId())) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }

            $this->smarty->assign($variables);
        }

        return $this->fetch($this->templateFile, $this->getCacheId());
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        if (isset($configuration['product']['id_product'])) {
            $this->currentProductId = $configuration['product']['id_product'];
        }

        $products = $this->getViewedProducts();
        $title = Configuration::get('vecviewed_title', $this->context->language->id);

        $slider_options = array(
            'slidesToShow' => (int)Configuration::get('vecviewed_items') ? (int)Configuration::get('vecviewed_items') : 4,
            'speed' => (int)Configuration::get('vecviewed_speed') ? (int)Configuration::get('vecviewed_speed') : 500,
            'autoplay' => Configuration::get('vecviewed_auto') ? true : false,
            'autoplay_speed' => (int)Configuration::get('vecviewed_pause') ? (int)Configuration::get('vecviewed_pause') : 3000,
            'arrows' => Configuration::get('vecviewed_arrows') ? true : false,
            'dots' => Configuration::get('vecviewed_dots') ? true : false,
            'slidesToScroll' => 1,   
        );

        if (!empty($products)) {
            return array(
                'products' => $products,
                'title' => $title,
                'slider_options' => json_encode($slider_options),
                'classes' => 'items-desktop-' . $slider_options['slidesToShow']
            );
        }

        return false;
    }

    protected function addViewedProduct($idProduct)
    {
        $arr = array();

        if (isset($this->context->cookie->viewed)) {
            $arr = explode(',', $this->context->cookie->viewed);
        }

        if (!in_array($idProduct, $arr)) {
            $arr[] = $idProduct;

            $this->context->cookie->viewed = trim(implode(',', $arr), ',');
        }
    }

    protected function getViewedProductIds()
    {
        $viewedProductsIds = array_reverse(explode(',', $this->context->cookie->viewed));
        if (null !== $this->currentProductId && in_array($this->currentProductId, $viewedProductsIds)) {
            $viewedProductsIds = array_diff($viewedProductsIds, array($this->currentProductId));
        }

        $existingProducts = $this->getExistingProductsIds();
        $viewedProductsIds = array_filter($viewedProductsIds, function ($entry) use ($existingProducts) {
            return in_array($entry, $existingProducts);
        });

        return array_slice($viewedProductsIds, 0, (int) (Configuration::get('vecviewed_limit')));
    }

    protected function getViewedProducts()
    {
        $productIds = $this->getViewedProductIds();

        if (!empty($productIds)) {
            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            $products_for_template = array();

            if (is_array($productIds)) {
                foreach ($productIds as $productId) {
                    if ($this->currentProductId !== $productId) {
                        $products_for_template[] = $presenter->present(
                            $presentationSettings,
                            $assembler->assembleProduct(array('id_product' => $productId)),
                            $this->context->language
                        );
                    }
                }
            }

            return $products_for_template;
        }

        return false;
    }

    /**
     * @return array the list of active product ids.
     */
    private function getExistingProductsIds()
    {
        $existingProductsQuery = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT p.id_product
            FROM ' . _DB_PREFIX_ . 'product p
            WHERE p.active = 1'
        );

        return array_map(function ($entry) {
            return $entry['id_product'];
        }, $existingProductsQuery);
    }
}
