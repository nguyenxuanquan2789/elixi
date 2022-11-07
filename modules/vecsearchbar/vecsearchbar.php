<?php
/**
 * 2007-2020 PrestaShop SA and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'vecsearchbar/src/VecSearchCore.php';
require_once _PS_MODULE_DIR_ . 'vecsearchbar/src/VecSearchProvider.php';

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Themevec\Module\Adapter\Search\VecSearchProvider;

class VecSearchbar extends Module implements WidgetInterface
{
    /**
     * @var string Name of the module running on PS 1.6.x. Used for data migration.
     */
    public static $level = array(
        1 => array('id' =>1 , 'name' => '2'),
        2 => array('id' =>2 , 'name' => '3'),
        3 => array('id' =>3 , 'name' => '4'),
        4 => array('id' =>4 , 'name' => '5'),

    );
    private $_html = '';
    private $templateFile;

    public function __construct()
    {
        $this->name = 'vecsearchbar';
        $this->author = 'ThemeVec';
        $this->version = '1.1.0';
        $this->need_instance = 0;
        $this->bootstrap =true ;

        parent::__construct();

        $this->displayName = $this->trans('Vec - Search bar', [], 'Modules.VecSearchbar.Admin');
        $this->description = $this->trans('Help your visitors find what they are looking for, add a quick search field to your store.', [], 'Modules.VecSearchbar.Admin');

        $this->ps_versions_compliancy = ['min' => '1.7.1.0', 'max' => _PS_VERSION_];

        $this->templateFile = 'module:vecsearchbar/vecsearchbar.tpl';
    }

    public function install()
    {
        Configuration::updateValue($this->name.'_categories', 0);
        Configuration::updateValue($this->name.'_categories_level', 3);
        Configuration::updateValue($this->name.'_limit', 8);
        Configuration::updateValue($this->name.'_keyword', '');
        Configuration::updateValue($this->name.'_suggest_status', 0);

        $languages = Language::getLanguages(false);
        $values = array();
        foreach ($languages as $lang){
                $values['keywords_title'][$lang['id_lang']] = '';
                $values['suggest_title'][$lang['id_lang']] = 'Most search products';
            }
        Configuration::updateValue($this->name.'_keywords_title', $values['keywords_title']);
        Configuration::updateValue($this->name.'_suggest_title', $values['suggest_title']);

        return parent::install()
            && $this->_createMenu() 
            && $this->registerHook('productSearchProvider')
            && $this->registerHook('displaySearch')
            && $this->registerHook('header')
        ;
    }

    public function uninstall(){
        Configuration::deleteByName($this->name.'_categories');
        Configuration::deleteByName($this->name.'_categories_level');
        Configuration::deleteByName($this->name.'_limit');
        Configuration::deleteByName($this->name.'_keyword');
        Configuration::deleteByName($this->name.'_suggest_status');
        Configuration::deleteByName($this->name.'_keywords_title');
        Configuration::deleteByName($this->name.'_suggest_title');
        return parent::uninstall() && $this->_deleteMenu();
    }
   
    protected function _createMenu() {
        $response = true;
        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('VecThemeMenu');
        if($parentTabID){
            $parentTab = new Tab($parentTabID);
        }else{
            $parentconfigure = Tab::getIdFromClassName('IMPROVE');
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "VecThemeMenu";
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = "THEMEVEC";
            }
            $parentTab->id_parent = 0;
            $response &= $parentTab->add();
        }
        
        //Add parent tab: modules
        $parentTabID2 = Tab::getIdFromClassName('VecModules');
        if($parentTabID2){
            $parentTab = new Tab($parentTabID);
        }else{
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "VecModules";
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = "Modules";
            }
            $tab->id_parent = (int)Tab::getIdFromClassName('VecThemeMenu');
            $tab->module = $this->name;
            $tab->icon = 'open_with';
            $response &= $tab->add();
        }
        //Add tab
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminVecSearchBar";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Ajax search";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('VecModules');
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    protected function _deleteMenu() {
        $parentTabID = Tab::getIdFromClassName('VecModules');

        // Get the number of tabs inside our parent tab
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }
        
        $id_tab = (int)Tab::getIdFromClassName('AdminVecSearchBar');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    public function getContent(){
        $admin_url =  $this->context->link->getAdminLink('AdminProducts', true);
        $tokenProducts = Tools::getAdminTokenLite('AdminProducts');
        $this->context->controller->addJS($this->_path. 'views/js/admin/admin.js');
        $this->html = '';
        if(Tools::isSubmit('submitUpdate')){
            $languages = Language::getLanguages(false);
            $values = array();

            foreach ($languages as $lang){
                $values['keywords_title'][$lang['id_lang']] = Tools::getValue('keywords_title_'.$lang['id_lang']);
                $values['suggest_title'][$lang['id_lang']] = Tools::getValue('suggest_title_'.$lang['id_lang']);
            }
            Configuration::UpdateValue($this->name.'_categories',Tools::getValue('categories'));
            Configuration::UpdateValue($this->name.'_categories_level',Tools::getValue('categories_level'));
            Configuration::UpdateValue($this->name.'_limit',Tools::getValue('limit'));
            Configuration::UpdateValue($this->name.'_keyword',Tools::getValue('keyword'));
            Configuration::UpdateValue($this->name.'_suggest_status',Tools::getValue('suggest_status'));
            Configuration::updateValue($this->name.'_keywords_title', $values['keywords_title']);
            Configuration::updateValue($this->name.'_suggest_title', $values['suggest_title']);

            if ( is_array(Tools::getValue('suggest_products'))) {
                Configuration::updateValue($this->name . '_suggest_products', implode(',', Tools::getValue('suggest_products')));
            }else {
                Configuration::updateValue($this->name . '_suggest_products', null);
            }
            $this->html = $this->displayConfirmation($this->l('Settings updated successfully.'));
        }
        $this->html .= '<div id="admin_info"  data-admin_url ='.$admin_url.' data-token_product ='.$tokenProducts.'></div>';
        $this->html .= $this->renderForm();
        return $this->html;

    }

    public function renderForm(){
        $id_lang = (int)Context::getContext()->language->id;
        $products = array();
        $products_current = Configuration::get($this->name . '_suggest_products');
        if(isset($products_current) && $products_current){
            $products_current = explode(',', $products_current);
            foreach($products_current as $product_current){
                $product_name = Product::getProductName($product_current, null, $id_lang);
                $products[] = array(
                    'name' => $product_name,
                    'product_id' => $product_current
                );
            }
        }  

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type'      => 'switch',
                        'label'     => $this->l('Enable list categories'),
                        'desc'      => $this->l('Would you like to use search categories ?'),
                        'name'      => 'categories',
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Category depth level'),
                        'name' => 'categories_level',
                        'options' => array(
                            'query' => self::$level,
                            'id' => 'id',
                            'name' => 'name',
                        ),
                        'validation' => 'isUnsignedInt',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Limit number products in ajax results'),
                        'name' => 'limit',
                        'class' => 'fixed-width-sm',
                        'desc' => $this->l('')
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Title for suggest keywords'),
                        'name' => 'keywords_title',
                        'class' => 'fixed-width-xxxl',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Suggest keywords'),
                        'name' => 'keyword',
                        'desc' => $this->l('Display as popular keywords in frontend. Please enter the index words separated by a command symbol ( , ). Example : keyword1 , keyword2, keyword3')
                    ),
                    array(
                        'type'      => 'switch',
                        'label'     => $this->l('Show suggest products'),
                        'name'      => 'suggest_status',
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Title'),
                        'name' => 'suggest_title',
                        'class' => 'fixed-width-xxxl',
                        'form_group_class' => 'suggest-status',
                    ),
                    array(
                        'type' => 'selectproduct',
                        'label' => 'Select products:',
                        'name' => 'suggest_products',
                        'multiple'=> true,
                        'size' => 500,
                        'form_group_class' => 'suggest-status',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
        $helper = new HelperForm();
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->show_toolbar = false;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUpdate';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'products' => $products,
        );
        return $helper->generateForm(array($fields_form));
    }
    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array(
            'categories' => Tools::getValue('categories', Configuration::get($this->name.'_categories')),
            'categories_level' => Tools::getValue('categories_level', Configuration::get($this->name.'_categories_level')),
            'limit' => Tools::getValue('limit', Configuration::get($this->name.'_limit')),
            'keyword' => Tools::getValue('keyword', Configuration::get($this->name.'_keyword')),
            'suggest_status' => Tools::getValue('suggest_status', Configuration::get($this->name.'_suggest_status')),
            'suggest_products' => Tools::getValue('suggest_products', Configuration::get($this->name.'_suggest_products')),
        );
        foreach ($languages as $lang)
        {   
            $fields['keywords_title'][$lang['id_lang']] = Tools::getValue('keywords_title', Configuration::get($this->name . '_keywords_title', $lang['id_lang']));
            $fields['suggest_title'][$lang['id_lang']] = Tools::getValue('suggest_title', Configuration::get($this->name . '_suggest_title', $lang['id_lang']));
        }
        return $fields;
    }

    public function hookHeader()
    {
        $this->context->controller->registerJavascript('modules-vec_search', 'modules/' . $this->name . '/views/js/front/vecsearchbar.js', ['position' => 'bottom', 'priority' => 150]);
        Media::addJsDef(
            array(
                'vecsearch' => array(
                    'search_not_found' => $this->l('No product found'),
                    'view_more' => $this->l('View more results'),
                    'limit'  => Configuration::get($this->name.'_limit') ? Configuration::get($this->name.'_limit') : 10,
                )
             )
        );
    }

    public function getWidgetVariables($hookName, array $configuration = [])
    {   
        $keywords_array = array();
        if(Configuration::get($this->name.'_keyword')){
            $keywords = explode(',', Configuration::get($this->name.'_keyword'));
        
            foreach($keywords as $keyword){
                $keywords_array[] = trim($keyword , " ");
            }
        }
        $cate_on = (int)Configuration::get($this->name.'_categories');
        $widgetVariables = [
            'search_controller_url' => $this->context->link->getPageLink('search', null, null, null, false, null, true),
            'keywords'              => $keywords_array,
            'suggest_status'        => Configuration::get($this->name.'_suggest_status'),
            'suggest_ids'           => Configuration::get($this->name.'_suggest_products'),
            'keywords_title'        => Configuration::get($this->name.'_keywords_title', $this->context->language->id),
            'suggest_title'         => Configuration::get($this->name.'_suggest_title', $this->context->language->id),
            'show_categories'       => $cate_on,
            'cateOptions'           => $this->getCategoryOption(2, false, false, true, 1),
            'placeholder'           => isset($configuration['placeholder']) ? $configuration['placeholder'] : '',
            'search_type'           => isset($configuration['search_type']) ? $configuration['search_type'] : 'classic',
            'icon'                  => isset($configuration['icon']) ? $configuration['icon'] : '',
            'button_type'           => isset($configuration['button_type']) ? $configuration['button_type'] : '',
            'button_text'           => isset($configuration['button_text']) ? $configuration['button_text'] : '',
        ];

        /** @var array $templateVars */
        $templateVars = $this->context->smarty->getTemplateVars();
        if (is_array($templateVars) && !array_key_exists('search_string', $templateVars)) {
            $widgetVariables['search_string'] = '';
        }

        return $widgetVariables;
    }

    public function renderWidget($hookName, array $configuration = [])
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch($this->templateFile);
    }

    public function hookProductSearchProvider()
    {
        $controller = Dispatcher::getInstance()->getController();

        if (!empty($this->context->controller->php_self)) {
            $controller = $this->context->controller->php_self;
        }
        
        $controller = Tools::strtolower( $controller );
        
        if( $controller != 'search' ){
            return null;
        }
        
        $search_string = Tools::getValue('s');
        
        if (!$search_string) {
            $search_string = Tools::getValue('search_query');
        }
        
        if( !$search_string ){
            return null;
        }
        
        return new VecSearchProvider(
            $this->getTranslator(),
            new VecSearchCore()
        );
    }

    public function getCategoryOption($id_category = 2, $id_lang = false, $id_shop = false, $recursive = true, $depth=01) {
        $maxdepth = (int)Configuration::get('POSSEARCH_LEVEL') + 2;
        $depth = $depth + 1;
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id))
            return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop); // array  
        }
        if($depth <= $maxdepth){
        if (isset($children) && count($children)){
            if($category->id != 1 && $category->id != 2){
                $this->_html .='<li class="search-cat-item" date-depth="'. $depth .'">';
                $this->_html .='<a href="#" class="search-cat-value" data-id="'. $category->id .'">'.$category->name.'</a>';
                $this->_html .='</li>';
            }
            foreach ($children as $child){
                $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'], true, $depth);
            }
         }else{
             $this->_html .='<li class="search-cat-item">';
             $this->_html .='<a href="#" class="search-cat-value" data-id="'. $category->id .'">'.$category->name.'</a>';
             $this->_html .='</li>';
         }
        }
        
        
         return $this->_html ;
    }
}
