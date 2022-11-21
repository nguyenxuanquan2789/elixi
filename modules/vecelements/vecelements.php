<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

define('_VEC_VERSION_', '1.5.5');
define('_VEC_PATH_', _PS_MODULE_DIR_ . 'vecelements/');
define('_VEC_URL_', defined('_PS_BO_ALL_THEMES_DIR_') ? _MODULE_DIR_ . 'vecelements/' : 'modules/vecelements/');
define('_VEC_ASSETS_URL_', _VEC_URL_ . 'views/');
define('_VEC_TEMPLATES_', _VEC_PATH_ . 'views/templates/');

use VEC\UId;
use VEC\WPPost;
use VEC\TemplateLibraryXSourceBase;

require_once _VEC_PATH_ . 'classes/wrappers/UId.php';
require_once _VEC_PATH_ . 'classes/VECTheme.php';
require_once _VEC_PATH_ . 'classes/VECContent.php';
require_once _VEC_PATH_ . 'classes/VECTemplate.php';
require_once _VEC_PATH_ . 'classes/VECSmarty.php';
require_once _VEC_PATH_ . 'includes/plugin.php';

class VecElements extends Module
{
    protected static $controller;

    public $controllers = [
        'ajax',
        'preview',
    ];

    protected $overrides = ['Category','CmsCategory','Manufacturer','Supplier'];
    protected $tplOverride = false;

    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'vecelements';
        $this->tab = 'content_management';
        $this->version = '1.5.5';
        $this->author = 'ThemeVec';
        $this->module_key = '7a5ebcc21c1764675f1db5d0f0eacfe5';
        $this->ps_versions_compliancy = ['min' => '1.7.6', 'max' => '1.7.8'];
        $this->displayName = $this->l('V-Elements - Live page builder');
        $this->description = $this->l('The frontend drag & drop page builder. Based on Elementor WP plugin.');
        $this->bootstrap = true;
        parent::__construct($this->name, null);

        $this->checkThemeChange();

        Shop::addTableAssociation( VECTheme::$definition['table'], ['type' => 'shop'] );
        Shop::addTableAssociation( VECTheme::$definition['table'] . '_lang', ['type' => 'fk_shop'] );
        Shop::addTableAssociation( VECContent::$definition['table'], ['type' => 'shop']);
        Shop::addTableAssociation( VECContent::$definition['table'] . '_lang', ['type' => 'fk_shop'] );
    }

    public function install()
    {
        require_once _VEC_PATH_ . 'install/VecElementInstall.php';

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        VecElementInstall::initConfigs();

        if (!VecElementInstall::createTables()) {
            $this->_errors[] = Db::getInstance()->getMsgError();
            return false;
        }
        $res = $this->installDemoData();
        if ($res = parent::install()) {
            foreach (VecElementInstall::getHooks() as $hook) {
                $res = $res && $this->registerHook($hook, null, 1);
            }
            Configuration::updateValue('VEC_HEADER', 1);
            Configuration::updateValue('VEC_PAGE_INDEX', 2);
            Configuration::updateValue('VEC_FOOTER', 3);
        }

        return $res && $this->_createAdminMenu();
    }

    protected function _createAdminMenu() {
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
        $parentTabID2 = Tab::getIdFromClassName('AdminParentVECContent');
        if($parentTabID2){
            $parentTab = new Tab($parentTabID2);
        }else{
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "AdminParentVECContent";
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = "V-Elements";
            }
            $tab->id_parent = (int)Tab::getIdFromClassName('VecThemeMenu');
            $tab->module = $this->name;
            $tab->icon = 've';
            $response &= $tab->add();
        }
        //Add tab
        $this->updateTab('AdminParentVECContent', 1, 'AdminVECHeader', true, 'Header Builder');
        $this->updateTab('AdminParentVECContent', 2, 'AdminVECHome', true, 'Home Builder');
        $this->updateTab('AdminParentVECContent', 3, 'AdminVECFooter', true, 'Footer Builder');
        $this->updateTab('AdminParentVECContent', 4, 'AdminVECContent', true, 'Content to hooks');
        $this->updateTab('AdminParentVECContent', 5, 'AdminVECEditor', false, 'Live Editor');

        return $response;
    }

    protected static function updateTab($class_parent, $position, $class, $active, $name, $icon = ''){
        $id = (int) Tab::getIdFromClassName($class);
        $tab = new Tab($id);
        $tab->id_parent = (int) Tab::getIdFromClassName($class_parent);
        $tab->position = (int) $position;
        $tab->module = 'vecelements';
        $tab->class_name = $class;
        $tab->active = $active;
        $tab->icon = $icon;
        $tab->name = [];

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        if (!$tab->save()) {
            throw new Exception('Can not save Tab: ' . $class);
        }

        if (!$id && $tab->position != $position) {
            $tab->position = (int) $position;
            $tab->update();
        }

        return $tab;
    }

    public function uninstall(){
        foreach ( Tab::getCollectionFromModule($this->name) as $tab ) {
            $tab->delete();
        }

        return parent::uninstall();
    }

    public function enable( $force_all = false ){
        return parent::enable($force_all) && Db::getInstance()->update(
            'tab',
            ['active' => 1],
            "module = 'vecelements' AND class_name != 'AdminVECEditor'"
        );
    }

    public function disable( $force_all = false ){
        return Db::getInstance()->update(
            'tab',
            ['active' => 0],
            "module = 'vecelements'"
        ) && parent::disable($force_all);
    }

    public function addOverride( $classname )
    {
        try{
            return parent::addOverride($classname);
        }catch(Exception $ex){
            return false;
        }
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminVECSettings'));
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if(!Configuration::get("PS_ALLOW_HTML_\x49FRAME")) {
            Configuration::updateValue("PS_ALLOW_HTML_\x49FRAME", 1);
        }

        // Handle migrate
        if((Configuration::getGlobalValue('ce_migrate') || Tools::getIsset('VECMigrate')) && Db::getInstance()->executeS("SHOW TABLES LIKE '%_vec_meta'")){
            require_once _VEC_PATH_ . 'classes/VECMigrate.php';
            VECMigrate::registerJavascripts();
        }

        $footer_product = '';
        preg_match( '~/([^/]+)/(\d+)/edit\b~', $_SERVER['REQUEST_URI'], $req );
        $controller = Tools::strtolower(Tools::getValue('controller'));

        switch ($controller) {
            case 'adminvectemplates':
                $id_type = VEC\UId::TEMPLATE;
                $id = (int) Tools::getValue('id_vec_template');
                break;
            case 'adminvecheader':
                $id_type = VEC\UId::THEME;
                $id = (int) Tools::getValue('id_vec_theme');
                break;
            case 'adminvechome':
                $id_type = VEC\UId::THEME;
                $id = (int) Tools::getValue('id_vec_theme');
                break;
            case 'adminvecfooter':
                $id_type = VEC\UId::THEME;
                $id = (int) Tools::getValue('id_vec_theme');
                break;
            case 'adminveccontent':
                $id_type = VEC\UId::CONTENT;
                $id = (int) Tools::getValue('id_vec_content');
                break;
            case 'adminproducts':
                $id_type = VEC\UId::PRODUCT;
                $id = (int) Tools::getValue('id_product', basename(explode('?', $_SERVER['REQUEST_URI'])[0]));
                $footer_product = new VEC\UId(VECContent::getFooterProductId($id), VEC\UId::CONTENT, 0, $this->context->shop->id);
                break;
            case 'admincategories':
                $id_type = VEC\UId::CATEGORY;
                $id = (int) Tools::getValue('id_category', $req ? $req[2] : 0);
                break;
            case 'adminmanufacturers':
                $id_type = VEC\UId::MANUFACTURER;
                $id = (int) Tools::getValue('id_manufacturer', $req ? $req[2] : 0);
                break;
            case 'adminsuppliers':
                $id_type = VEC\UId::SUPPLIER;
                $id = (int) Tools::getValue('id_supplier', $req ? $req[2] : 0);
                break;
            case 'adminblogpost':
                $id_type = VEC\UId::SMARTBLOG_POST;
                $id = (int) Tools::getValue('id_smart_blog_post');
                break;
            case 'adminmaintenance':
                $id_type = VEC\UId::CONTENT;
                $id = VECContent::getMaintenanceId();

                $uids = VEC\UId::getBuiltList($id, $id_type, $this->context->shop->id);
                $hideEditor = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);
                break;
        }

        if (isset($id)) {
            self::$controller = $this->context->controller;
            self::$controller->addJQuery();
            self::$controller->js_files[] = $this->_path . 'views/js/admin.js?v=' . _VEC_VERSION_;
            self::$controller->css_files[$this->_path . 'views/css/admin.css?v=' . _VEC_VERSION_] = 'all';

            $uid = new VEC\UId($id, $id_type, 0, Shop::getContext() === Shop::CONTEXT_SHOP ? $this->context->shop->id : 0);

            isset($hideEditor) or $hideEditor = $uid->getBuiltLangIdList();

            Media::addJsDef([
                'vecAdmin' => [
                    'uid' => "$uid",
                    'editorUrl' => Tools::safeOutput($this->context->link->getAdminLink('AdminVECEditor') . '&uid='),
                    'footerProduct' => "$footer_product",
                    'hideEditor' => $hideEditor,
                    'i18n' => [
                        'edit' => str_replace("'", "’", $this->l('Edit with V-Elements')),
                        'save' => str_replace("'", "’", $this->l('Please save the form before editing with V-Elements')),
                        'error' => str_replace("'", "’", $this->getErrorMsg()),
                    ],
                    'languages' => Language::getLanguages(true, $uid->id_shop),
                ],
            ]);
            $this->context->smarty->assign('edit_with_vec', $this->context->link->getAdminLink('AdminVECEditor'));
        }
        return $this->context->smarty->fetch(_VEC_TEMPLATES_ . 'hook/backoffice_header.tpl');
    }

    protected function getErrorMsg()
    {
        if (!Configuration::get('PS_SHOP_ENABLE', null, null, $this->context->shop->id)) {
            $ips = explode(',', Configuration::get('PS_MAINTENANCE_IP', null, null, $this->context->shop->id));

            if (!in_array(Tools::getRemoteAddr(), $ips)) {
                return $this->l('The shop is in maintenance mode, please add your IP to whitelist');
            }
        }

        $id_tab = Tab::getIdFromClassName('AdminVECEditor');
        $access = Profile::getProfileAccess($this->context->employee->id_profile, $id_tab);

        if ('1' !== $access['view']) {
            return VEC\Helper::transError('You do not have permission to view this.');
        }

        $class = isset(self::$controller->className) ? self::$controller->className : '';

        if (in_array($class, $this->overrides)) {
            $loadObject = new ReflectionMethod(self::$controller, 'loadObject');
            $loadObject->setAccessible(true);

            if (empty($loadObject->invoke(self::$controller, true)->active) && !defined("$class::V`")) {
                return $this->l('You can not edit items which are not displayed, because an override file is missing. Please contact us on email: themevec@gmail.com');
            }
        }
        return '';
    }

    public function hookDisplayHeader()
    {
        self::$controller = $this->context->controller;

        $plugin = VEC\Plugin::instance();
        VEC\did_action('template_redirect') or VEC\do_action('template_redirect');

        if (self::getPreviewUId()) {
            if ('widget' === Tools::getValue('render') && Tools::getIsset('actions')) {
                $this->tplOverride = '';

                $request = json_decode(${'_POST'}['actions'], true);
                VEC\setup_postdata($request['editor_post_id']);
                $response = $plugin->widgets_manager->ajaxRenderWidget($request);
                empty($response) or http_response_code(200);
                die(json_encode($response));
            }
            header_register_callback(function () {
                header_remove('Content-Security-Policy');
                header_remove('X-Content-Type-Options');
                header_remove('X-Frame-Options');
                header_remove('X-Xss-Protection');
            });
            if (Tools::getValue('ctx') > Shop::CONTEXT_SHOP) {
                self::$controller->warning[] = VECSmarty::get(_VEC_TEMPLATES_ . 'admin/admin.tpl', 'ce_warning_multistore');
            }
        }
        
        require_once _VEC_PATH_ . 'classes/assets/CEAssetManager.php';
        
        CEAssetManager::instance();

        $uid_preview = self::getPreviewUId(false);

        if ($uid_preview && VEC\UId::CONTENT === $uid_preview->id_type) {
            Tools::getIsset('maintenance') && $this->displayMaintenancePage();
        }
    }

    public function hookOverrideLayoutTemplate($params)
    {
        if (false !== $this->tplOverride || !self::$controller) {
            return $this->tplOverride;
        }
        $this->tplOverride = '';

        if (self::isMaintenance()) {
            return $this->tplOverride;
        }
        // Page Content
        $controller = self::$controller;
        $tpl_vars = &$this->context->smarty->tpl_vars;
        $front = Tools::strtolower(preg_replace('/(ModuleFront)?Controller(Override)?$/i', '', get_class($controller)));
        // PrestaBlog fix for non-default blog URL
        stripos($front, 'prestablog') === 0 && 'prestablog' . Configuration::get('prestablog_urlblog') === $front && $front = 'prestablogblog';

        switch ($front) {
            case 'vecelementspreview':
                $model = self::getPreviewUId(false)->getModel();
                $key = $model::${'definition'}['table'];

                if (isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = ['description' => &$tpl_vars[$key]->value['content']];
                }
                break;
            case 'cms':
                $model = 'CMS';
                $key = $model::${'definition'}['table'];

                if (isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = ['description' => &$tpl_vars[$key]->value['content']];

                    VEC\add_action('wp_head', 'print_og_image');
                } elseif (isset($controller->cms->id)) {
                    $id = $controller->cms->id;
                    $desc = ['description' => &$controller->cms->content];
                }
                break;
            case 'product':
            case 'category':
            case 'manufacturer':
            case 'supplier':
                $model = $front;

                if (isset($tpl_vars[$model]->value['id'])) {
                    $id = $tpl_vars[$model]->value['id'];
                    $desc = &$tpl_vars[$model]->value;
                } elseif (method_exists($controller, "get$model") && Validate::isLoadedObject($obj = $controller->{"get$model"}())) {
                    $id = $obj->id;
                    $desc = ['description' => &$obj->description];
                }
                break;
            case 'smartblogdetails':
                $model = 'SmartBlogPost';

                if (isset($tpl_vars['post']->value['id_post'])) {
                    $id = $tpl_vars['post']->value['id_post'];
                    $desc = ['description' => &$tpl_vars['post']->value['content']];
                }
                break;
        }

        if (isset($id)) {
            $uid_preview = self::getPreviewUId();

            if ($uid_preview && $uid_preview->id === (int) $id && $uid_preview->id_type === VEC\UId::getTypeId($model)) {
                VEC\UId::$_ID = $uid_preview;
            } elseif (!VEC\UId::$_ID) {
                VEC\UId::$_ID = new VEC\UId($id, VEC\UId::getTypeId($model), $this->context->language->id, $this->context->shop->id);
            }

            if (VEC\UId::$_ID) {
                $this->filterBodyClasses();

                $desc['description'] = VEC\apply_filters('the_content', $desc['description']);
            }
        }

        // Theme Builder
        $themes = [
            'header' => Configuration::get('VEC_HEADER'),
            'footer' => Configuration::get('VEC_FOOTER'),
        ];
        $pages = [
            'index' => 'page-index',
            'contact' => 'page-contact',
            'pagenotfound' => 'page-not-found',
        ];
        foreach ($pages as $page_type => $theme_type) {
            if ($front === $page_type) {
                $themes[$theme_type] = Configuration::get(self::getThemeVarName($theme_type));
                break;
            }
        }
        $uid_preview = self::getPreviewUId(false);

        if ($uid_preview && VEC\UId::THEME === $uid_preview->id_type) {
            $preview = $this->renderTheme($uid_preview);

            $document = VEC\Plugin::$instance->documents->getDocForFrontend($uid_preview);
            $type_preview = $document->getTemplateType();
            $this->context->smarty->assign(self::getThemeVarName($type_preview), $preview);

            if (stripos($type_preview, 'page-') === 0) {
                VEC\UId::$_ID = $uid_preview;
                $desc = ['description' => &$preview];
                $this->filterBodyClasses();
                VEC\add_action('wp_head', 'print_og_image');
            }
            unset($themes[$type_preview]);
        }
        if (isset($pages[$front]) && !empty($themes[$pages[$front]])) {
            $theme_type = $pages[$front];
            VEC\UId::$_ID = new VEC\UId($themes[$theme_type], VEC\UId::THEME, $this->context->language->id, $this->context->shop->id);
            $desc = ['description' => $this->renderTheme(VEC\UId::$_ID)];
            $this->context->smarty->assign(self::getThemeVarName($theme_type), $desc['description']);
            $this->filterBodyClasses();
            VEC\add_action('wp_head', 'print_og_image');

            unset($themes[$theme_type]);
        }

        $this->tplOverride = VEC\apply_filters('template_include', $this->tplOverride);

        if (strrpos($this->tplOverride, 'layout-canvas') !== false) {
            empty($desc) or $this->context->smarty->assign('ce_desc', $desc);
        } else {
            foreach ($themes as $theme_type => $id_vec_theme) {
                empty($id_vec_theme) or $this->context->smarty->assign(
                    self::getThemeVarName($theme_type),
                    $this->renderTheme(new VEC\UId($id_vec_theme, VEC\UId::THEME, $this->context->language->id, $this->context->shop->id))
                );
            }
        }

        return $this->tplOverride;
    }

    public function hookDisplayOverrideTemplate($params)
    {
        
    }

    protected function filterBodyClasses()
    {
        $tpl_vars = &$this->context->smarty->tpl_vars;

        $body_classes = &$tpl_vars['page']->value['body_classes'];
        $body_classes['elementor-page'] = 1;
        $body_classes['elementor-page-' . VEC\get_the_ID()->toDefault()] = 1;
        
    }

    protected function displayMaintenancePage()
    {
        Configuration::set( 'PS_SHOP_ENABLE', false );
        Configuration::set( 'PS_MAINTENANCE_IP', '' );

        $displayMaintenancePage = new ReflectionMethod( $this->context->controller, 'displayMaintenancePage' );
        $displayMaintenancePage->setAccessible( true );
        $displayMaintenancePage->invoke( $this->context->controller );
    }

    public function hookDisplayMaintenance($params)
    {
        if (self::getPreviewUId(false)){
            http_response_code(200);
            header_remove('Retry-After');
        }else{
            $this->hookDisplayHeader();
        }

        VEC\add_filter('the_content', function(){
            $uid = VEC\get_the_ID();
            ${'this'}->context->smarty->assign(
                'vec_content', new VECContent($uid->id, $uid->id_lang, $uid->id_shop)
            );

            VEC\remove_all_filters('the_content');
        }, 0);

        if (!$maintenance = $this->renderContent('displayMaintenance', $params)){
            return;
        }
        
        self::$controller->registerJavascript('jquery', 'js/jquery/jquery-1.11.0.min.js');

        $this->unshiftTemplateDir(_VEC_TEMPLATES_ . 'front/theme/');

        $this->context->smarty->assign([
            'iso_code' => $this->context->language->iso_code,
            'favicon' => Configuration::get('PS_FAVICON'),
            'favicon_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
        ]);
        return $maintenance;
    }

    public function hookDisplayFooterProduct($params)
    {
        return $this->renderContent('displayFooterProduct', $params);
    }

    public function __call($method, $args)
    {
        if (stripos($method, 'hookActionObject') === 0 && stripos($method, 'DeleteAfter') !== false) {
            call_user_func_array([$this, 'hookActionObjectDeleteAfter'], $args);
        } elseif (stripos($method, 'hook') === 0) {
            // render this hook only after the Header inited or if it's the home page.
            if (false !== $this->tplOverride || !strcasecmp($method, 'hookDisplayHome')) {
                return $this->renderContent(Tools::substr($method, 4), $args);
            }
        } else {
            throw new Exception('Can not find method: ' . $method);
        }
    }

    public function renderContent($hook_name = null)
    {
        if (!$hook_name) {
            return;
        }
        $out = '';
        $rows = VECContent::getIdsByHook(
            $hook_name,
            $id_lang = $this->context->language->id,
            $id_shop = $this->context->shop->id,
            Tools::getValue('id_product', 0),
            self::getPreviewUId()
        );
        if ($rows) {
            $uid = VEC\UId::$_ID;

            foreach ($rows as $row) {
                VEC\UId::$_ID = new VEC\UId($row['id'], VEC\UId::CONTENT, $id_lang, $id_shop);

                $out .= VEC\apply_filters('the_content', '');
            }
            VEC\UId::$_ID = $uid;
        }
        return $out;
    }

    public function renderTheme($uid)
    {
        static $unshift;
        is_null($unshift) && $unshift = $this->unshiftTemplateDir(_VEC_TEMPLATES_ . 'front/theme/');

        $tmp = VEC\UId::$_ID;
        VEC\UId::$_ID = $uid;
        $out = VEC\apply_filters('the_content', '');
        VEC\UId::$_ID = $tmp;

        return $out;
    }

    protected function unshiftTemplateDir($path)
    {
        $tpl_dir = $this->context->smarty->getTemplateDir();
        $res = array_unshift($tpl_dir, $path);
        $this->context->smarty->setTemplateDir($tpl_dir);

        return $res;
    }

    public function registerHook($hook_name, $shop_list = null, $position = null){
        $res = parent::registerHook($hook_name, $shop_list);
        if($res){
            $this->updatePosition(Hook::getIdByName($hook_name), 0, $position);
        }
        return $res;
    }

    public function hookVECTemplate($params){
        if (empty($params['id']) || !Validate::isLoadedObject($tpl = new VECTemplate($params['id'])) || !$tpl->active) {
            return;
        }
        $uid = VEC\UId::$_ID;
        VEC\UId::$_ID = new VEC\UId($params['id'], VEC\UId::TEMPLATE);
        $out = VEC\apply_filters('the_content', '');
        VEC\UId::$_ID = $uid;

        return $out;
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $model = get_class($params['object']);
        $id_type = VEC\UId::getTypeId($model);
        $id_half = sprintf('%d%02d', $params['object']->id, $id_type);

        // Delete meta data
        Db::getInstance()->delete('vec_meta', "id LIKE '{$id_half}____'");

        // Delete CSS files
        $css_files = glob(_VEC_PATH_ . "views/css/ce/$id_half????.css", GLOB_NOSORT);

        foreach ($css_files as $css_file) {
            Tools::deleteFile($css_file);
        }
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->hookActionObjectDeleteAfter($params);

        // Delete displayFooterProduct content
        if ($id = VECContent::getFooterProductId($params['object']->id)) {
            $content = new VECContent($id);
            Validate::isLoadedObject($content) && $content->delete();
        }
    }

    public function hookActionProductAdd($params)
    {
        if (isset($params['request']) && $params['request']->attributes->get('action') === 'duplicate') {
            $id_product_duplicate = (int) $params['request']->attributes->get('id');
        }elseif(Tools::getIsset('duplicateproduct')){
            $id_product_duplicate = (int) Tools::getValue('id_product');
        }

        if (isset($id_product_duplicate, $params['id_product']) && $built_list = VEC\UId::getBuiltList($id_product_duplicate, VEC\UId::PRODUCT)){
            $db = VEC\Plugin::instance()->db;
            $uid = new VEC\UId($params['id_product'], VEC\UId::PRODUCT, 0);

            foreach($built_list as $id_shop => &$langs){
                foreach($langs as $id_lang => $uid_from){
                    $uid->id_lang = $id_lang;
                    $uid->id_shop = $id_shop;
                    $db->copyElementorMeta( $uid_from, $uid );
                }
            }
        }
    }

    protected function checkThemeChange()
    {
        if (!empty($this->context->shop->theme)) {
            $theme = $this->context->shop->theme->get('name');
            $vec_theme = Configuration::get('CE_THEME');

            if (empty($vec_theme)) {
                Configuration::updateValue('CE_THEME', $theme);
            } elseif ($vec_theme != $theme) {
                require_once _VEC_PATH_ . 'install/VecElementInstall.php';

                // register missing hooks after changing theme
                foreach (VecElementInstall::getHooks() as $hook) {
                    $this->registerHook($hook, null, 1);
                }
                Configuration::updateValue('CE_THEME', $theme);
            }
        }
    }

    public static function getPreviewUId($embed = true){
        static $res = null;

        if(null === $res && $res = Tools::getIsset('preview_id') && $uid = VEC\UId::parse(Tools::getValue('preview_id'))){
            $controller = $uid->getAdminController();
            $key = 'AdminBlogPosts' === $controller ? 'blogtoken' : 'adtoken';
            $res = self::hasAdminToken($controller, $key) ? $uid : false;
        }
        return !$embed || Tools::getIsset('ver') ? $res : false;
    }

    public static function hasAdminToken( $tab, $key = 'adtoken' )
    {
        $adtoken = Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee'));
        return Tools::getValue($key) == $adtoken;
    }

    public static function getThemeVarName( $theme_type )
    {
        return 'VEC_' . Tools::strtoupper(str_replace('-', '_', $theme_type));
    }

    public static function isMaintenance()
    {
        return !Configuration::get('PS_SHOP_ENABLE') && !in_array(Tools::getRemoteAddr(), explode(',', Configuration::get('PS_MAINTENANCE_IP')));
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
    
    public function getProducts($listing, $order_by, $order_dir, $limit, $id_category = 2, $products = [])
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
    
    public function installDemoData(){
        $languages = Language::getLanguages(false);
                
        $path = $this->getLocalPath() . 'install/import-data/';
        $fileList = array(
            'demo1-header',
            'demo1-homepage',
            'demo1-footer',
            'demo2-header',
            'demo2-homepage',
            'demo2-footer',
            'demo3-header',
            'demo3-homepage',
            'demo3-footer',
            'demo4-header',
            'demo4-homepage',
            'demo4-footer',
        );
        foreach($fileList as $fileName){
            $postarr = array();        
            $file = $path.$fileName.'.json';

            if(file_exists($file)){
                
                $content = file_get_contents($file, true);

                $uid = new UId(0, UId::THEME);

                $id_shop = $this->context->shop->id;
                $uid->id_shop = $id_shop;
                $post = WPPost::getInstance($uid);
                $postarr['post_author'] = \Context::getContext()->employee->id;
                $postarr['post_title'] = $fileName;
                $postarr['post_status'] = 'publish';
                $postarr['post_type'] = 'VECTheme';
                if (strpos($fileName, 'header') !== false) {
                    $postarr['template_type'] = 'header'; 
                }
                if (strpos($fileName, 'home') !== false) {
                    $postarr['template_type'] = 'page-index'; 
                }
                if (strpos($fileName, 'footer') !== false) {
                    $postarr['template_type'] = 'footer'; 
                }

                foreach ($postarr as $key => &$value) {
                    $post->$key = $value;
                }
                if ($post->_obj->add()) {
                    $uid->id = $post->_obj->id;
                    $post->ID = "$uid";
                } else {
                    $post->ID = 0;
                }
                
                //Insert to vec_meta table
                $names = [
                    '_elementor_data',
                    '_wp_page_template',
                    '_elementor_edit_mode',
                ];

                $table = 'vec_meta';
                $meta_data = array();
                $meta_data['id'] = $post->ID;

                foreach($names as $name){
                    $meta_data['name'] = $name;
                    if($name == '_elementor_data'){
                        $meta_data['value'] = $content;
                    }
                    if($name == '_wp_page_template'){
                        $meta_data['value'] = 'default';
                    }
                    if($name == '_elementor_edit_mode'){
                        $meta_data['value'] = 'builder';
                    }
                    
                    $result = Db::getInstance()->insert($table,$meta_data);
                }
                
            }
        }
    }
}