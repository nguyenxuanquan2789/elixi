<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class VecPopupNewsletter extends Module
{
    const GUEST_NOT_REGISTERED = -1;
    const CUSTOMER_NOT_REGISTERED = 0;
    const GUEST_REGISTERED = 1;
    const CUSTOMER_REGISTERED = 2;

    function __construct()
    {
		$this->name = 'vecpopupnewsletter';
		$this->tab = 'front_office_features';
		$this->version = '1.0.5';
		$this->author = 'ThemeVec';

		$this->controllers = array('verification', 'ajax');
		
		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Vec - Popup Newsletter');
		$this->description = $this->l('Shows popup newsletter window with your message');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->error = false;
        $this->valid = false;
        $this->_files = array(
            'name' => array('newsletter_conf', 'newsletter_voucher'),
            'ext' => array(
                0 => 'html',
                1 => 'txt',
            ),
        );
	}

	public function install()
	{		
		$this->context->controller->getLanguages();
		
		$title = array();
		$content = array();
		
		foreach ($this->context->controller->_languages as $lang){
		  $title[(int)$lang['id_lang']] = '<p>NEWSLETTER</p>';
		  $content[(int)$lang['id_lang']] = '<p>Sign up to our newsletter and get exclusive deals you won find any where else straight to your inbox!</p>';
		}
                		
		if (parent::install() && 
			$this->_createMenu() &&
			$this->registerHook('displayBeforeBodyClosingTag') && 
			$this->registerHook('registerGDPRConsent') && 
			$this->registerHook('header') &&
			Configuration::updateValue('VEC_NEWSLETTER_TEXT_COLOR', 'dark') &&
			Configuration::updateValue('VEC_NEWSLETTER_LAYOUT', 3) &&
			Configuration::updateValue('VEC_NEWSLETTER', true) &&
			Configuration::updateValue('VEC_NEWSLETTER_PAGES', true) &&
			Configuration::updateValue('VEC_NEWSLETTER_TITLE', $title, true) &&
			Configuration::updateValue('VEC_NEWSLETTER_TEXT', $content, true) &&
			Configuration::updateValue('VEC_NEWSLETTER_FORM', true) &&
			Configuration::updateValue('VEC_NEWSLETTER_BG', false) &&
			Configuration::updateValue('VEC_NEWSLETTER_BG_IMAGE', _MODULE_DIR_.$this->name.'/img/background_image1.jpg') && 
			Configuration::updateValue('VEC_NEWSLETTER_DELAY', 3000) &&
			Configuration::updateValue('VEC_NEWSLETTER_POPUP_START', '0000-00-00 00:00:00'))
			{
				return true;
			}
		return false;
	}
	
	public function uninstall()
	{
		return 
			$this->_deleteMenu() &&
			Configuration::deleteByName('VEC_NEWSLETTER_TEXT_COLOR') &&
			Configuration::deleteByName('VEC_NEWSLETTER_LAYOUT') &&
			Configuration::deleteByName('VEC_NEWSLETTER') &&
			Configuration::deleteByName('VEC_NEWSLETTER_PAGES') &&
			Configuration::deleteByName('VEC_NEWSLETTER_TITLE') &&	
			Configuration::deleteByName('VEC_NEWSLETTER_TEXT') &&	
			Configuration::deleteByName('VEC_NEWSLETTER_FORM') &&
			Configuration::deleteByName('VEC_NEWSLETTER_BG') &&
			Configuration::deleteByName('VEC_NEWSLETTER_BG_IMAGE') &&
			Configuration::deleteByName('VEC_NEWSLETTER_DELAY') &&
			Configuration::deleteByName('VEC_NEWSLETTER_POPUP_START') &&
			parent::uninstall();
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
        $tab->class_name = "AdminPopupNewsletter";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Popup newsletter";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminPopupNewsletter');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

	public function getContent()
	{
		$this->context->controller->addCSS($this->_path.'views/css/backend.css');
		$this->context->controller->getLanguages();
		$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        $errors = array();
                
		if (Tools::isSubmit('vec_submit')) {
			$this->registerHook('registerGDPRConsent');
			
			Configuration::updateValue('VEC_NEWSLETTER_TEXT_COLOR', Tools::getValue('VEC_NEWSLETTER_TEXT_COLOR'));
			Configuration::updateValue('VEC_NEWSLETTER_LAYOUT', (int)Tools::getValue('VEC_NEWSLETTER_LAYOUT'));
			Configuration::updateValue('VEC_NEWSLETTER', (bool)Tools::getValue('VEC_NEWSLETTER'));
			Configuration::updateValue('VEC_NEWSLETTER_PAGES', (bool)Tools::getValue('VEC_NEWSLETTER_PAGES'));
			Configuration::updateValue('VEC_NEWSLETTER_FORM', (bool)Tools::getValue('VEC_NEWSLETTER_FORM'));
			Configuration::updateValue('VEC_NEWSLETTER_BG', Tools::getValue('VEC_NEWSLETTER_BG'));
			if (Tools::isSubmit('VEC_NEWSLETTER_BG_IMAGE')){
				Configuration::updateValue('VEC_NEWSLETTER_BG_IMAGE', Tools::getValue('VEC_NEWSLETTER_BG_IMAGE'));
			}
			$message_trads = array();
			$message_trads2 = array();
			foreach ($_POST as $key => $value){
				if (preg_match('/VEC_NEWSLETTER_TITLE_/i', $key))
				{
					$id_lang = preg_split('/VEC_NEWSLETTER_TITLE_/i', $key);
					$message_trads2[(int)$id_lang[1]] = $value;
				}
				if (preg_match('/VEC_NEWSLETTER_TEXT_/i', $key))
				{
					$id_lang = preg_split('/VEC_NEWSLETTER_TEXT_/i', $key);
					$message_trads[(int)$id_lang[1]] = $value;
				}
			}
			Configuration::updateValue('VEC_NEWSLETTER_TEXT', $message_trads, true);
			Configuration::updateValue('VEC_NEWSLETTER_TITLE', $message_trads2, true);
			$start = Tools::getValue('VEC_NEWSLETTER_POPUP_START');
			if (!$start) {
				$start = '0000-00-00 00:00:00';
			}
			$end = Tools::getValue('VEC_NEWSLETTER_DELAY');
			if (!$end) {
				$end = '0000-00-00 00:00:00';
			}
			if ($end != '0000-00-00 00:00:00' && strtotime($end) < strtotime($start)) {
				$errors[] = $this->l('Invalid date range');
			} else {
				Configuration::updateValue('VEC_NEWSLETTER_DELAY', Tools::getValue('VEC_NEWSLETTER_DELAY'));
				Configuration::updateValue('VEC_NEWSLETTER_POPUP_START', Tools::getValue('VEC_NEWSLETTER_POPUP_START'));
			}
			if (count($errors)){
				$output .= $this->displayError(implode('<br />', $errors));
			} else {
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		return $output.$this->renderForm();
	}

	public function hookDisplayBeforeBodyClosingTag($params)
	{		
		if( !Configuration::get('VEC_NEWSLETTER')) return; 

		$this->templateFile = 'module:vecpopupnewsletter/views/templates/hook/popup-' . Configuration::get('VEC_NEWSLETTER_LAYOUT') . '.tpl';
		
		if (!$this->isCached($this->templateFile, $this->getCacheId())) {
			$this->context->smarty->assign(array(
				'vecpopup' => $this->getConfigFromDB(),
				'id_module' => $this->id,
			));		
		}
		
		return $this->fetch($this->templateFile, $this->getCacheId());
	}

	public function hookHeader($params)
	{
		$this->context->controller->addJS(($this->_path).'views/js/front.js');

        Media::addJsDef(array(
			'vecpopup' => array(
				'ajax' => $this->context->link->getModuleLink('ps_emailsubscription', 'subscription', array(), null, null, null, true),
				'time_delay' => Configuration::get('VEC_NEWSLETTER_DELAY'),
				'pp_start' => !(isset($_COOKIE['has_cookiepopupn']) || (!Configuration::get('VEC_NEWSLETTER_PAGES') && $this->context->controller->php_self != 'index'))
			)
        ));
	}
        
	public function setMedia()
	{
		parent::setMedia();
		$this->addJqueryUI('ui.datepicker');
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Module Appearance'),
					'icon' => 'icon-cogs'
				),
				'input' => array(	
					array(
						'type' => 'switch',
						'label' => $this->l('Active popup'),
						'name' => 'VEC_NEWSLETTER',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
					),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show popup in All pages'),
						'name' => 'VEC_NEWSLETTER_PAGES',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						'desc' => $this->l('select No: popup only appear in home page.')
					),
					array(
						'type' => 'image-select',
						'label' => $this->l('Popup layout'),
						'name' => 'VEC_NEWSLETTER_LAYOUT',
						'default_value' => 1,
						'options' => array(
							'query' => array(
								array(
									'id_option' => 1,
									'name' => $this->l('Type 1'),
									'img' => 'style1.jpg',
									),
								array(
									'id_option' => 2,
									'name' => $this->l('Type 2'),
									'img' => 'style2.jpg',
									),
								array(
									'id_option' => 3,
									'name' => $this->l('Type 3'),
									'img' => 'style3.jpg',
									),
							),
							'id' => 'id_option',
							'name' => 'name',
						),
					),
					array(
							'type' => 'textarea',
							'name' => 'VEC_NEWSLETTER_TITLE',
							'label' => $this->l('Popup title'),
							'rows' => 10,
							'cols' => 40,
							'required' => false,
							'lang' => true,
							'autoload_rte' => true
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Popup content'),
						'name' => 'VEC_NEWSLETTER_TEXT',
						'rows' => 10,
						'cols' => 40,
						'lang' => true,
						'autoload_rte' => true
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Newsletter form in popup'),
						'name' => 'VEC_NEWSLETTER_FORM',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('No')
									)
								),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show background image'),
						'name' => 'VEC_NEWSLETTER_BG',
						'is_bool' => true,
						'values' => array(
									array(
										'id' => 'active_on',
										'value' => true,
										'label' => $this->l('Yes')
									),
									array(
										'id' => 'active_off',
										'value' => false,
										'label' => $this->l('No')
									)
								),
						),
                    array(
						'type' => 'background_image',
						'label' => $this->l('Popup background image'),
						'name' => 'VEC_NEWSLETTER_BG_IMAGE',
						'size' => 30,
					),
					array(
						'type' => 'select',
						'name' => 'VEC_NEWSLETTER_TEXT_COLOR',
						'label' => $this->l('Text color'),
						'class' => 'fixed-width-xxl',
						'required' => false,
						'options' => array(
							'query' => array(
									array('value'=>'dark','name'=>$this->l('Dark')),
									array('value'=>'light','name'=>$this->l('Light')),
								),
							'id' => 'value',
							'name' => 'name'
						)
					),
					array(
							'type' => 'hidden',
							'label' => $this->l('Countdown from'),
							'name' => 'VEC_NEWSLETTER_POPUP_START',
							'size' => 10,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Delays show popup'),
						'name' => 'VEC_NEWSLETTER_DELAY',
						'required' => false,
						'class' => 'fixed-width-xxl',
						'suffix' => 'million seconds'
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);
		

		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language){
			$languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
		}

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'vec_submit';
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
		);
		return $helper->generateForm(array($fields_form));
	}

	private function initToolbar()
	{
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);

		return $this->toolbar_btn;
	}
	
	public function getConfigFieldsValues()
	{
		$values = array(
			'VEC_NEWSLETTER_TEXT_COLOR' => Tools::getValue('VEC_NEWSLETTER_TEXT_COLOR', Configuration::get('VEC_NEWSLETTER_TEXT_COLOR')),
			'VEC_NEWSLETTER_LAYOUT' => Tools::getValue('VEC_NEWSLETTER_LAYOUT', Configuration::get('VEC_NEWSLETTER_LAYOUT')),
			'VEC_NEWSLETTER_PAGES' => Tools::getValue('VEC_NEWSLETTER_PAGES', Configuration::get('VEC_NEWSLETTER_PAGES')),
			'VEC_NEWSLETTER' => Tools::getValue('VEC_NEWSLETTER', Configuration::get('VEC_NEWSLETTER')),
			'VEC_NEWSLETTER_FORM' => Tools::getValue('VEC_NEWSLETTER_FORM', Configuration::get('VEC_NEWSLETTER_FORM')),
			'VEC_NEWSLETTER_BG' => Tools::getValue('VEC_NEWSLETTER_BG', Configuration::get('VEC_NEWSLETTER_BG')),
			'VEC_NEWSLETTER_BG_IMAGE' => Tools::getValue('VEC_NEWSLETTER_BG_IMAGE', Configuration::get('VEC_NEWSLETTER_BG_IMAGE')),
			'VEC_NEWSLETTER_DELAY' => Tools::getValue('VEC_NEWSLETTER_DELAY', Configuration::get('VEC_NEWSLETTER_DELAY')),
			'VEC_NEWSLETTER_POPUP_START' => Tools::getValue('VEC_NEWSLETTER_POPUP_START', Configuration::get('VEC_NEWSLETTER_POPUP_START')),
		);

		foreach (Language::getLanguages(false) as $lang){
			$values['VEC_NEWSLETTER_TITLE'][(int)$lang['id_lang']] =html_entity_decode(Configuration::get('VEC_NEWSLETTER_TITLE', (int)$lang['id_lang']));
			$values['VEC_NEWSLETTER_TEXT'][(int)$lang['id_lang']] =html_entity_decode(Configuration::get('VEC_NEWSLETTER_TEXT', (int)$lang['id_lang']));
		}
		return $values;
	}

	public function getConfigFromDB()
	{
		$now = date('Y-m-d H:i:00');
		$start_date = (Configuration::get('VEC_NEWSLETTER_POPUP_START') ? Configuration::get('VEC_NEWSLETTER_POPUP_START'): '0000-00-00 00:00:00');
		if (strtotime($start_date) > strtotime($now)){
			$end_date = "0000-00-00 00:00:00";
		} else {
			$end_date = (Configuration::get('VEC_NEWSLETTER_DELAY') ? Configuration::get('VEC_NEWSLETTER_DELAY'): '0000-00-00 00:00:00');
		}
		return array(
			'VEC_NEWSLETTER_TEXT_COLOR' => (Configuration::get('VEC_NEWSLETTER_TEXT_COLOR') ? Configuration::get('VEC_NEWSLETTER_TEXT_COLOR'): 'dark'),
			'VEC_NEWSLETTER_LAYOUT' => (Configuration::get('VEC_NEWSLETTER_LAYOUT') ? Configuration::get('VEC_NEWSLETTER_LAYOUT'): 1),
			'VEC_NEWSLETTER' => (Configuration::get('VEC_NEWSLETTER') ? Configuration::get('VEC_NEWSLETTER'): false),
			'VEC_NEWSLETTER_PAGES' => (Configuration::get('VEC_NEWSLETTER_PAGES') ? Configuration::get('VEC_NEWSLETTER_PAGES'): false),
			'VEC_NEWSLETTER_FORM' => (Configuration::get('VEC_NEWSLETTER_FORM') ? Configuration::get('VEC_NEWSLETTER_FORM'): false),
			'VEC_NEWSLETTER_TEXT' => html_entity_decode(Configuration::get('VEC_NEWSLETTER_TEXT', $this->context->language->id) ? Configuration::get('VEC_NEWSLETTER_TEXT', $this->context->language->id): false),
            'VEC_NEWSLETTER_TITLE' => html_entity_decode(Configuration::get('VEC_NEWSLETTER_TITLE', $this->context->language->id) ?  Configuration::get('VEC_NEWSLETTER_TITLE', $this->context->language->id): false),
			'VEC_NEWSLETTER_BG' => (Configuration::get('VEC_NEWSLETTER_BG') ? Configuration::get('VEC_NEWSLETTER_BG'): 0),
			'VEC_NEWSLETTER_BG_IMAGE' => (Configuration::get('VEC_NEWSLETTER_BG_IMAGE') ? Configuration::get('VEC_NEWSLETTER_BG_IMAGE'): 0),
            'VEC_NEWSLETTER_DELAY' => $end_date
		);
	}
}