<?php
/*
* 2007-2016 PrestaShop
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
* Do not edit or add to this file if you wish to upgrade PrestaShop to newers
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class VecCookieLaw extends Module implements WidgetInterface
{
    protected $templateFile;

    public function __construct()
    {
        $this->name = 'veccookielaw';
        $this->version = '1.1.0';
        $this->author = 'ThemeVec';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Vec - Cookie Law');
        $this->description = $this->l('Show text about cookies in your shop for Eu Cookie Law');

        $this->templateFile = 'module:veccookielaw/views/templates/hook/veccookielaw.tpl';
    }

    public function install()
    {
        $this->_clearCache($this->templateFile);
        if (parent::install() && 
            $this->_createMenu() && 
            $this->registerHook('displayHeader') && 
            $this->registerHook('displayBeforeBodyClosingTag')) 
        {
            Configuration::updateValue($this->name . '_design', '1');
            Configuration::updateValue($this->name . '_bg_color', '#000000cc');
            Configuration::updateValue($this->name . '_color', '#ffffff');
            $values = array();
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang){
                $values['content'][$lang['id_lang']] = 'We use cookies to improve your online experience. By continuing browsing this website, we assume you agree our use of cookies.';
                $values['button'][$lang['id_lang']] = 'Agree';
            }
                
            Configuration::updateValue($this->name . '_content', $values['content'], true);
            Configuration::updateValue($this->name . '_button', $values['button'], true);
            
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        Configuration::deleteByName($this->name . '_design');
        Configuration::deleteByName($this->name . '_bg_color');
        Configuration::deleteByName($this->name . '_color');
        Configuration::deleteByName($this->name . '_content');
        Configuration::deleteByName($this->name . '_button');
        
        return parent::uninstall() && $this->deleteTab();
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
        $tab->class_name = "AdminVecCookieLaw";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Cookie law";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminVecCookieLaw');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    public function getContent()
    {
        $output = '';
        $output .= $this->getWarningMultishopHtml();
        
        if (Tools::isSubmit('submitModule')) {
            $values = array();
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang){
                $values['content'][$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
                $values['button'][$lang['id_lang']] = Tools::getValue('button_'.$lang['id_lang']);
            }
                
            Configuration::updateValue($this->name . '_content', $values['content'], true);
            Configuration::updateValue($this->name . '_button', $values['button'], true);
            Configuration::updateValue($this->name . '_design', Tools::getValue('design'));
            Configuration::updateValue($this->name . '_bg_color', Tools::getValue('bg_color'));
            Configuration::updateValue($this->name . '_color', Tools::getValue('color'));
                
            $output .= $this->displayConfirmation($this->l('Configuration updated'));
            $this->_clearCache($this->templateFile);
        }
        $output .= $this->renderForm();

        return $output;
    }

    public function renderForm()
    {
        $this->context->controller->addCSS($this->_path.'views/js/js-pickr.css');
        $this->context->controller->addJS($this->_path.'views/js/js-pickr.js');
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'image-select',
                        'label' => $this->l('Design'),
                        'name' => 'design',
                        'default_value' => 1,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('Design 1'),
                                    'img' => 'design1.jpg',
                                    ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('Design 2'),
                                    'img' => 'design2.jpg',
                                    ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Cookie law text'),
                        'name' => 'content',
                        'autoload_rte' => true,
                        'lang' => true,
                        'cols' => 60,
                        'rows' => 30,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Button text'),
                        'name' => 'button',
                        'lang' => true,
                        'class' => 'fixed-width-xxl',
                    ),
                    array(
                        'type' => 'color2',
                        'label' => $this->l('Background color'),
                        'name' => 'bg_color',
                        'size' => 30,
                    ),
                    array(
                        'type' => 'color2',
                        'label' => $this->l('Text color'),
                        'name' => 'color',
                        'size' => 30,
                    ),
                ),
                'submit' => array(
                    'name' => 'submitModule',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        if (Shop::isFeatureActive()) {
            $fields_form['form']['description'] = $this->l('The modifications will be applied to') . ' ' . (Shop::getContext() == Shop::CONTEXT_SHOP ? $this->l('shop') . ' ' . $this->context->shop->name : $this->l('all shops'));
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $fields = array();

        $fields['design'] = Configuration::get($this->name . '_design');
        $fields['bg_color'] = Configuration::get($this->name . '_bg_color');
        $fields['color'] = Configuration::get($this->name . '_color');

        foreach (Language::getLanguages(false) as $lang) {
            $fields['content'][(int) $lang['id_lang']] = Configuration::get($this->name . '_content', (int) $lang['id_lang']);
            $fields['button'][(int) $lang['id_lang']] = Configuration::get($this->name . '_button', (int) $lang['id_lang']);
        }

        return $fields;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerJavascript('modules'.$this->name.'-script', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 150]);

        $this->smarty->assign(
            array(
                'design' => Configuration::get($this->name . '_design'),
                'bg_color' => Configuration::get($this->name . '_bg_color'),
                'color' => Configuration::get($this->name . '_color'),
            ));
        return $this->display(__FILE__, 'views/templates/hook/cookielaw-header.tpl');
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        if (isset($_COOKIE['veccookielaw'])) {
            return;
        }
        if (!$this->isCached($this->templateFile, $this->getCacheId())) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId());
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }
        return array(
            'content' => Configuration::get($this->name . '_content', $this->context->language->id),
            'button' => Configuration::get($this->name . '_button', $this->context->language->id),
            'design' => Configuration::get($this->name . '_design'),
            'bg_color' => Configuration::get($this->name . '_bg_color'),
            'color' => Configuration::get($this->name . '_color'),
        );
    }
    
    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->l('You cannot manage module from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit') .
                '</p>';
        } else {
            return '';
        }
    }
}
