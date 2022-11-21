<?php
/*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class VecThemeoptions extends Module implements WidgetInterface
{
    public static $text_transform = array(
        1 => array('id' =>1 , 'name' => 'None'),
        2 => array('id' =>2 , 'name' => 'Capitalize'),
        3 => array('id' =>3 , 'name' => 'UPPERCASE'),
    );
    public static $product_row = array(
        1 => array('id' =>1 , 'name' => '2'),
        2 => array('id' =>2 , 'name' => '3'),
        3 => array('id' =>3 , 'name' => '4'),
        4 => array('id' =>4 , 'name' => '5'),
        5 => array('id' =>5 , 'name' => '6'),
    );
    public $fields_arr_path = '/fields_array.php';
    private $_html;

    private $templateFile;

    public function __construct()
    {
        $this->name = 'vecthemeoptions';
        $this->author = 'ThemeVec';
        $this->version = '1.2.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        Shop::addTableAssociation('info', array('type' => 'shop'));

        $this->displayName = $this->trans('Vec - Theme options', array(), 'Modules.VecThemeoptions.Admin');
        $this->description = $this->trans('Theme editor module - allow customize your website.', array(), 'Modules.VecThemeoptions.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        //General
        Configuration::updateValue($this->name . 'g_main_color', '#ff4e0c');
        Configuration::updateValue($this->name . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap');
        Configuration::updateValue($this->name . 'g_body_gfont_name', '"Quicksand", sans-serif');
        Configuration::updateValue($this->name . 'g_body_font_size', 14);
        Configuration::updateValue($this->name . 'g_body_font_color', '#666666');
		
        Configuration::updateValue($this->name . 'button_text', '#ffffff');
        Configuration::updateValue($this->name . 'button_texth', '#ffffff');
		Configuration::updateValue($this->name . 'button_border', 'none');
        Configuration::updateValue($this->name . 'button_border_width', '1px');
		Configuration::updateValue($this->name . 'button_border_color', '#111111');
        Configuration::updateValue($this->name . 'button_border_colorh', '#ff4e0c');
		Configuration::updateValue($this->name . 'button_background', '#111111');
        Configuration::updateValue($this->name . 'button_backgroundh', '#ff4e0c');

		//header
		Configuration::updateValue($this->name . 'header_sticky', 1);
		//page title	
		Configuration::updateValue($this->name . 'ptitle_color', '#111111');
		Configuration::updateValue($this->name . 'ptitle_size', 'default');
        // Product
        Configuration::updateValue($this->name . 'second_img', 'fade');
        Configuration::updateValue($this->name . 'grid_type', 1);
        Configuration::updateValue($this->name . 'grid_name_color', '#111111');
        Configuration::updateValue($this->name . 'grid_name_colorh', '#ff4e0c');
        Configuration::updateValue($this->name . 'grid_name_size', 16);
        Configuration::updateValue($this->name . 'grid_name_length', 'cut');
        Configuration::updateValue($this->name . 'grid_name_cut', 45);
        Configuration::updateValue($this->name . 'p_name_transform', 1);
        Configuration::updateValue($this->name . 'p_price_color', '#f04057');
        Configuration::updateValue($this->name . 'p_price_size', 18);
		
		Configuration::updateValue($this->name . 'new_bgcolor', '#00c2b4');
        Configuration::updateValue($this->name . 'new_color', '#ffffff');
		Configuration::updateValue($this->name . 'sale_bgcolor', '#ff4e0c');
        Configuration::updateValue($this->name . 'sale_color', '#ffffff');
		Configuration::updateValue($this->name . 'pack_bgcolor', '#00c2b4');
        Configuration::updateValue($this->name . 'pack_color', '#ffffff');
		
        // Category page
        Configuration::updateValue($this->name . 'category_width', 'inherit');
        Configuration::updateValue($this->name . 'category_layout', 1);
        Configuration::updateValue($this->name . 'category_thumbnail', 0);
        Configuration::updateValue($this->name . 'category_description', 'hide');
        Configuration::updateValue($this->name . 'category_description_bottom', 0);
        Configuration::updateValue($this->name . 'category_sub', 0);
        Configuration::updateValue($this->name . 'PS_PRODUCTS_PER_PAGE', 16);
        Configuration::updateValue($this->name . 'category_column', 3);
        Configuration::updateValue($this->name . 'category_pagination', 'default');
        // Product page
        Configuration::updateValue($this->name . 'product_width', 'inherit');
        Configuration::updateValue($this->name . 'product_layout', 1);
        Configuration::updateValue($this->name . 'main_layout', 1);
        Configuration::updateValue($this->name . 'product_image', 'horizontal');
        Configuration::updateValue($this->name . 'information_layout', 1);
        Configuration::updateValue($this->name . 'zoom', 1);
		Configuration::updateValue($this->name . 'thumbnail_items', 4);
        Configuration::updateValue($this->name . 'product_name_color', '#111111');
        Configuration::updateValue($this->name . 'product_name_size', 32);
        Configuration::updateValue($this->name . 'product_name_transform', 1);
        Configuration::updateValue($this->name . 'product_price_color', '#ff4e0c');
        Configuration::updateValue($this->name . 'product_price_size', 24);

        $this->vecImportImages();

        return parent::install()
        && $this->registerHook('header')
        && $this->registerHook('displayBackofficeHeader')
        && $this->registerHook('productSearchProvider')
        && $this->registerHook('actionProductSearchComplete')
        && $this->_createMenu();
    }

    public function uninstall()
    {
        return parent::uninstall()
               && $this->_deleteMenu();
    }

    protected function createNewHook(){
        $hooks = array(
            'displayFilterCanvas'
        );
        foreach($hooks as $hook){
            $id_hook = Hook::getIdByName($hook, false);
            if(!$id_hook){
                $new_hook = new Hook();
                $new_hook->name = pSQL($hook);
                $new_hook->title = pSQL($hook);
                $new_hook->position = 1;
                $new_hook->add();
            }
            
        }
        return true;
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
        $tab->class_name = "AdminVecThemeoptions";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Theme settings";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminPosThemeoptions');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        
        $html = '';
        $multiple_arr = array();

        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            $html .= $this->getWarningMultishopHtml();
        }
        // START RENDER FIELDS
        $this->AllFields();
        // END RENDER FIELDS
        if(Tools::isSubmit('save'.$this->name)){
            if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
                $helper = $this->SettingForm();
                $html_form = $helper->generateForm($this->fields_form);
                $html .= $html_form;

                return $html;
            }
            foreach($this->fields_form as $key => $value){
                $multiple_arr = array_merge($multiple_arr,$value['form']['input']);
            }
            // START LANG
            $languages = Language::getLanguages(false);
            if(isset($multiple_arr) && !empty($multiple_arr)){
                foreach($multiple_arr as $mvalue){
                    if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
                       foreach($languages as $lang){
                        ${$mvalue['name'].'_lang'}[$lang['id_lang']] = Tools::getvalue($mvalue['name'].'_'.$lang['id_lang']);
                       }
                    }
                }
            }
            // END LANG
            if(isset($multiple_arr) && !empty($multiple_arr)){
                //echo '<pre>';print_r($multiple_arr);die;
                foreach($multiple_arr as $mvalue){
                    if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
                            Configuration::updateValue($this->name.$mvalue['name'],${$mvalue['name'].'_lang'});
                    }else{
                        if(isset($mvalue['name'])){
                            if($mvalue['name'] == 'PS_PRODUCTS_PER_PAGE'){
                                Configuration::updateValue('PS_PRODUCTS_PER_PAGE',Tools::getvalue($mvalue['name']));
                            }else{
                                Configuration::updateValue($this->name.$mvalue['name'],Tools::getvalue($mvalue['name']));
                            }
                        }
                    }
                }
            }
			
            $helper = $this->SettingForm();
            $html_form = $helper->generateForm($this->fields_form);
            $html .= $this->displayConfirmation($this->l('Successfully Saved All Fields Values.'));
            $html .= $html_form;
            $this->generateCss();
            $this->generateJs();
            
        }else{
            $helper = $this->SettingForm();
            $html_form = $helper->generateForm($this->fields_form);
            $html .= $html_form;
        }
        return $html;
    }
    public function SettingForm() {
        $languages = Language::getLanguages(false);
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->AllFields();
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach ($languages as $lang)
                $helper->languages[] = array(
                        'id_lang' => $lang['id_lang'],
                        'iso_code' => $lang['iso_code'],
                        'name' => $lang['name'],
                        'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                );
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'save'.$this->name;
        $multiple_arr = array();

        foreach($this->fields_form as $key => $value) {
            if(empty($multiple_arr)){
                if(isset($value['form']['input']) && !empty($value['form']['input'])){
                    $multiple_arr = $value['form']['input'];
                }
            }else{
                if(isset($value['form']['input']) && !empty($value['form']['input'])){
                    $multiple_arr = array_merge($multiple_arr,$value['form']['input']);
                }
            }
        }
        foreach($multiple_arr as $mvalue){
            if(isset($mvalue['lang']) && $mvalue['lang'] == true && isset($mvalue['name'])){
               foreach($languages as $lang){
                    $helper->fields_value[$mvalue['name']][$lang['id_lang']] = Configuration::get($this->name.$mvalue['name'],$lang['id_lang']);
               }
            }else{
                if(isset($mvalue['name'])){
                    if($mvalue['name'] == 'PS_PRODUCTS_PER_PAGE'){
                        $helper->fields_value[$mvalue['name']] = Configuration::get('PS_PRODUCTS_PER_PAGE');
                    }else{
                        $helper->fields_value[$mvalue['name']] = Configuration::get($this->name.$mvalue['name']);
                    }
                }
            }
        }
        return $helper;
    }

    public function AllFields()
    {
        $posthemeoption_settings = array();
        include_once(dirname(__FILE__).$this->fields_arr_path);
        if(isset($posthemeoption_settings) && !empty($posthemeoption_settings)){
            foreach ($posthemeoption_settings as $posthemeoption_setting) {
                $this->fields_form[]['form'] = $posthemeoption_setting;
            }
        }
        //echo '<pre>'; print_r($this->fields_form);die;
        return $this->fields_form;
    }

    public function getPath()
    {
        return $this->_path;
    }
 
    public function convertTransform($value) {
            switch($value) {
                case 2 :
                    $transform_option = 'capitalize';
                    break;
                case 1 :
                    $transform_option = 'none';
                    break;
                default :
                    $transform_option = 'uppercase';
            }
            return  $transform_option;
    }
    public function generateCss()
    {
        $css = '';
        $main_color = Configuration::get($this->name . 'g_main_color');
        $button_color = Configuration::get($this->name . 'button_text');
        $button_colorh = Configuration::get($this->name . 'button_texth');
        $button_border = Configuration::get($this->name . 'button_border');
        $button_border_width = Configuration::get($this->name . 'button_border_width');
        $button_border_color = Configuration::get($this->name . 'button_border_color');
        $button_border_colorh = Configuration::get($this->name . 'button_border_colorh');
        $button_background = Configuration::get($this->name . 'button_background');
        $button_backgroundh = Configuration::get($this->name . 'button_backgroundh');
        $grid_name_color = Configuration::get($this->name . 'grid_name_color');
        $grid_name_colorh = Configuration::get($this->name . 'grid_name_colorh');
        $grid_name_size = Configuration::get($this->name . 'grid_name_size');
        $grid_name_transform = $this->convertTransform(Configuration::get($this->name . 'grid_name_transform'));
        $grid_price_color = Configuration::get($this->name . 'grid_price_color');
        $grid_price_size = Configuration::get($this->name . 'grid_price_size');
        $ptitle_color = Configuration::get($this->name . 'ptitle_color');
        if($button_border != 'none'){
            $css .= '.btn, .btn-primary, .btn-secondary, .btn-tertiary {';
                if($button_border != 'none'){
                    $css .= 'border-style: ' . $button_border .';';
                }
                if($button_border_width){
                    $css .= 'border-width: ' . $button_border_width .'px;';
                }

            $css .= '}';
        }
        $css .='
         :root {  
            --hovercolor: '.$main_color.';
            --pricecolor: '.$grid_price_color.';
            --pricesize: '.$grid_price_size.'px;
            --namecolor: '.$grid_name_color.';
            --namehovercolor: '.$grid_name_colorh.';
            --nametransform: '.$grid_name_transform.';
            --namesize: '.$grid_name_size.'px;
            --buttoncolor: '.$button_color.'; 
            --buttonbackground: '.$button_background.'; 
            --buttonborder: '.$button_border_color.'; 
            --buttonhovercolor: '.$button_colorh.'; 
            --buttonhoverbackground: '.$button_backgroundh.'; 
            --buttonhoverborder: '.$button_border_colorh.';  
            --pagetitlecolor: '.$ptitle_color.'; 
        }';
        $body_font_family = Configuration::get($this->name . 'g_body_gfont_name');
        $body_font_size = Configuration::get($this->name . 'g_body_font_size');
        $body_font_color = Configuration::get($this->name . 'g_body_font_color');
        $css .= 'body{
            font-family: '.$body_font_family.';
            font-size: '.$body_font_size.'px;
            color: '.$body_font_color.';
        }';
         $css .= '{
            font-size: '.$body_font_size.'px;
        }';
       
        //header
        $sticky_header_bg = Configuration::get($this->name . 'sticky_background');
        $css .= '#header .sticky-inner.scroll-menu {  
            background-color: '.$sticky_header_bg.';   
        }';

        //Page title
        $ptitle_bg_image = Configuration::get($this->name . 'ptitle_bg_image');
        $ptitle_color = Configuration::get($this->name . 'ptitle_color');
        if($ptitle_bg_image){
            $css .= '.page-title-wrapper{  
                background-image: url('.$ptitle_bg_image.');   
            }';
        }
        //Product grid
        $product_name_color = Configuration::get($this->name . 'product_name_color');
        $product_name_size = Configuration::get($this->name . 'product_name_size');
        $product_name_transform = $this->convertTransform(Configuration::get($this->name . 'product_name_transform'));
        $product_price_color = Configuration::get($this->name . 'product_price_color');
        $product_price_size = Configuration::get($this->name . 'product_price_size');
        $css .= '.h1.namne_details, .product_name_h1{
            color: '.$product_name_color.';
            font-size: '.$product_name_size.'px;
            text-transform: '.$product_name_transform.';
        }';
        $css .= '.product-prices .price, .product-prices .current-price span:first-child{
            color:'.$product_price_color.';
            font-size: '.$product_price_size.'px;
        }';
        $new_bgcolor = Configuration::get($this->name . 'new_bgcolor');
        $new_color = Configuration::get($this->name . 'new_color');
        $sale_bgcolor = Configuration::get($this->name . 'sale_bgcolor');
        $sale_color = Configuration::get($this->name . 'sale_color');
        $pack_bgcolor = Configuration::get($this->name . 'pack_bgcolor');
        $pack_color = Configuration::get($this->name . 'pack_color');
        if($new_bgcolor || $new_color){
            $css .= '.product-flag .new{';
                if($new_bgcolor){
                    $css .= 'background: '. $new_bgcolor . ';';
                }
                if($new_color){
                    $css .= 'color: '. $new_color . ';';
                }    
            $css .= '}';
        }
        if($sale_bgcolor || $sale_color){
            $css .= '.product-flag .discount, .product-flag .sale{';
                if($sale_bgcolor){
                    $css .= 'background: '. $sale_bgcolor . ';';
                }
                if($sale_color){
                    $css .= 'color: '. $sale_color . ';';
                }    
            $css .= '}';
        }
        if($pack_bgcolor || $pack_color){
            $css .= '.product-flag .pack{';
                if($pack_bgcolor){
                    $css .= 'background: '. $pack_bgcolor . ';';
                }
                if($pack_color){
                    $css .= 'color: '. $pack_color . ';';
                }    
            $css .= '}';
        }
        
        //Category
        $category_width = Configuration::get($this->name . 'category_width');
        $category_custom_width = Configuration::get($this->name . 'category_custom_width');
        if($category_width == 'custom'){
            $css .= '#product #wrapper .container{
                width: '. $category_custom_width .'px;
            }';
        }
        //details
        $product_width = Configuration::get($this->name . 'product_width');
        $product_custom_width = Configuration::get($this->name . 'product_custom_width');
        if($product_width == 'custom'){
            $css .= '#product #wrapper .container{
                width: '. $product_custom_width .'px;
            }';
        }
        //Custom CSS
        if(Configuration::get($this->name . 'custom_css')){
            $css .= Configuration::get($this->name . 'custom_css');
        }
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
            $my_file = $this->local_path.'views/css/vecthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.css';
        
        $fh = fopen($my_file, 'w') or die("can't open file");
        fwrite($fh, $css);
        fclose($fh);
    }
    public function generateJs()
    {
        $js = '';
    
        if(Configuration::get($this->name . 'custom_js')){
            $js .= Configuration::get($this->name . 'custom_js');
        }
        if (Shop::getContext() == Shop::CONTEXT_SHOP)
            $my_file = $this->local_path.'views/js/vecthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.js';
        if($js){
            $fh = fopen($my_file, 'w') or die("can't open file");
            fwrite($fh, $js);
            fclose($fh);
        }else{
            if(file_exists($my_file)){
                unlink($my_file);
            }
        } 
        
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        return false;
    }
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        return false;
    }

    public function hookProductSearchProvider()
    {
        if (Tools::getIsset('from-xhr')) {

            if (Tools::getIsset('shop_view')) {
                $view = Tools::getValue('shop_view');
                if ($view == 'grid') {
                    $this->context->cookie->__set('shop_view', 'grid');
                } elseif ($view == 'list') {
                    $this->context->cookie->__set('shop_view', 'list');
                }
                $this->context->cookie->write();
            }

            $options = $this->getOptions();
            $configuration['is_catalog'] = (bool) Configuration::isCatalogMode();
            $this->context->smarty->assign(array(
                    'vectheme' => $options,
                    'configuration' => $configuration,
                ));
        }
    }
    public function hookActionProductSearchComplete($hook_args)
    {
        if (isset($hook_args['js_enabled']) && $hook_args['js_enabled']) {
            if (Tools::getIsset('shop_view')) {
                $view = Tools::getValue('shop_view');
                if ($view == 'grid') {
                    $this->context->cookie->__set('shop_view', 'grid');
                } elseif ($view == 'list') {
                    $this->context->cookie->__set('shop_view', 'list');
                }
                $this->context->cookie->write();
            }

            $options = $this->getOptions();
            $this->context->smarty->assign('vectheme', $options);
        }
    }

    public function getOptions(){
        $options = array(
            'header_sticky'                 => Configuration::get($this->name . 'header_sticky'),
            //Product grid
			'grid_type'                     => isset($_GET['grid_type']) ? $_GET['grid_type'] : Configuration::get($this->name . 'grid_type'),
			'rotator'                       => Configuration::get($this->name . 'second_img'),
			'name_length'                   => (Configuration::get($this->name . 'grid_name_length') == 'cut' && Configuration::get($this->name . 'grid_name_cut') > 0) ? (Configuration::get($this->name . 'grid_name_cut')) : 128,
            //Page title
            'ptitle_size'                   => Configuration::get($this->name . 'ptitle_size'),
            //Category page
            'category_layout'               => isset($_GET['category_layout']) ? $_GET['category_layout'] : Configuration::get($this->name . 'category_layout'),
            'category_thumbnail'            => isset($_GET['category_thumbnail']) ? $_GET['category_thumbnail'] : Configuration::get($this->name . 'category_thumbnail'),
            'category_description'          => isset($_GET['category_description']) ? $_GET['category_description'] : Configuration::get($this->name . 'category_description'),
            'category_description_bottom'   => isset($_GET['category_description_bottom']) ? $_GET['category_description_bottom'] : Configuration::get($this->name . 'category_description_bottom'),
            'category_sub'                  => isset($_GET['category_sub']) ? $_GET['category_sub'] : Configuration::get($this->name . 'category_sub'),
            'category_pagination'           => isset($_GET['category_pagination']) ? $_GET['category_pagination'] : Configuration::get($this->name . 'category_pagination'),
            'category_filter'               => isset($_GET['category_filter']) ? $_GET['category_filter'] : Configuration::get($this->name . 'category_filter'),
            'category_column'               => isset($_GET['column']) ? $_GET['column'] : Configuration::get($this->name . 'category_column'),
            //Product page
            'product_layout'                => isset($_GET['product_layout']) ? $_GET['product_layout'] : Configuration::get($this->name . 'product_layout'),
            'main_layout'                   => isset($_GET['product_main']) ? $_GET['product_main'] : Configuration::get($this->name . 'main_layout'),
            'product_image'                 => isset($_GET['product_image']) ? $_GET['product_image'] : Configuration::get($this->name . 'product_image'),
            'information_layout'            => isset($_GET['product_infor']) ? $_GET['product_infor'] : Configuration::get($this->name . 'information_layout'),

            'zoom_active'                   => Configuration::get($this->name . 'zoom'),
            'thumbnail_items'               => (int)Configuration::get($this->name . 'thumbnail_items') ? Configuration::get($this->name . 'thumbnail_items'): 4,
            //404 page
            '404_content'                   => Configuration::get($this->name . '404_content'),
            '404_image'                     => Configuration::get($this->name . '404_image') ? Configuration::get($this->name . '404_image') : '',
            '404_text1'                     => Configuration::get($this->name . '404_text1', $this->context->language->id) ? Configuration::get($this->name . '404_text1', $this->context->language->id) : '',
            '404_text2'                     => Configuration::get($this->name . '404_text2', $this->context->language->id) ? Configuration::get($this->name . '404_text2', $this->context->language->id) : '',
		);
        if (isset($this->context->cookie->shop_view)) {
            $options['shop_view'] = $this->context->cookie->shop_view;
        }
        //echo '<pre>'; print_r($options);die;
        return $options;
    }

    public function hookHeader($params)
	{
		if (Shop::getContext() == Shop::CONTEXT_SHOP){
    		$this->context->controller->addCSS(($this->_path).'views/css/vecthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
            $js_file = ($this->_path).'views/js/vecthemeoptions_s_'.(int)$this->context->shop->getContextShopID().'.js';
            $this->context->controller->addJS($js_file);
		}
        $body_font_family = Configuration::get($this->name . 'g_body_gfont_url');
		if($body_font_family) $this->context->controller->registerStylesheet('vecthemeoptions-body-fonts', $body_font_family,['server' => 'remote']);
		$body_class = '';
        if(Module::isInstalled('posquickmenu') && Module::isEnabled('posquickmenu')){
            $body_class  = 'has-quickmenu';
        }
        $smart_vals = $this->getOptions();
		$smart_vals['body_class'] = $body_class;
		$this->context->smarty->assign('vectheme', $smart_vals);

		$this->context->smarty->assign('name_length', Configuration::get($this->name . 'p_name_length'));
        
        $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
        Media::addJsDef(array(
            'vectheme' => array(
                'baseDir' => $protocol_content.Tools::getHttpHost().__PS_BASE_URI__,
                'cd_days_text' => 'days',
                'cd_day_text' => 'day',
                'cd_hours_text' => 'hours',
                'cd_hour_text' => 'hour',
                'cd_mins_text' => 'mins',
                'cd_min_text' => 'min',
                'cd_secs_text' => 'secs',
                'cd_sec_text' => 'sec',
            )
        ));
	}

    public function hookDisplayBackOfficeHeader($params)
	{
		$this->context->controller->addCSS($this->_path . 'views/css/elixi-icon.css');
	}
	protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->getTranslator()->trans('You cannot manage slides items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit', array(), 'Modules.Imageslider.Admin') .
            '</p>';
        } else {
            return '';
        }
    }

    public function vecImportImages(){


        $images = array();
        
        $error = false;
        foreach($images as $image){
            if(! $this->importImageFromURL($image, false)){
                $error = true;
            }
        }

        return true;
    }

    protected function importImageFromURL($url, $regenerate = true)
    {
        $origin_image = pathinfo($url);
        $origin_name = $origin_image['filename'];
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
  
        $path = _PS_IMG_DIR_ . 'cms/';

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = [];
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once _PS_TOOL_DIR_ . 'http_build_url/http_build_url.php';
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);

                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path . $origin_name .'.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
   
        } else {
            echo 'cant copy image';
            @unlink($orig_tmpfile);

            return false;
        }
        unlink($orig_tmpfile);

        return true;
    }
}
