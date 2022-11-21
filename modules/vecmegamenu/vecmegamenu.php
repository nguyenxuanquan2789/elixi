<?php
/**
* 2007-2014 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

include_once(_PS_MODULE_DIR_.'vecmegamenu/src/vecMegamenuClass.php');
include_once(_PS_MODULE_DIR_.'vecmegamenu/src/vecMegamenuSubmenuClass.php');
include_once(_PS_MODULE_DIR_.'vecmegamenu/src/vecMegamenuSubmenuRowClass.php');
include_once(_PS_MODULE_DIR_.'vecmegamenu/src/vecMegamenuSubmenuColumnClass.php');
include_once(_PS_MODULE_DIR_.'vecmegamenu/src/vecMegamenuSubmenuItemClass.php');
include_once(_PS_MODULE_DIR_.'vecmegamenu/sql/vecSampleDataMenu.php');

class VecMegamenu extends Module 
{	

	private $html = '';
	
	public function __construct()
	{	
		$this->name = 'vecmegamenu';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'ThemeVec';
		$this->need_instance = 1;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('Vec - Megamenu');
		$this->description = $this->l('Megamenu custommer module');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
		$this->secure_key = Tools::encrypt($this->name);
	}

	public function install()
	{
		if (parent::install() &&
			$this->registerHook('header') &&
			$this->registerHook('displayMegamenu') &&
			$this->registerHook('displayMegamenuMobileTop') &&
			$this->registerHook('displayMegamenuMobileBottom') &&
			$this->registerHook('actionShopDataDuplication') &&
			$this->_createMenu() &&
			$this->registerHook('actionObjectLanguageAddAfter'))
			{
				include(dirname(__FILE__).'/sql/install.php');
				$sample_data = new VecSampleDataMenu();
			    $sample_data->initData();
				$this->generateCss();
				return true;
			}
		return false;
	}
    
	public function uninstall()
	{
		include(dirname(__FILE__).'/sql/uninstall.php');
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
        $tab->class_name = "AdminVecMegamenu";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Horizontal menu";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminVecMegamenu');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }
	
	public function getContent()
	{
		if (Tools::isSubmit('submitMenuItem') || Tools::isSubmit('delete_id_menu') || Tools::isSubmit('changeStatus') || Tools::isSubmit('removeIcon'))
		{
			$this->_postProcess();
			$this->html .= $this->renderList();
		}
		elseif (Tools::isSubmit('buildMenu') && Tools::isSubmit('id_vecmegamenu_item') )
		{
			$this->_postProcess();
			$this->html .= $this->renderBuildMenu();
		}
		elseif (Tools::isSubmit('addMenu') || Tools::isSubmit('editMenu'))
			$this->html .= $this->renderAddForm();
		else
		{
			$this->_postProcess();
			$this->context->smarty->assign('module_dir', $this->_path);
			$this->html .= $this->renderList();
		}
		return $this->html;
	}
	

	public function renderList()
	{	
		if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) return '<p class="alert alert-warning">'.
			$this->l('You have to configure this module for each shop').
		'</p>';
		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addCSS($this->_path.'views/css/back.css');
		$this->context->controller->addJS($this->_path.'views/js/jquery.colorpicker.js');
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;
		$info_menus = $this->getMenuInfo();

		foreach ($info_menus as $key => $info_menu)
		{
			$info_menus[$key]['status'] = $this->displayStatus($info_menu['id_vecmegamenu_item'], $info_menu['active']);
		}
		$this->context->smarty->assign(
			array(
				'link' => $this->context->link,
				'info_menus' => $info_menus,
				'url_base' => $this->context->shop->physical_uri.$this->context->shop->virtual_uri,
				'secure_key' => $this->secure_key,
			)
		);
		return $this->display(__FILE__, 'views/templates/admin/list.tpl');
	}

	public function displayStatus($id_vecmegamenu_item, $active)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
		$class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
		$html = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
			'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_vecmegamenu_item='.(int)$id_vecmegamenu_item.'" title="'.$title.'"><i class="'.$icon.'"></i> '.$title.'</a>';

		return $html;
	}
	
	public function getMenuInfo($active = null)
	{
		$this->context = Context::getContext();
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;
		
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT pi.*, pil.*
			FROM '._DB_PREFIX_.'vecmegamenu_item pi
			LEFT JOIN '._DB_PREFIX_.'vecmegamenu_item_lang pil ON pi.id_vecmegamenu_item = pil.id_vecmegamenu_item 
			LEFT JOIN '._DB_PREFIX_.'vecmegamenu_item_shop pis ON pi.id_vecmegamenu_item = pis.id_vecmegamenu_item 
			WHERE pis.id_shop = '.$id_shop.' AND pil.id_lang = '.$id_lang.($active ? ' AND pi.`active` = 1' : ' ').' ORDER BY pi.position ASC, pi.id_vecmegamenu_item ASC'
		);
	}
	protected function _postProcess()
	{
		$errors = array();
		if (Tools::isSubmit('submitMenuItem'))
		{
			$this->_clearCache('vecmegamenu.tpl');

			if (Tools::getValue('id_vecmegamenu_item'))
			{
				$menu_item = new VecMegamenuClass((int)Tools::getValue('id_vecmegamenu_item'));
				if (!Validate::isLoadedObject($menu_item))
				{
					$this->html .= $this->displayError($this->l('Invalid id_vecmegamenu_item'));
					return false;
				}
			}
			else{
				$menu_item = new VecMegamenuClass();
			}
			
			
			$menu_item->active = (int)Tools::getValue('active');
			$menu_item->type_link = Tools::getValue('type_link');
			$menu_item->link = Tools::getValue('link');
			$menu_item->type_icon = Tools::getValue('type_icon');
			$menu_item->icon_class = Tools::getValue('icon_class');
			$menu_item->icon = Tools::getValue('icon_img');

			$menu_item->item_class  = Tools::getValue('item_class');
			$menu_item->new_window  = Tools::getValue('new_window');
			$menu_item->submenu_type  = Tools::getValue('submenu_type');

			$menu_item->subtitle_bg_color  = Tools::getValue('subtitle_bgcolor');
			$menu_item->subtitle_color  = Tools::getValue('subtitle_color');
			$menu_item->subtitle_fontsize  = Tools::getValue('subtitle_fontsize');
			$menu_item->subtitle_lineheight  = Tools::getValue('subtitle_lineheight');
			
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{	
				
				$menu_item->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
				$menu_item->custom_link[$language['id_lang']] = Tools::getValue('custom_link_'.$language['id_lang']);
				$menu_item->subtitle[$language['id_lang']] = Tools::getValue('subtitle_'.$language['id_lang']);	
			}
			if (!$errors)
			{
				if (!Tools::getValue('id_vecmegamenu_item'))
				{
					$menu_item->position = VecMegamenuClass::getLastPosition()+1;
					if (!$menu_item->add())
					{
						$errors[] = $this->displayError($this->l('The menu_item could not be added.'));
						
						Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=1&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
					}
					$this->generateCss();
				}
				else
				{
					if (!$menu_item->update())
					{
						$errors[] = $this->displayError($this->l('The menu_item could not be updated.'));
						Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=1&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
					}
					$this->generateCss();
					$this->html .= $this->displayConfirmation($this->l('Your configuration is saved.'));
				}
			}
			return $errors;
		}
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_vecmegamenu_item'))
		{
			$this->_clearCache('vecmegamenu.tpl');
			$menu = new VecMegamenuClass((int)Tools::getValue('id_vecmegamenu_item'));
			if ($menu->active == 0)
				$menu->active = 1;
			else
				$menu->active = 0;
			$res = $menu->update();
			$this->html .= ($res ? $this->displayConfirmation($this->l('Configuration updated')) : $this->displayError($this->l('The configuration could not be updated.')));
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=6&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
		}
		elseif (Tools::isSubmit('delete_id_menu'))
		{
			$this->_clearCache('vecmegamenu.tpl');
			$menu_item = new VecMegamenuClass((int)Tools::getValue('delete_id_menu'));
			$res = $menu_item->delete();
			if (!$res)
				$this->html .= $this->displayError('Could not delete.');
			else{
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=1&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&id_vecmegamenu_item='.Tools::getValue('id_vecmegamenu_item'));
				$this->generateCss();
			}
		}
		elseif(Tools::isSubmit('submitSubmenu')){
			$submenu_item = new VecMegamenuSubmenuClass((int)Tools::getValue('id_vecmegamenu_item'));	
			
			$submenu_item->id_vecmegamenu_item = (int)Tools::getValue('id_vecmegamenu_item');
			$submenu_item->submenu_width = Tools::getValue('submenu_width');
			$submenu_item->submenu_class = Tools::getValue('submenu_class');
			$submenu_item->submenu_bg = Tools::getValue('submenu_bg');
			$submenu_item->submenu_bg_color = Tools::getValue('submenu_bg_color');
			$submenu_item->submenu_bg_image = Tools::getValue('submenu_bg_image');
			$submenu_item->submenu_bg_repeat = (int)Tools::getValue('submenu_bg_repeat');
			$submenu_item->submenu_bg_position  = (int)Tools::getValue('submenu_bg_position');
			
			if (!$errors)
			{
				$submenu_item->update();
				$this->generateCss();
				$this->html .= $this->displayConfirmation($this->l('Your configuration is saved.'));
			}
			return $errors;
		}
		
	}
	
	public function renderAddForm()
	{
		$this->context->controller->addJS($this->_path.'views/js/jquery.colorpicker.js');
		$this->context->controller->addJS($this->_path.'views/js/admin.js');
		$this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.min.css');
        $this->context->controller->addCSS($this->_path.'views/css/elixi-icon.min.css');
		$id_vecmegamenu_item = Tools::getValue('id_vecmegamenu_item');
		if (isset($id_vecmegamenu_item))
			$menu = new VecMegamenuClass($id_vecmegamenu_item);
		else
			$menu = new VecMegamenuClass();
		//echo '<pre>'; print_r($menu);die;
		$languages = $this->context->controller->getLanguages();
		$this->context->smarty->assign(
			array(
				'languages' => $languages,
				'id_language' => (int)$this->context->language->id,
				'token' => Tools::getAdminTokenLite('AdminModules'),
				'all_options' => $this->getAllDefaultLink(),
				'menu' => $menu,
				'image_baseurl' => $this->_path.'views/img/icons/',
				'vecicons' => $this->getIconsVec(),
				'awesomeicons' => $this->getIconsAwesome(),
			)
		);
		return $this->display(__FILE__, 'views/templates/admin/menu_item.tpl');
	}
	
	public function renderBuildMenu()
	{
		$this->context->controller->addJqueryUI('ui.sortable');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js');
		$this->context->controller->addJS(__PS_BASE_URI__.'js/admin/tinymce.inc.js');
		$this->context->controller->addJS($this->_path.'views/js/jquery.colorpicker.js');
		$this->context->controller->addJS($this->_path.'views/js/back.js');
		$this->context->controller->addJS($this->_path.'views/js/admin.js');
		$this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->controller->addCSS($this->_path.'views/css/font-awesome.min.css');
        $this->context->controller->addCSS($this->_path.'views/css/elixi-icon.min.css');

		$languages = $this->context->controller->getLanguages();
		$id_vecmegamenu_item = Tools::getValue('id_vecmegamenu_item', 1);
		$submenu_info = $this->getSubmenuInfo($id_vecmegamenu_item);
		$info_rows = $this->getRowInfo($id_vecmegamenu_item);
		
		foreach ($info_rows as $key => $info_row)
		{
			$info_rows[$key]['list_col'] = $this->getColInfo($info_row['id_row']);
		}
		//echo '<pre>'; print_r($info_rows);die;
		$this->context->smarty->assign(
			array(
				'id_vecmegamenu_item' => $id_vecmegamenu_item,
				'token' => Tools::getAdminTokenLite('AdminModules'),
				'info_rows' => $info_rows,
				'submenu_info' => $submenu_info,
				'link' => $this->context->link,
				'url_base' => $this->context->shop->physical_uri.$this->context->shop->virtual_uri,
				'secure_key' => $this->secure_key,
				'ps_links' => $this->getAllDefaultLink(),
				'manufacturers' => $this->getManufacturers(),
				'category_links' => $this->getCategoryOption(1, (int)$this->context->language->id, false, true, false),
				'languages' => $languages,
				'id_language' => (int)$this->context->language->id,
			)
		);
		return $this->display(__FILE__, 'views/templates/admin/build_menu.tpl').$this->display(__FILE__, 'views/templates/admin/menu_submenu_config.tpl');
	}

	// this function is used for ajax reload
	public function renderSubmenu()
    {  
    	$id_vecmegamenu_item = Tools::getValue('id_vecmegamenu_item', 1);
		$info_rows = $this->getRowInfo($id_vecmegamenu_item);
		$languages = $this->context->controller->getLanguages();
		
		foreach ($info_rows as $key => $info_row)
		{
			$info_rows[$key]['list_col'] = $this->getColInfo($info_row['id_row']);
		}
		return $this->render('views/templates/admin/build_menu.tpl', array(
			'id_vecmegamenu_item' => $id_vecmegamenu_item,
			'token' => Tools::getAdminTokenLite('AdminModules'),
			'info_rows' => $info_rows,
			'link' => $this->context->link,
			'url_base' => $this->context->shop->physical_uri.$this->context->shop->virtual_uri,
			'secure_key' => $this->secure_key,
			'ps_links' => $this->getAllDefaultLink(),
			'category_links' => $this->getCategoryOption(1, (int)$this->context->language->id, false, true, false),
			'manufacturers' => $this->getManufacturers(),
			'languages' => $languages,
			'id_language' => (int)$this->context->language->id,
		));
		
    }
    public function render($template, $params = array())
    {
        $this->smarty->assign($params);
        return $this->display(__FILE__, $template);
    }
	public function getSubmenuInfo($id_menu, $active = null)
	{
		$id_shop = (int)$this->context->shop->id;
		$row_infos = array();
		$row_infos_rs = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT ps.*
			FROM '._DB_PREFIX_.'vecmegamenu_submenu ps
			WHERE ps.id_vecmegamenu_item = '.$id_menu.($active ? ' AND ps.`active` = 1' : ' ')
		);
		if (is_array($row_infos_rs) && count($row_infos_rs) > 0)
			$row_infos = $row_infos_rs;
		return $row_infos;
	}
	public function getRowInfo($id_menu, $active = null)
	{
		$row_infos = array();
		$row_infos_rs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT sr.*
			FROM '._DB_PREFIX_.'vecmegamenu_submenu_row sr
			WHERE sr.id_vecmegamenu_item = '.$id_menu.($active ? ' AND sr.`active` = 1' : '').' ORDER BY sr.position ASC'
		);
		if (is_array($row_infos_rs) && count($row_infos_rs) > 0)
			$row_infos = $row_infos_rs;
		return $row_infos;
	}
	
	public function getColInfo($id_row, $active = null, $is_backend = true)
	{
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;
		$col_infos = array();
		
		$cols_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT sc.*, scl.title, scl.custom_link
			FROM '._DB_PREFIX_.'vecmegamenu_submenu_column sc
			LEFT JOIN '._DB_PREFIX_.'vecmegamenu_submenu_column_lang scl ON (sc.id_vecmegamenu_submenu_column = scl.id_vecmegamenu_submenu_column)
			WHERE  sc.id_row = '.$id_row.'  AND scl.`id_lang` = '.$id_lang.'  AND sc.`active` = 1
			ORDER BY sc.position ASC, sc.id_vecmegamenu_submenu_column ASC'
		);
		if (is_array($cols_result) && count($cols_result) > 0)
			$col_infos = $cols_result;
		
		if (is_array($col_infos) && count($col_infos) > 0)
			foreach ($col_infos as $key => $col_info)
			{
				
				$sub_menu_infos = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT psi.*,psil.*
					FROM '._DB_PREFIX_.'vecmegamenu_submenu_item psi
					LEFT JOIN '._DB_PREFIX_.'vecmegamenu_submenu_item_lang psil ON psi.id_vecmegamenu_submenu_item = psil.id_vecmegamenu_submenu_item
					WHERE psi.id_vecmegamenu_submenu_column = '.$col_info['id_vecmegamenu_submenu_column'].' AND psil.id_lang = '.$id_lang.($active ? ' AND psi.`active` = 1' : '').' ORDER BY psi.position ASC, psi.id_vecmegamenu_submenu_item ASC');
				if (is_array($sub_menu_infos) && count($sub_menu_infos) > 0)
				{	
					foreach ($sub_menu_infos as $key1 => $sub_menu_info)
					{	
						$id_vecmegamenu_item = Tools::getValue('id_vecmegamenu_item', 1);
						//if (isset($id_vecmegamenu_item) && $id_vecmegamenu_item > 0 && $is_backend)
						$sub_menu_infos[$key1]['active'] = $sub_menu_info['active'];
						switch ($sub_menu_info['type_link']) {
							case 1: // category tree
						        $id_category = Tools::substr($sub_menu_info['category_tree'], 3, Tools::strlen($sub_menu_info['category_tree']) - 3);
						        $category = new Category($id_category, $this->context->language->id);
								$sub_menu_infos[$key1]['categories'] = $this->getCategories($category);
								//echo '<pre>'; print_r($sub_menu_infos[$key1]['categories']);die;
								$sub_menu_infos[$key1]['title'] = 'Category tree: '.$category->name;
						        break;
						    case 2: // ps links
						        $menu_info = $this->fomartLink($sub_menu_info, $id_lang, true);
								$sub_menu_infos[$key1]['link'] = $menu_info['link'];
								$sub_menu_infos[$key1]['title'] = $menu_info['title'];
						        break;
						    case 3: // custom link
						        $sub_menu_infos[$key1]['title'] = $sub_menu_info['customlink_title'];
						        break;
						    case 4: // product
						        $id_prod = (int)$sub_menu_info['id_product'];
								if (isset($id_prod) && $id_prod > 0)
								{	
									$productName = Product::getProductName($id_prod);
								}
								$sub_menu_infos[$key1]['title'] = 'Product: '.$productName.' - ID: '.$id_prod;
						        break;
						    case 5: // banner image
						        $sub_menu_infos[$key1]['title'] = 'Banner image';
						        break;
						    case 6: // html
						        $sub_menu_infos[$key1]['title'] = 'HTML content';
						        break;
						    case 7: // manufacturer
						        $sub_menu_infos[$key1]['title'] = 'Brand - ID: '.(int)$sub_menu_info['id_manufacturer'];
						        $sub_menu_infos[$key1]['manufacturer_logo'] = $this->context->link->getManufacturerImageLink((int)$sub_menu_info['id_manufacturer']);
						        $sub_menu_infos[$key1]['link'] = $this->context->link->getManufacturerLink((int)$sub_menu_info['id_manufacturer']);

						        break;
						}
						
					}
					
					$col_infos[$key]['list_menu_item'] = $sub_menu_infos;
				}
				else
					$col_infos[$key]['list_menu_item'] = array();
			}
		return $col_infos;
	}
	public function getColInfoFront($id_row, $active = null, $is_backend = true)
	{
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;
		$col_infos = array();
		
		$cols_result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT sc.*, scl.title, scl.custom_link
			FROM '._DB_PREFIX_.'vecmegamenu_submenu_column sc
			LEFT JOIN '._DB_PREFIX_.'vecmegamenu_submenu_column_lang scl ON (sc.id_vecmegamenu_submenu_column = scl.id_vecmegamenu_submenu_column)
			WHERE  sc.id_row = '.$id_row.'  AND scl.`id_lang` = '.$id_lang.'  AND sc.`active` = 1
			ORDER BY sc.position ASC, sc.id_vecmegamenu_submenu_column ASC'
		);
		if (is_array($cols_result) && count($cols_result) > 0)
			$col_infos = $cols_result;
		
		if (is_array($col_infos) && count($col_infos) > 0)
			foreach ($col_infos as $key => $col_info)
			{
				//	echo '<pre>'; print_r($col_info);die;
				if($col_info['type_link'] == 0){
					$col_title_link = $this->fomartLink($col_info, $id_lang);
					$col_infos[$key]['link']= $col_title_link['link'];
				}

				$sub_menu_infos = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT psi.*,psil.*
					FROM '._DB_PREFIX_.'vecmegamenu_submenu_item psi
					LEFT JOIN '._DB_PREFIX_.'vecmegamenu_submenu_item_lang psil ON psi.id_vecmegamenu_submenu_item = psil.id_vecmegamenu_submenu_item
					WHERE psi.id_vecmegamenu_submenu_column = '.$col_info['id_vecmegamenu_submenu_column'].' AND psil.id_lang = '.$id_lang.($active ? ' AND psi.`active` = 1' : '').' ORDER BY psi.position ASC, psi.id_vecmegamenu_submenu_item ASC');
				if (is_array($sub_menu_infos) && count($sub_menu_infos) > 0)
				{
					foreach ($sub_menu_infos as $key1 => $sub_menu_info)
					{	
						$id_vecmegamenu_item = Tools::getValue('id_vecmegamenu_item', 1);
						$sub_menu_infos[$key1]['active'] = $sub_menu_info['active'];
						switch ($sub_menu_info['type_link']) {
							case 1: // category tree
						        $id_category = Tools::substr($sub_menu_info['category_tree'], 3, Tools::strlen($sub_menu_info['category_tree']) - 3);
						        $category = new Category($id_category, $this->context->language->id);
								$sub_menu_infos[$key1]['categories'] = $this->getCategories($category);
						        break;
						    case 2: // ps links
						        $menu_info = $this->fomartLink($sub_menu_info, $id_lang, true);
								$sub_menu_infos[$key1]['link'] = $menu_info['link'];
								$sub_menu_infos[$key1]['title'] = $menu_info['title'];
						        break;
						    case 3: // custom link
						        break;
						    case 4: // product
						        $id_prod = (int)$sub_menu_info['id_product'];
								if (isset($id_prod) && $id_prod > 0)
								{	
									$productInfo = $this->getProducts($id_prod);
								}
								$sub_menu_infos[$key1]['product'] = $productInfo[0];
						        break;
						    case 5: // banner image
								$sub_menu_infos[$key1]['image']= str_replace('/pos_ecolife_organic/',__PS_BASE_URI__,$sub_menu_infos[$key1]['image']);
						        break;
						    case 6: // html
								$sub_menu_infos[$key1]['htmlcontent']= str_replace('/pos_ecolife_organic/',__PS_BASE_URI__,$sub_menu_infos[$key1]['htmlcontent']);
						        break;
						    case 7: // manufacturer
						        $sub_menu_infos[$key1]['manufacturer_logo'] = $this->context->link->getManufacturerImageLink((int)$sub_menu_info['id_manufacturer']);
						        $sub_menu_infos[$key1]['link'] = $this->context->link->getManufacturerLink((int)$sub_menu_info['id_manufacturer']);

						        break;
						}
						
					}
					
					$col_infos[$key]['list_menu_item'] = $sub_menu_infos;
				}
				else
					$col_infos[$key]['list_menu_item'] = array();
			}
		return $col_infos;
	}

	public function getProducts($id_product)
    {

        $product_info = $this->getProductByID($id_product);
        if($product_info)
        $result = $product_info;

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

        $products_for_template = [];

        foreach ($result as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $products_for_template;
    }
	public function getProductByID($id_product){
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang =(int) Context::getContext()->language->id;
        $id_shop = (int) Context::getContext()->shop->id;

        $sql = 'SELECT p.*, product_shop.*,  pl.`description`, pl.`description_short`, pl.`available_now`,
                    pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image` id_image,
                    il.`legend` as legend, m.`name` AS manufacturer_name,
                    DATEDIFF(product_shop.`date_add`, DATE_SUB("'.date('Y-m-d').' 00:00:00",
                    INTERVAL '.(int)$nb_days_new_product.' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `'._DB_PREFIX_.'product` p
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                    ON (pl.`id_product` = '.$id_product.'
                    AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'product_shop` product_shop
                    ON product_shop.`id_product` = '.$id_product.'
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop='.$id_shop.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image`
                    AND il.`id_lang` = '.(int)$id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
                    ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = '.$id_shop.'
                    AND p.`id_product` = '.(int)$id_product;

           //echo '<pre>'; print_r($sql); die;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);

        if($result){
        	return Product::getProductsProperties($id_lang, $result);
    	}else{
	    	return false;
	    }
    }
	
	public function getSubMenu($id_vecmegamenu_item)
	{
		$submenu_info = array();
		$info_rows = array();
		$submenu_config = $this->getSubmenuInfo($id_vecmegamenu_item, true);
		if (is_array($this->getRowInfo($id_vecmegamenu_item, true)) && count($this->getRowInfo($id_vecmegamenu_item, true)) > 0)
		{
			$info_rows = $this->getRowInfo($id_vecmegamenu_item, true);			
			foreach ($info_rows as $key => $info_row){
				$info_rows[$key]['list_col'] = $this->getColInfoFront($info_row['id_row'], true, false);
			}	
		}
		$submenu_info =array(
			'info_rows' => $info_rows,
			'submenu_config' => $submenu_config
		);
		return $submenu_info;
	}

	public function menuExists($id)
	{
		$req = 'SELECT pi.`id_vecmegamenu_item` as id_vecmegamenu_item
				FROM `'._DB_PREFIX_.'vecmegamenu_item` pi
				WHERE pi.`id_vecmegamenu_item` = '.(int)$id;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}
	
	public function hookHeader()
	{
		$this->context->controller->addJS($this->_path.'/views/js/front.js');
		if (Shop::getContext() == Shop::CONTEXT_SHOP)
		$this->context->controller->addCSS(($this->_path).'views/css/vecmegamenu_s_'.(int)$this->context->shop->getContextShopID().'.css', 'all');
	}

	public function hookDisplayMegamenu()
	{
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Context::getContext()->shop->id;

		$group_cat_result = array();
		
			$menu_obj = new VecMegamenuClass();
			$menus = $menu_obj->getMenus();
			$languages = Language::getLanguages();
			$new_menus = array();
			foreach ($menus as $menu)
			{
				$type = Tools::substr($menu['link'], 0, 3);
				$id = (int)Tools::substr($menu['link'], 3, Tools::strlen($menu['link']) - 3);
				if ($menu['type_link'] == 0)
				{	
					$menu_info = $this->fomartLink($menu);
					$menu['link'] = $menu_info['link'];
					$menu['title'] = $menu_info['title'];
					$menu['selected_item'] = $menu_info['selected_item'];
				}
				if($menu['submenu_type'] == 0 || $menu['submenu_type'] == 1){
					$sub_menu = $this->getSubMenu($menu['id_vecmegamenu_item']);

					if (is_array($sub_menu) && count($sub_menu) > 0)
						$menu['sub_menu'] = $sub_menu;
					else
						$menu['sub_menu'] = array();
				}else{
					$menu['sub_menu'] = array();
				}
				$new_menus[] = $menu;
			}
			
			$this->context->smarty->assign(array(
				'menus' => $new_menus,
			));
		
		return $this->display(__FILE__, 'vecmegamenu.tpl', $this->getCacheId('vecmegamenu'));
	}

	public function fomartLink($item = null, $id_lang = null, $submenu_selection = null)
	{	
		if (is_null($item)) return;
			if (!empty($this->context->controller->php_self)) $page_name = $this->context->controller->php_self;
		else
		{
			$page_name = Dispatcher::getInstance()->getController();
			$page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
		}
		$link = '';
		$selected_item = false;
		if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;
		if(isset($submenu_selection)){
			$type = Tools::substr($item['ps_link'], 0, 3);
			$key = Tools::substr($item['ps_link'], 3, Tools::strlen($item['ps_link']) - 3);
		}else{
			$type = Tools::substr($item['link'], 0, 3);
			$key = Tools::substr($item['link'], 3, Tools::strlen($item['link']) - 3);
		}
		//echo '<pre>';print_r($item);die;
		$title = '';
		switch ($type)
		{
			case 'CAT':
				if ($page_name == 'category' && (int)Tools::getValue('id_category') == (int)$key) $selected_item = true;
				$link = $this->context->link->getCategoryLink((int)$key, null, $id_lang);
				$category = new Category($key, $id_lang);
				if(!isset($submenu_selection) && $item['title']){
					$title = $item['title'];
				}else{
					$title = $category->name;
				}
				break;
			case 'CMS':
				if ($page_name == 'cms' && (int)Tools::getValue('id_cms') == (int)$key) $selected_item = true;
				$id_shop = (int)Context::getContext()->shop->id;
				$link = $this->context->link->getCMSLink((int)$key, null, $id_lang, $id_shop);
				$cms = new CMS($key, $id_lang, $id_shop);
				//echo '<pre>'; print_r($item['title']);die;
				if(!isset($submenu_selection) && $item['title']){
					$title = $item['title'];
				}else{
					$title = $cms->meta_title;
				}
				break;
			case 'MAN':
				if ($page_name == 'manufacturer' && (int)Tools::getValue('id_manufacturer') == (int)$key) $selected_item = true;
				$man = new Manufacturer((int)$key, $id_lang);
				$link = $this->context->link->getManufacturerLink($man->id, $man->link_rewrite, $id_lang);
				if(!isset($submenu_selection) && $item['title']){
					$title = $item['title'];
				}else{
					$title = $man->name;
				}
				break;
			case 'SUP':
				if ($page_name == 'supplier' && (int)Tools::getValue('id_supplier') == (int)$key) $selected_item = true;
				$sup = new Supplier((int)$key, $id_lang);
				$link = $this->context->link->getSupplierLink($sup->id, $sup->link_rewrite, $id_lang);
				if(!isset($submenu_selection) && $item['title']){
					$title = $item['title'];
				}else{
					$title = $sup->name;
				}
				break;
			case 'PAG':
				if($key == 'homepage'){
					$key = 'index';
				}
				$pag = Meta::getMetaByPage($key, $id_lang);
				$link = $this->context->link->getPageLink($pag['page'], true, $id_lang);
				if ($page_name == $pag['page']) $selected_item = true;
				if(!isset($submenu_selection) && $item['title']){
					$title = $item['title'];
				}else{
					if ($pag['page'] == 'index')
						$title = $this->l('Home');
					elseif($pag['page'] == 'homepage'){
						$title = $this->l('Home');
						$link = $this->context->link->getPageLink('index', true, $id_lang);
					}else{
						$title = $pag['title'];
					}
				}
				break;
			case 'SHO':
				$shop = new Shop((int)$key);
				$link = $shop->getBaseURL();
				$title = $shop->name;
				break;
			default:
				$link = $item['link'];
				break;
		}
		return array('title' => $title, 'link' => $link, 'selected_item' => $selected_item);
	}
	private function getManufacturers(){
		$html = '';
		$manufacturers = Manufacturer::getManufacturers(false, (int)$this->context->language->id, true);
		//echo '<pre>'; print_r($manufacturers);die;
		foreach ($manufacturers as $manufacturer){
			$html .= '<option value="'.$manufacturer['id_manufacturer'].'">'.$manufacturer['name'].'</option>';
		}
		return $html;
	}
	private function getAllDefaultLink($id_lang = null, $link = false)
	{
		if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;
		$html = '<option value="PAGhomepage">'.$this->l('Homepage').'</option>';
		$html .= '<optgroup label="'.$this->l('Category').'">';
		$html .= $this->getCategoryOption(1, $id_lang, false, true, $link);
		$html .= '</optgroup>';
		$html .= '<optgroup label="'.$this->l('Cms').'">';
		$html .= $this->getCMSOptions(0, 0, $id_lang, $link);
		$html .= '</optgroup>';
		$html .= '<optgroup label="'.$this->l('Manufacturer').'">';
		$manufacturers = Manufacturer::getManufacturers(false, $id_lang);
		foreach ($manufacturers as $manufacturer)
		{
			if ($link)
				$html .= '<option value="'.$this->context->link->getManufacturerLink($manufacturer['id_manufacturer']).'">'.$manufacturer['name'].'</option>';
			else
				$html .= '<option value="MAN'.(int)$manufacturer['id_manufacturer'].'">'.$manufacturer['name'].'</option>';
		}
		$html .= '</optgroup>';
		$html .= '<optgroup label="'.$this->l('Supplier').'">';
		$suppliers = Supplier::getSuppliers(false, $id_lang);
		foreach ($suppliers as $supplier)
		{
		if ($link)
			$html .= '<option value="'.$this->context->link->getSupplierLink($supplier['id_supplier']).'">'.$supplier['name'].'</option>';
		else
			$html .= '<option value="SUP'.(int)$supplier['id_supplier'].'">'.$supplier['name'].'</option>';
		}
		$html .= '</optgroup>';
		$html .= '<optgroup label="'.$this->l('Page').'">';
		$html .= $this->getPagesOption($id_lang, $link);
		$shoplink = Shop::getShops();
		if (count($shoplink) > 1)
		{
			$html .= '<optgroup label="'.$this->l('Shops').'">';
			foreach ($shoplink as $sh)
				$html .= '<option value="SHO'.(int)$sh['id_shop'].'">'.$sh['name'].'</option>';
		}
		$html .= '</optgroup>';
		return $html;
	}
	private function getCategories($category)
    {
        $range = '';
        $maxdepth = 2;
        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
        }

        $resultIds = array();
        $resultParents = array();
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').'
			'.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			'.$range.'
			ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC'));
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        return $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null));
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        $children = [];

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }

        return [
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'desc'=> $desc,
            'children' => $children
        ];
    }
	
	public function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true, $link = false)
	{
		$html = '';
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$id_shop = $id_shop ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
		if (is_null($category->id)) return;
		if ($recursive)
		{
			$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
			$spacer = str_repeat('&nbsp;', 3 * (int)$category->level_depth);
		}
		$shop = (object)Shop::getShop((int)$category->getShopID());
		if (!in_array($category->id, array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'))))
		{
		if ($link)
			$html .= '<option value="'.$this->context->link->getCategoryLink($category->id).'">'.(isset($spacer) ? $spacer : '').str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'</option>';
		else
			$html .= '<option value="CAT'.(int)$category->id.'">'.str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'</option>';
		}
		elseif ($category->id != Configuration::get('PS_ROOT_CATEGORY'))
			$html .= '<optgroup label="'.str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'">';
		if (isset($children) && count($children))
			foreach ($children as $child)
			{
				$html .= $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],
				$recursive, $link);
			}
		return $html;
	}
	
	public function getCMSOptions($parent = 0, $depth = 0, $id_lang = false, $link = false)
	{
		$html = '';
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
		$pages = $this->getCMSPages((int)$parent, false, $id_lang);
		$spacer = str_repeat('&nbsp;', 3 * (int)$depth);
		foreach ($categories as $category)
			$html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $link);
		foreach ($pages as $page)
			if ($link)
				$html .= '<option value="'.$this->context->link->getCMSLink($page['id_cms']).'">'.(isset($spacer) ? $spacer : '').$page['meta_title'].'</option>';
			else
				$html .= '<option value="CMS'.$page['id_cms'].'">'.$page['meta_title'].'</option>';
		return $html;
	}
	
	public function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
	{
		$categories = array();
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		if ($recursive === false)
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;
			return Db::getInstance()->executeS($sql);
		}
		else
		{
			$sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
				FROM `'._DB_PREFIX_.'cms_category` bcp
				INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
				ON (bcp.`id_cms_category` = cl.`id_cms_category`)
				WHERE cl.`id_lang` = '.(int)$id_lang.'
				AND bcp.`id_parent` = '.(int)$parent;
				$results = Db::getInstance()->executeS($sql);
			foreach ($results as $result)
			{
			$sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
			if ($sub_categories && count($sub_categories) > 0) $result['sub_categories'] = $sub_categories;
				$categories[] = $result;
			}
			return isset($categories) ? $categories : false;
		}
	}
	
	public function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
	{
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
			FROM `'._DB_PREFIX_.'cms` c
			INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms` AND cs.`id_shop` = cl.`id_shop`)
			WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
			AND cl.`id_shop` = '.(int)$id_shop.'
			AND cl.`id_lang` = '.(int)$id_lang.'
			AND c.`active` = 1
			ORDER BY `position`';
		return Db::getInstance()->executeS($sql);
	}
	
	public function getPagesOption($id_lang = null, $link = false)
	{
		if (is_null($id_lang)) $id_lang = (int)$this->context->cookie->id_lang;
		$files = Meta::getMetasByIdLang($id_lang);
		$html = '';
		foreach ($files as $file)
		{
			if ($link) $html .= '<option value="'.$this->context->link->getPageLink($file['page']).'">'.(($file['title'] != '') ? $file['title'] : $file['page']).'</option>';
			else  $html .= '<option value="PAG'.$file['page'].'">'.(($file['title'] != '') ? $file['title'] :$file['page']).'</option>';
		}
		return $html;
	}
	
	
	
	public function getRespCategories($id_category = 1, $id_lang = false, $id_shop = false, $level_root = 1, $sub_animation = null)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
		$class= '';
		if ($sub_animation) {
			switch ($sub_animation) {
				case 4:
					$class = 'menu_rotate';
					break;
				case 3:
					$class = 'menu_slideup';
					break;
				case 2:
					$class = 'menu_slidedown';
					break;
				default:
					$class = 'menu_noanimation';
					break;
			}
		}
		$category = new Category((int)$id_category, $id_lang, $id_shop);

		if (is_null($category->id))
			return;
		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
		
		if (isset($children) && count($children))
		{
			$this->respMenu .= '<span class="icon-drop-mobile"><i class="material-icons add">add</i><i class="material-icons remove">remove </i></span>';  
			$this->respMenu .= '<div class="menu-dropdown cat-drop-menu '.$class.'">';
			$this->respMenu .= '<ul class="vec-sub-inner">';
			foreach ($children as $child){	
				$category = new Category((int)$child['id_category'], $id_lang, $id_shop);
				$this->respMenu .= '<li>';
				$this->respMenu .= '<a href="'.$category->getLink().'" class=""><span>'.$category->name.'</span></a>';			
				$this->getRespCategories((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'], $level_root);
				$this->respMenu .= '</li>';
			}
			$this->respMenu .= '</ul>';
			$this->respMenu .= '</div>';
		}

		return $this->respMenu;
	}
	
	public function clearCache()
	{
		$this->_clearCache('vecmegamenu.tpl');
	}	
	
	public function generateCss()
	{
		$css = '';

		$submenus = VecMegamenuClass::getMenus(); 
		foreach ($submenus as $key => $submenu)
		{
			
			$css .= ' .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' > a .menu-subtitle { 
				'.($submenus[$key]['subtitle_bg_color'] != '' ? 'background: '.$submenus[$key]['subtitle_bg_color'].';' : '').'
                '.($submenus[$key]['subtitle_bg_color'] != '' ? 'border-color: '.$submenus[$key]['subtitle_bg_color'].';' : '').'
				'.($submenus[$key]['subtitle_color'] != '' ? 'color: '.$submenus[$key]['subtitle_color'].';' : '').'
				'.($submenus[$key]['subtitle_fontsize'] != 0 ? 'font-size: '.$submenus[$key]['subtitle_fontsize'].'px;' : '').'
				'.($submenus[$key]['subtitle_lineheight'] != 0 ? 'line-height: '.$submenus[$key]['subtitle_lineheight'].'px;' : '').';
			}';
            $css .= '.vec-mobile-menu .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' > a .menu-subtitle:after{  
				'.($submenus[$key]['subtitle_bg_color'] != '' ? 'border-right-color: '.$submenus[$key]['subtitle_bg_color'].';' : '').'
			}';	 
			$submenu_config = VecMegamenuSubmenuClass::getSubmenuConfig($submenus[$key]['id_vecmegamenu_item']);
			if($submenu_config['submenu_bg'] == 2 && $submenu_config['submenu_bg_color'] != ''){
				$css .= '#_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown .vec-sub-inner,
                #_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown.menu-flyout .vec-sub-inner ul,
                #_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown.menu-flyout .column_flyout{
					'.($submenu_config['submenu_bg_color'] != '' ? 'background: '.$submenu_config['submenu_bg_color'].';' : '').'
				}';
			}elseif($submenu_config['submenu_bg'] == 3 && $submenu_config['submenu_bg_image'] != ''){
				$css .= '#_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown .vec-sub-inner,
                #_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown.menu-flyout .vec-sub-inner ul,
                #_desktop_megamenu .vec-menu-horizontal .menu-content .menu-item.menu-item'.$submenus[$key]['id_vecmegamenu_item'].' .menu-dropdown.menu-flyout .column_flyout{
					'.($submenu_config['submenu_bg_image'] != '' ? 'background-image: url("'.$submenu_config['submenu_bg_image'].'");' : '').'
					background-repeat: '.$this->convertBgRepeat($submenu_config['submenu_bg_repeat']).';
					background-position: '.$this->convertBgPosition($submenu_config['submenu_bg_position']).';
				}';
			}
		}
		$css  = trim(preg_replace('/\s+/', ' ', $css));
		if (Shop::getContext() == Shop::CONTEXT_SHOP)
			$my_file = $this->local_path.'views/css/vecmegamenu_s_'.(int)$this->context->shop->getContextShopID().'.css';
		
		if($css){ 
			$fh = fopen($my_file, 'w') or die("can't open file");
			fwrite($fh, $css);
			fclose($fh);
        }else{
            if(file_exists($my_file)){
                unlink($my_file);
            }
        } 
		
	}
	public function convertTransform($value) {
			switch($value) {
				case 2 :
					$transform_option = 'capitalize';
					break;
				case 1 :
					$transform_option = 'uppercase';
					break;
				default :
					$transform_option = 'none';
			}
			return  $transform_option;
	}
	public function convertBgPosition($value) {
			switch($value) {
				case 9 :
					$position_option = 'left top';
					break;
				case 8 :
					$position_option = 'left center';
					break;
				case 7 :
					$position_option = 'left bottom';
					break;
				case 6 :
					$position_option = 'right top';
					break;
				case 5 :
					$position_option = 'right center';
					break;
				case 4 :
					$position_option = 'right bottom';
					break;
				case 3 :
					$position_option = 'center top';
					break;
				case 2 :
					$position_option = 'center center';
					break;
				default :
					$position_option = 'center bottom';
			}
			return  $position_option;
	}
	public function convertBgRepeat($value) {
			switch($value) {
				case 1 :
					$repeat_option = 'no-repeat';
					break;
				case 2 :
					$repeat_option = 'repeat-x';
					break;
				case 3 :
					$repeat_option = 'repeat-y';
					break;
				case 4 :
					$repeat_option = 'repeat';
					break;
			}
			return  $repeat_option;
	}
	public static function getIconsVec()
    {
        return [
            'elicon-arrow_less' => 'arrow_less',
            'elicon-arrow_more' => 'arrow_more',
            'elicon-arow' => 'arow',
            'elicon-arow_r' => 'arow_r',
            'elicon-apps_solid' => 'apps_solid',
            'elicon-arrow_back' => 'arrow_back',
            'elicon-arrow_down' => 'arrow_down',
            'elicon-arrow_forward' => 'arrow_forward',
            'elicon-arrow_left' => 'arrow_left',
            'elicon-arrow_right' => 'arrow_right',
            'elicon-arrow_upward' => 'arrow_upward',
            'elicon-bars' => 'bars',
            'elicon-bag' => 'bag',
            'elicon-bluetooth_speaker' => 'bluetooth_speaker',
            'elicon-box' => 'box',
            'elicon-cake' => 'cake',
            'elicon-calendar1' => 'calendar1',
            'elicon-call1' => 'call1',
            'elicon-call3' => 'call3',
            'elicon-cancel' => 'cancel',
            'elicon-card1' => 'card1',
            'elicon-chat' => 'chat',
            'elicon-chatbox' => 'chatbox',
            'elicon-chatbubble' => 'chatbubble',
            'elicon-check' => 'check',
            'elicon-check_circle' => 'check_circle',
            'elicon-close_circle' => 'close_circle',
            'elicon-close_circle_outline' => 'close_circle_outline',
            'elicon-credit_card' => 'credit_card',
            'elicon-cross' => 'cross',
            'elicon-camera' => 'camera',
            'elicon-cart' => 'cart',
            'elicon-charging' => 'charging',
            'elicon-chipset' => 'chipset',
            'elicon-compare' => 'compare',
            'elicon-diamond' => 'diamond',
            'elicon-dribbble' => 'dribbble',
            'elicon-edit_square' => 'edit_square',
            'elicon-expand' => 'expand',
            'elicon-expand_arrows' => 'expand_arrows',
            'elicon-eye1' => 'eye1',
            'elicon-eye2' => 'eye2',
            'elicon-facebook' => 'facebook',
            'elicon-filter' => 'filter',
            'elicon-filter2' => 'filter2',
            'elicon-gift2' => 'gift2',
            'elicon-gift4' => 'gift4',
            'elicon-globe1' => 'globe1',
            'elicon-globe3' => 'globe3',
            'elicon-google' => 'google',
            'elicon-grid_outline' => 'grid_outline',
            'elicon-grid_outline2' => 'grid_outline2',
            'elicon-grid_solid' => 'grid_solid',
            'elicon-headset3' => 'headset3',
            'elicon-headset4' => 'headset4',
            'elicon-heart2' => 'heart2',
            'elicon-heart2_solid' => 'heart2_solid',
            'elicon-heart5' => 'heart5',
            'elicon-heart5_solid' => 'heart5_solid',
            'elicon-help_outline' => 'help_outline',
            'elicon-history' => 'history',
            'elicon-home1' => 'home1',
            'elicon-instagram' => 'instagram',
            'elicon-life_ring' => 'life_ring',
            'elicon-linkedin' => 'linkedin',
            'elicon-list_outline' => 'list_outline',
            'elicon-list_solid' => 'list_solid',
            'elicon-location2' => 'location2',
            'elicon-location3' => 'location3',
            'elicon-lock' => 'lock',
            'elicon-login' => 'login',
            'elicon-logout' => 'logout',
            'elicon-loyalty' => 'loyalty',
            'elicon-mail_open' => 'mail_open',
            'elicon-menu' => 'menu',
            'elicon-minus' => 'minus',
            'elicon-money2' => 'money2',
            'elicon-open_in_new' => 'open_in_new',
            'elicon-options' => 'options',
            'elicon-paid' => 'paid',
            'elicon-person2' => 'person2',
            'elicon-person3' => 'person3',
            'elicon-person4' => 'person4',
            'elicon-person5' => 'person5',
            'elicon-person6' => 'person6',
            'elicon-pinterest' => 'pinterest',
            'elicon-plane' => 'plane',
            'elicon-plus' => 'plus',
            'elicon-question_answer' => 'question_answer',
            'elicon-refresh1' => 'refresh1',
            'elicon-repeat1' => 'repeat1',
            'elicon-reply' => 'reply',
            'elicon-ribbon' => 'ribbon',
            'elicon-rocket1' => 'rocket1',
            'elicon-rocket2' => 'rocket2',
            'elicon-search1' => 'search1',
            'elicon-search4' => 'search4',
            'elicon-search5' => 'search5',
            'elicon-search6' => 'search6',
            'elicon-settings1' => 'settings1',
            'elicon-share' => 'share',
            'elicon-shield' => 'shield',
            'elicon-shop1' => 'shop1',
            'elicon-shopping_bag1' => 'shopping_bag1',
            'elicon-shopping_bag2' => 'shopping_bag2',
            'elicon-shopping_bag3' => 'shopping_bag3',
            'elicon-shopping_bag4' => 'shopping_bag4',
            'elicon-shopping_basket1' => 'shopping_basket1',
            'elicon-shopping_basket2' => 'shopping_basket2',
            'elicon-shopping_cart1' => 'shopping_cart1',
            'elicon-shopping_cart3' => 'shopping_cart3',
            'elicon-shopping_cart4' => 'shopping_cart4',
            'elicon-shuffle3' => 'shuffle3',
            'elicon-skype' => 'skype',
            'elicon-star' => 'star',
            'elicon-star_solid' => 'star_solid',
            'elicon-sync' => 'sync',
            'elicon-sync2' => 'sync2',
            'elicon-task' => 'task',
            'elicon-thumb_up' => 'thumb_up',
            'elicon-tiktok' => 'tiktok',
            'elicon-time' => 'time',
            'elicon-trash' => 'trash',
            'elicon-truck1' => 'truck1',
            'elicon-tumblr' => 'tumblr',
            'elicon-twitter' => 'twitter',
            'elicon-verified' => 'verified',
            'elicon-verified1' => 'verified1',
            'elicon-whatsapp' => 'whatsapp',
            'elicon-youtube' => 'youtube',
            'elicon-dot' => 'dot',
            'elicon-download' => 'download',
            'elicon-face_id' => 'face_id',
            'elicon-fillter' => 'fillter',
            'elicon-game' => 'game',
            'elicon-heart' => 'heart',
            'elicon-hearta' => 'hearta',
            'elicon-laptop' => 'laptop',
            'elicon-menu1' => 'menu1',
            'elicon-menu2' => 'menu2',
            'elicon-mobiles_tablets' => 'mobiles_tablets',
            'elicon-phone' => 'phone',
            'elicon-play_video' => 'play_video',
            'elicon-pluss' => 'pluss',
            'elicon-portable_speakers' => 'portable_speakers',
            'elicon-quotes' => 'quotes',
            'elicon-return' => 'return',
            'elicon-search' => 'search',
            'elicon-sencruty' => 'sencruty',
            'elicon-shipping' => 'shipping',
            'elicon-shopping_now' => 'shopping_now',
            'elicon-store_location' => 'store_location',
            'elicon-support' => 'support',
            'elicon-tick' => 'tick',
            'elicon-tv' => 'tv',
            'elicon-user' => 'user',
            'elicon-video_game' => 'video_game',
        ];
    }

	public static function getIconsAwesome()
    {
        return [
            'fa fa-500px' => '500px',
            'fa fa-address-book' => 'address-book',
            'fa fa-address-book-o' => 'address-book-o',
            'fa fa-address-card' => 'address-card',
            'fa fa-address-card-o' => 'address-card-o',
            'fa fa-adjust' => 'adjust',
            'fa fa-adn' => 'adn',
            'fa fa-align-center' => 'align-center',
            'fa fa-align-justify' => 'align-justify',
            'fa fa-align-left' => 'align-left',
            'fa fa-align-right' => 'align-right',
            'fa fa-amazon' => 'amazon',
            'fa fa-ambulance' => 'ambulance',
            'fa fa-american-sign-language-interpreting' => 'american-sign-language-interpreting',
            'fa fa-anchor' => 'anchor',
            'fa fa-android' => 'android',
            'fa fa-angellist' => 'angellist',
            'fa fa-angle-double-down' => 'angle-double-down',
            'fa fa-angle-double-left' => 'angle-double-left',
            'fa fa-angle-double-right' => 'angle-double-right',
            'fa fa-angle-double-up' => 'angle-double-up',
            'fa fa-angle-down' => 'angle-down',
            'fa fa-angle-left' => 'angle-left',
            'fa fa-angle-right' => 'angle-right',
            'fa fa-angle-up' => 'angle-up',
            'fa fa-apple' => 'apple',
            'fa fa-archive' => 'archive',
            'fa fa-area-chart' => 'area-chart',
            'fa fa-arrow-circle-down' => 'arrow-circle-down',
            'fa fa-arrow-circle-left' => 'arrow-circle-left',
            'fa fa-arrow-circle-o-down' => 'arrow-circle-o-down',
            'fa fa-arrow-circle-o-left' => 'arrow-circle-o-left',
            'fa fa-arrow-circle-o-right' => 'arrow-circle-o-right',
            'fa fa-arrow-circle-o-up' => 'arrow-circle-o-up',
            'fa fa-arrow-circle-right' => 'arrow-circle-right',
            'fa fa-arrow-circle-up' => 'arrow-circle-up',
            'fa fa-arrow-down' => 'arrow-down',
            'fa fa-arrow-left' => 'arrow-left',
            'fa fa-arrow-right' => 'arrow-right',
            'fa fa-arrow-up' => 'arrow-up',
            'fa fa-arrows' => 'arrows',
            'fa fa-arrows-alt' => 'arrows-alt',
            'fa fa-arrows-h' => 'arrows-h',
            'fa fa-arrows-v' => 'arrows-v',
            'fa fa-asl-interpreting' => 'asl-interpreting',
            'fa fa-assistive-listening-systems' => 'assistive-listening-systems',
            'fa fa-asterisk' => 'asterisk',
            'fa fa-at' => 'at',
            'fa fa-audio-description' => 'audio-description',
            'fa fa-automobile' => 'automobile',
            'fa fa-backward' => 'backward',
            'fa fa-balance-scale' => 'balance-scale',
            'fa fa-ban' => 'ban',
            'fa fa-bandcamp' => 'bandcamp',
            'fa fa-bank' => 'bank',
            'fa fa-bar-chart' => 'bar-chart',
            'fa fa-bar-chart-o' => 'bar-chart-o',
            'fa fa-barcode' => 'barcode',
            'fa fa-bars' => 'bars',
            'fa fa-bath' => 'bath',
            'fa fa-bathtub' => 'bathtub',
            'fa fa-battery' => 'battery',
            'fa fa-battery-0' => 'battery-0',
            'fa fa-battery-1' => 'battery-1',
            'fa fa-battery-2' => 'battery-2',
            'fa fa-battery-3' => 'battery-3',
            'fa fa-battery-4' => 'battery-4',
            'fa fa-battery-empty' => 'battery-empty',
            'fa fa-battery-full' => 'battery-full',
            'fa fa-battery-half' => 'battery-half',
            'fa fa-battery-quarter' => 'battery-quarter',
            'fa fa-battery-three-quarters' => 'battery-three-quarters',
            'fa fa-bed' => 'bed',
            'fa fa-beer' => 'beer',
            'fa fa-behance' => 'behance',
            'fa fa-behance-square' => 'behance-square',
            'fa fa-bell' => 'bell',
            'fa fa-bell-o' => 'bell-o',
            'fa fa-bell-slash' => 'bell-slash',
            'fa fa-bell-slash-o' => 'bell-slash-o',
            'fa fa-bicycle' => 'bicycle',
            'fa fa-binoculars' => 'binoculars',
            'fa fa-birthday-cake' => 'birthday-cake',
            'fa fa-bitbucket' => 'bitbucket',
            'fa fa-bitbucket-square' => 'bitbucket-square',
            'fa fa-bitcoin' => 'bitcoin',
            'fa fa-black-tie' => 'black-tie',
            'fa fa-blind' => 'blind',
            'fa fa-bluetooth' => 'bluetooth',
            'fa fa-bluetooth-b' => 'bluetooth-b',
            'fa fa-bold' => 'bold',
            'fa fa-bolt' => 'bolt',
            'fa fa-bomb' => 'bomb',
            'fa fa-book' => 'book',
            'fa fa-bookmark' => 'bookmark',
            'fa fa-bookmark-o' => 'bookmark-o',
            'fa fa-braille' => 'braille',
            'fa fa-briefcase' => 'briefcase',
            'fa fa-btc' => 'btc',
            'fa fa-bug' => 'bug',
            'fa fa-building' => 'building',
            'fa fa-building-o' => 'building-o',
            'fa fa-bullhorn' => 'bullhorn',
            'fa fa-bullseye' => 'bullseye',
            'fa fa-bus' => 'bus',
            'fa fa-buysellads' => 'buysellads',
            'fa fa-cab' => 'cab',
            'fa fa-calculator' => 'calculator',
            'fa fa-calendar' => 'calendar',
            'fa fa-calendar-check-o' => 'calendar-check-o',
            'fa fa-calendar-minus-o' => 'calendar-minus-o',
            'fa fa-calendar-o' => 'calendar-o',
            'fa fa-calendar-plus-o' => 'calendar-plus-o',
            'fa fa-calendar-times-o' => 'calendar-times-o',
            'fa fa-camera' => 'camera',
            'fa fa-camera-retro' => 'camera-retro',
            'fa fa-car' => 'car',
            'fa fa-caret-down' => 'caret-down',
            'fa fa-caret-left' => 'caret-left',
            'fa fa-caret-right' => 'caret-right',
            'fa fa-caret-square-o-down' => 'caret-square-o-down',
            'fa fa-caret-square-o-left' => 'caret-square-o-left',
            'fa fa-caret-square-o-right' => 'caret-square-o-right',
            'fa fa-caret-square-o-up' => 'caret-square-o-up',
            'fa fa-caret-up' => 'caret-up',
            'fa fa-cart-arrow-down' => 'cart-arrow-down',
            'fa fa-cart-plus' => 'cart-plus',
            'fa fa-cc' => 'cc',
            'fa fa-cc-amex' => 'cc-amex',
            'fa fa-cc-diners-club' => 'cc-diners-club',
            'fa fa-cc-discover' => 'cc-discover',
            'fa fa-cc-jcb' => 'cc-jcb',
            'fa fa-cc-mastercard' => 'cc-mastercard',
            'fa fa-cc-paypal' => 'cc-paypal',
            'fa fa-cc-stripe' => 'cc-stripe',
            'fa fa-cc-visa' => 'cc-visa',
            'fa fa-certificate' => 'certificate',
            'fa fa-chain' => 'chain',
            'fa fa-chain-broken' => 'chain-broken',
            'fa fa-check' => 'check',
            'fa fa-check-circle' => 'check-circle',
            'fa fa-check-circle-o' => 'check-circle-o',
            'fa fa-check-square' => 'check-square',
            'fa fa-check-square-o' => 'check-square-o',
            'fa fa-chevron-circle-down' => 'chevron-circle-down',
            'fa fa-chevron-circle-left' => 'chevron-circle-left',
            'fa fa-chevron-circle-right' => 'chevron-circle-right',
            'fa fa-chevron-circle-up' => 'chevron-circle-up',
            'fa fa-chevron-down' => 'chevron-down',
            'fa fa-chevron-left' => 'chevron-left',
            'fa fa-chevron-right' => 'chevron-right',
            'fa fa-chevron-up' => 'chevron-up',
            'fa fa-child' => 'child',
            'fa fa-chrome' => 'chrome',
            'fa fa-circle' => 'circle',
            'fa fa-circle-o' => 'circle-o',
            'fa fa-circle-o-notch' => 'circle-o-notch',
            'fa fa-circle-thin' => 'circle-thin',
            'fa fa-clipboard' => 'clipboard',
            'fa fa-clock-o' => 'clock-o',
            'fa fa-clone' => 'clone',
            'fa fa-close' => 'close',
            'fa fa-cloud' => 'cloud',
            'fa fa-cloud-download' => 'cloud-download',
            'fa fa-cloud-upload' => 'cloud-upload',
            'fa fa-cny' => 'cny',
            'fa fa-code' => 'code',
            'fa fa-code-fork' => 'code-fork',
            'fa fa-codepen' => 'codepen',
            'fa fa-codiepie' => 'codiepie',
            'fa fa-coffee' => 'coffee',
            'fa fa-cog' => 'cog',
            'fa fa-cogs' => 'cogs',
            'fa fa-columns' => 'columns',
            'fa fa-comment' => 'comment',
            'fa fa-comment-o' => 'comment-o',
            'fa fa-commenting' => 'commenting',
            'fa fa-commenting-o' => 'commenting-o',
            'fa fa-comments' => 'comments',
            'fa fa-comments-o' => 'comments-o',
            'fa fa-compass' => 'compass',
            'fa fa-compress' => 'compress',
            'fa fa-connectdevelop' => 'connectdevelop',
            'fa fa-contao' => 'contao',
            'fa fa-copy' => 'copy',
            'fa fa-copyright' => 'copyright',
            'fa fa-creative-commons' => 'creative-commons',
            'fa fa-credit-card' => 'credit-card',
            'fa fa-credit-card-alt' => 'credit-card-alt',
            'fa fa-crop' => 'crop',
            'fa fa-crosshairs' => 'crosshairs',
            'fa fa-css3' => 'css3',
            'fa fa-cube' => 'cube',
            'fa fa-cubes' => 'cubes',
            'fa fa-cut' => 'cut',
            'fa fa-cutlery' => 'cutlery',
            'fa fa-dashboard' => 'dashboard',
            'fa fa-dashcube' => 'dashcube',
            'fa fa-database' => 'database',
            'fa fa-deaf' => 'deaf',
            'fa fa-deafness' => 'deafness',
            'fa fa-dedent' => 'dedent',
            'fa fa-delicious' => 'delicious',
            'fa fa-desktop' => 'desktop',
            'fa fa-deviantart' => 'deviantart',
            'fa fa-diamond' => 'diamond',
            'fa fa-digg' => 'digg',
            'fa fa-dollar' => 'dollar',
            'fa fa-dot-circle-o' => 'dot-circle-o',
            'fa fa-download' => 'download',
            'fa fa-dribbble' => 'dribbble',
            'fa fa-drivers-license' => 'drivers-license',
            'fa fa-drivers-license-o' => 'drivers-license-o',
            'fa fa-dropbox' => 'dropbox',
            'fa fa-drupal' => 'drupal',
            'fa fa-edge' => 'edge',
            'fa fa-edit' => 'edit',
            'fa fa-eercast' => 'eercast',
            'fa fa-eject' => 'eject',
            'fa fa-ellipsis-h' => 'ellipsis-h',
            'fa fa-ellipsis-v' => 'ellipsis-v',
            'fa fa-empire' => 'empire',
            'fa fa-envelope' => 'envelope',
            'fa fa-envelope-o' => 'envelope-o',
            'fa fa-envelope-open' => 'envelope-open',
            'fa fa-envelope-open-o' => 'envelope-open-o',
            'fa fa-envelope-square' => 'envelope-square',
            'fa fa-envira' => 'envira',
            'fa fa-eraser' => 'eraser',
            'fa fa-etsy' => 'etsy',
            'fa fa-eur' => 'eur',
            'fa fa-euro' => 'euro',
            'fa fa-exchange' => 'exchange',
            'fa fa-exclamation' => 'exclamation',
            'fa fa-exclamation-circle' => 'exclamation-circle',
            'fa fa-exclamation-triangle' => 'exclamation-triangle',
            'fa fa-expand' => 'expand',
            'fa fa-expeditedssl' => 'expeditedssl',
            'fa fa-external-link' => 'external-link',
            'fa fa-external-link-square' => 'external-link-square',
            'fa fa-eye' => 'eye',
            'fa fa-eye-slash' => 'eye-slash',
            'fa fa-eyedropper' => 'eyedropper',
            'fa fa-fa' => 'fa',
            'fa fa-facebook' => 'facebook',
            'fa fa-facebook-f' => 'facebook-f',
            'fa fa-facebook-official' => 'facebook-official',
            'fa fa-facebook-square' => 'facebook-square',
            'fa fa-fast-backward' => 'fast-backward',
            'fa fa-fast-forward' => 'fast-forward',
            'fa fa-fax' => 'fax',
            'fa fa-feed' => 'feed',
            'fa fa-female' => 'female',
            'fa fa-fighter-jet' => 'fighter-jet',
            'fa fa-file' => 'file',
            'fa fa-file-archive-o' => 'file-archive-o',
            'fa fa-file-audio-o' => 'file-audio-o',
            'fa fa-file-code-o' => 'file-code-o',
            'fa fa-file-excel-o' => 'file-excel-o',
            'fa fa-file-image-o' => 'file-image-o',
            'fa fa-file-movie-o' => 'file-movie-o',
            'fa fa-file-o' => 'file-o',
            'fa fa-file-pdf-o' => 'file-pdf-o',
            'fa fa-file-photo-o' => 'file-photo-o',
            'fa fa-file-picture-o' => 'file-picture-o',
            'fa fa-file-powerpoint-o' => 'file-powerpoint-o',
            'fa fa-file-sound-o' => 'file-sound-o',
            'fa fa-file-text' => 'file-text',
            'fa fa-file-text-o' => 'file-text-o',
            'fa fa-file-video-o' => 'file-video-o',
            'fa fa-file-word-o' => 'file-word-o',
            'fa fa-file-zip-o' => 'file-zip-o',
            'fa fa-files-o' => 'files-o',
            'fa fa-film' => 'film',
            'fa fa-filter' => 'filter',
            'fa fa-fire' => 'fire',
            'fa fa-fire-extinguisher' => 'fire-extinguisher',
            'fa fa-firefox' => 'firefox',
            'fa fa-first-order' => 'first-order',
            'fa fa-flag' => 'flag',
            'fa fa-flag-checkered' => 'flag-checkered',
            'fa fa-flag-o' => 'flag-o',
            'fa fa-flash' => 'flash',
            'fa fa-flask' => 'flask',
            'fa fa-flickr' => 'flickr',
            'fa fa-floppy-o' => 'floppy-o',
            'fa fa-folder' => 'folder',
            'fa fa-folder-o' => 'folder-o',
            'fa fa-folder-open' => 'folder-open',
            'fa fa-folder-open-o' => 'folder-open-o',
            'fa fa-font' => 'font',
            'fa fa-font-awesome' => 'font-awesome',
            'fa fa-fonticons' => 'fonticons',
            'fa fa-fort-awesome' => 'fort-awesome',
            'fa fa-forumbee' => 'forumbee',
            'fa fa-forward' => 'forward',
            'fa fa-foursquare' => 'foursquare',
            'fa fa-free-code-camp' => 'free-code-camp',
            'fa fa-frown-o' => 'frown-o',
            'fa fa-futbol-o' => 'futbol-o',
            'fa fa-gamepad' => 'gamepad',
            'fa fa-gavel' => 'gavel',
            'fa fa-gbp' => 'gbp',
            'fa fa-ge' => 'ge',
            'fa fa-gear' => 'gear',
            'fa fa-gears' => 'gears',
            'fa fa-genderless' => 'genderless',
            'fa fa-get-pocket' => 'get-pocket',
            'fa fa-gg' => 'gg',
            'fa fa-gg-circle' => 'gg-circle',
            'fa fa-gift' => 'gift',
            'fa fa-git' => 'git',
            'fa fa-git-square' => 'git-square',
            'fa fa-github' => 'github',
            'fa fa-github-alt' => 'github-alt',
            'fa fa-github-square' => 'github-square',
            'fa fa-gitlab' => 'gitlab',
            'fa fa-gittip' => 'gittip',
            'fa fa-glass' => 'glass',
            'fa fa-glide' => 'glide',
            'fa fa-glide-g' => 'glide-g',
            'fa fa-globe' => 'globe',
            'fa fa-google' => 'google',
            'fa fa-google-plus' => 'google-plus',
            'fa fa-google-plus-circle' => 'google-plus-circle',
            'fa fa-google-plus-official' => 'google-plus-official',
            'fa fa-google-plus-square' => 'google-plus-square',
            'fa fa-google-wallet' => 'google-wallet',
            'fa fa-graduation-cap' => 'graduation-cap',
            'fa fa-gratipay' => 'gratipay',
            'fa fa-grav' => 'grav',
            'fa fa-group' => 'group',
            'fa fa-h-square' => 'h-square',
            'fa fa-hacker-news' => 'hacker-news',
            'fa fa-hand-grab-o' => 'hand-grab-o',
            'fa fa-hand-lizard-o' => 'hand-lizard-o',
            'fa fa-hand-o-down' => 'hand-o-down',
            'fa fa-hand-o-left' => 'hand-o-left',
            'fa fa-hand-o-right' => 'hand-o-right',
            'fa fa-hand-o-up' => 'hand-o-up',
            'fa fa-hand-paper-o' => 'hand-paper-o',
            'fa fa-hand-peace-o' => 'hand-peace-o',
            'fa fa-hand-pointer-o' => 'hand-pointer-o',
            'fa fa-hand-rock-o' => 'hand-rock-o',
            'fa fa-hand-scissors-o' => 'hand-scissors-o',
            'fa fa-hand-spock-o' => 'hand-spock-o',
            'fa fa-hand-stop-o' => 'hand-stop-o',
            'fa fa-handshake-o' => 'handshake-o',
            'fa fa-hard-of-hearing' => 'hard-of-hearing',
            'fa fa-hashtag' => 'hashtag',
            'fa fa-hdd-o' => 'hdd-o',
            'fa fa-header' => 'header',
            'fa fa-headphones' => 'headphones',
            'fa fa-heart' => 'heart',
            'fa fa-heart-o' => 'heart-o',
            'fa fa-heartbeat' => 'heartbeat',
            'fa fa-history' => 'history',
            'fa fa-home' => 'home',
            'fa fa-hospital-o' => 'hospital-o',
            'fa fa-hotel' => 'hotel',
            'fa fa-hourglass' => 'hourglass',
            'fa fa-hourglass-1' => 'hourglass-1',
            'fa fa-hourglass-2' => 'hourglass-2',
            'fa fa-hourglass-3' => 'hourglass-3',
            'fa fa-hourglass-end' => 'hourglass-end',
            'fa fa-hourglass-half' => 'hourglass-half',
            'fa fa-hourglass-o' => 'hourglass-o',
            'fa fa-hourglass-start' => 'hourglass-start',
            'fa fa-houzz' => 'houzz',
            'fa fa-html5' => 'html5',
            'fa fa-i-cursor' => 'i-cursor',
            'fa fa-id-badge' => 'id-badge',
            'fa fa-id-card' => 'id-card',
            'fa fa-id-card-o' => 'id-card-o',
            'fa fa-ils' => 'ils',
            'fa fa-image' => 'image',
            'fa fa-imdb' => 'imdb',
            'fa fa-inbox' => 'inbox',
            'fa fa-indent' => 'indent',
            'fa fa-industry' => 'industry',
            'fa fa-info' => 'info',
            'fa fa-info-circle' => 'info-circle',
            'fa fa-inr' => 'inr',
            'fa fa-instagram' => 'instagram',
            'fa fa-institution' => 'institution',
            'fa fa-internet-explorer' => 'internet-explorer',
            'fa fa-intersex' => 'intersex',
            'fa fa-ioxhost' => 'ioxhost',
            'fa fa-italic' => 'italic',
            'fa fa-joomla' => 'joomla',
            'fa fa-jpy' => 'jpy',
            'fa fa-jsfiddle' => 'jsfiddle',
            'fa fa-key' => 'key',
            'fa fa-keyboard-o' => 'keyboard-o',
            'fa fa-krw' => 'krw',
            'fa fa-language' => 'language',
            'fa fa-laptop' => 'laptop',
            'fa fa-lastfm' => 'lastfm',
            'fa fa-lastfm-square' => 'lastfm-square',
            'fa fa-leaf' => 'leaf',
            'fa fa-leanpub' => 'leanpub',
            'fa fa-legal' => 'legal',
            'fa fa-lemon-o' => 'lemon-o',
            'fa fa-level-down' => 'level-down',
            'fa fa-level-up' => 'level-up',
            'fa fa-life-bouy' => 'life-bouy',
            'fa fa-life-buoy' => 'life-buoy',
            'fa fa-life-ring' => 'life-ring',
            'fa fa-life-saver' => 'life-saver',
            'fa fa-lightbulb-o' => 'lightbulb-o',
            'fa fa-line-chart' => 'line-chart',
            'fa fa-link' => 'link',
            'fa fa-linkedin' => 'linkedin',
            'fa fa-linkedin-square' => 'linkedin-square',
            'fa fa-linode' => 'linode',
            'fa fa-linux' => 'linux',
            'fa fa-list' => 'list',
            'fa fa-list-alt' => 'list-alt',
            'fa fa-list-ol' => 'list-ol',
            'fa fa-list-ul' => 'list-ul',
            'fa fa-location-arrow' => 'location-arrow',
            'fa fa-lock' => 'lock',
            'fa fa-long-arrow-down' => 'long-arrow-down',
            'fa fa-long-arrow-left' => 'long-arrow-left',
            'fa fa-long-arrow-right' => 'long-arrow-right',
            'fa fa-long-arrow-up' => 'long-arrow-up',
            'fa fa-low-vision' => 'low-vision',
            'fa fa-magic' => 'magic',
            'fa fa-magnet' => 'magnet',
            'fa fa-mail-forward' => 'mail-forward',
            'fa fa-mail-reply' => 'mail-reply',
            'fa fa-mail-reply-all' => 'mail-reply-all',
            'fa fa-male' => 'male',
            'fa fa-map' => 'map',
            'fa fa-map-marker' => 'map-marker',
            'fa fa-map-o' => 'map-o',
            'fa fa-map-pin' => 'map-pin',
            'fa fa-map-signs' => 'map-signs',
            'fa fa-mars' => 'mars',
            'fa fa-mars-double' => 'mars-double',
            'fa fa-mars-stroke' => 'mars-stroke',
            'fa fa-mars-stroke-h' => 'mars-stroke-h',
            'fa fa-mars-stroke-v' => 'mars-stroke-v',
            'fa fa-maxcdn' => 'maxcdn',
            'fa fa-meanpath' => 'meanpath',
            'fa fa-medium' => 'medium',
            'fa fa-medkit' => 'medkit',
            'fa fa-meetup' => 'meetup',
            'fa fa-meh-o' => 'meh-o',
            'fa fa-mercury' => 'mercury',
            'fa fa-microchip' => 'microchip',
            'fa fa-microphone' => 'microphone',
            'fa fa-microphone-slash' => 'microphone-slash',
            'fa fa-minus' => 'minus',
            'fa fa-minus-circle' => 'minus-circle',
            'fa fa-minus-square' => 'minus-square',
            'fa fa-minus-square-o' => 'minus-square-o',
            'fa fa-mixcloud' => 'mixcloud',
            'fa fa-mobile' => 'mobile',
            'fa fa-mobile-phone' => 'mobile-phone',
            'fa fa-modx' => 'modx',
            'fa fa-money' => 'money',
            'fa fa-moon-o' => 'moon-o',
            'fa fa-mortar-board' => 'mortar-board',
            'fa fa-motorcycle' => 'motorcycle',
            'fa fa-mouse-pointer' => 'mouse-pointer',
            'fa fa-music' => 'music',
            'fa fa-navicon' => 'navicon',
            'fa fa-neuter' => 'neuter',
            'fa fa-newspaper-o' => 'newspaper-o',
            'fa fa-object-group' => 'object-group',
            'fa fa-object-ungroup' => 'object-ungroup',
            'fa fa-odnoklassniki' => 'odnoklassniki',
            'fa fa-odnoklassniki-square' => 'odnoklassniki-square',
            'fa fa-opencart' => 'opencart',
            'fa fa-openid' => 'openid',
            'fa fa-opera' => 'opera',
            'fa fa-optin-monster' => 'optin-monster',
            'fa fa-outdent' => 'outdent',
            'fa fa-pagelines' => 'pagelines',
            'fa fa-paint-brush' => 'paint-brush',
            'fa fa-paper-plane' => 'paper-plane',
            'fa fa-paper-plane-o' => 'paper-plane-o',
            'fa fa-paperclip' => 'paperclip',
            'fa fa-paragraph' => 'paragraph',
            'fa fa-paste' => 'paste',
            'fa fa-pause' => 'pause',
            'fa fa-pause-circle' => 'pause-circle',
            'fa fa-pause-circle-o' => 'pause-circle-o',
            'fa fa-paw' => 'paw',
            'fa fa-paypal' => 'paypal',
            'fa fa-pencil' => 'pencil',
            'fa fa-pencil-square' => 'pencil-square',
            'fa fa-pencil-square-o' => 'pencil-square-o',
            'fa fa-percent' => 'percent',
            'fa fa-phone' => 'phone',
            'fa fa-phone-square' => 'phone-square',
            'fa fa-photo' => 'photo',
            'fa fa-picture-o' => 'picture-o',
            'fa fa-pie-chart' => 'pie-chart',
            'fa fa-pied-piper' => 'pied-piper',
            'fa fa-pied-piper-alt' => 'pied-piper-alt',
            'fa fa-pied-piper-pp' => 'pied-piper-pp',
            'fa fa-pinterest' => 'pinterest',
            'fa fa-pinterest-p' => 'pinterest-p',
            'fa fa-pinterest-square' => 'pinterest-square',
            'fa fa-plane' => 'plane',
            'fa fa-play' => 'play',
            'fa fa-play-circle' => 'play-circle',
            'fa fa-play-circle-o' => 'play-circle-o',
            'fa fa-plug' => 'plug',
            'fa fa-plus' => 'plus',
            'fa fa-plus-circle' => 'plus-circle',
            'fa fa-plus-square' => 'plus-square',
            'fa fa-plus-square-o' => 'plus-square-o',
            'fa fa-podcast' => 'podcast',
            'fa fa-power-off' => 'power-off',
            'fa fa-print' => 'print',
            'fa fa-product-hunt' => 'product-hunt',
            'fa fa-pull-left' => 'pull-left',
            'fa fa-pull-right' => 'pull-right',
            'fa fa-puzzle-piece' => 'puzzle-piece',
            'fa fa-qq' => 'qq',
            'fa fa-qrcode' => 'qrcode',
            'fa fa-question' => 'question',
            'fa fa-question-circle' => 'question-circle',
            'fa fa-question-circle-o' => 'question-circle-o',
            'fa fa-quora' => 'quora',
            'fa fa-quote-left' => 'quote-left',
            'fa fa-quote-right' => 'quote-right',
            'fa fa-ra' => 'ra',
            'fa fa-random' => 'random',
            'fa fa-ravelry' => 'ravelry',
            'fa fa-rebel' => 'rebel',
            'fa fa-recycle' => 'recycle',
            'fa fa-reddit' => 'reddit',
            'fa fa-reddit-alien' => 'reddit-alien',
            'fa fa-reddit-square' => 'reddit-square',
            'fa fa-refresh' => 'refresh',
            'fa fa-registered' => 'registered',
            'fa fa-remove' => 'remove',
            'fa fa-renren' => 'renren',
            'fa fa-reorder' => 'reorder',
            'fa fa-repeat' => 'repeat',
            'fa fa-reply' => 'reply',
            'fa fa-reply-all' => 'reply-all',
            'fa fa-resistance' => 'resistance',
            'fa fa-retweet' => 'retweet',
            'fa fa-rmb' => 'rmb',
            'fa fa-road' => 'road',
            'fa fa-rocket' => 'rocket',
            'fa fa-rotate-left' => 'rotate-left',
            'fa fa-rotate-right' => 'rotate-right',
            'fa fa-rouble' => 'rouble',
            'fa fa-rss' => 'rss',
            'fa fa-rss-square' => 'rss-square',
            'fa fa-rub' => 'rub',
            'fa fa-ruble' => 'ruble',
            'fa fa-rupee' => 'rupee',
            'fa fa-s15' => 's15',
            'fa fa-safari' => 'safari',
            'fa fa-save' => 'save',
            'fa fa-scissors' => 'scissors',
            'fa fa-scribd' => 'scribd',
            'fa fa-search' => 'search',
            'fa fa-search-minus' => 'search-minus',
            'fa fa-search-plus' => 'search-plus',
            'fa fa-sellsy' => 'sellsy',
            'fa fa-send' => 'send',
            'fa fa-send-o' => 'send-o',
            'fa fa-server' => 'server',
            'fa fa-share' => 'share',
            'fa fa-share-alt' => 'share-alt',
            'fa fa-share-alt-square' => 'share-alt-square',
            'fa fa-share-square' => 'share-square',
            'fa fa-share-square-o' => 'share-square-o',
            'fa fa-shekel' => 'shekel',
            'fa fa-sheqel' => 'sheqel',
            'fa fa-shield' => 'shield',
            'fa fa-ship' => 'ship',
            'fa fa-shirtsinbulk' => 'shirtsinbulk',
            'fa fa-shopping-bag' => 'shopping-bag',
            'fa fa-shopping-basket' => 'shopping-basket',
            'fa fa-shopping-cart' => 'shopping-cart',
            'fa fa-shower' => 'shower',
            'fa fa-sign-in' => 'sign-in',
            'fa fa-sign-language' => 'sign-language',
            'fa fa-sign-out' => 'sign-out',
            'fa fa-signal' => 'signal',
            'fa fa-signing' => 'signing',
            'fa fa-simplybuilt' => 'simplybuilt',
            'fa fa-sitemap' => 'sitemap',
            'fa fa-skyatlas' => 'skyatlas',
            'fa fa-skype' => 'skype',
            'fa fa-slack' => 'slack',
            'fa fa-sliders' => 'sliders',
            'fa fa-slideshare' => 'slideshare',
            'fa fa-smile-o' => 'smile-o',
            'fa fa-snapchat' => 'snapchat',
            'fa fa-snapchat-ghost' => 'snapchat-ghost',
            'fa fa-snapchat-square' => 'snapchat-square',
            'fa fa-snowflake-o' => 'snowflake-o',
            'fa fa-soccer-ball-o' => 'soccer-ball-o',
            'fa fa-sort' => 'sort',
            'fa fa-sort-alpha-asc' => 'sort-alpha-asc',
            'fa fa-sort-alpha-desc' => 'sort-alpha-desc',
            'fa fa-sort-amount-asc' => 'sort-amount-asc',
            'fa fa-sort-amount-desc' => 'sort-amount-desc',
            'fa fa-sort-asc' => 'sort-asc',
            'fa fa-sort-desc' => 'sort-desc',
            'fa fa-sort-down' => 'sort-down',
            'fa fa-sort-numeric-asc' => 'sort-numeric-asc',
            'fa fa-sort-numeric-desc' => 'sort-numeric-desc',
            'fa fa-sort-up' => 'sort-up',
            'fa fa-soundcloud' => 'soundcloud',
            'fa fa-space-shuttle' => 'space-shuttle',
            'fa fa-spinner' => 'spinner',
            'fa fa-spoon' => 'spoon',
            'fa fa-spotify' => 'spotify',
            'fa fa-square' => 'square',
            'fa fa-square-o' => 'square-o',
            'fa fa-stack-exchange' => 'stack-exchange',
            'fa fa-stack-overflow' => 'stack-overflow',
            'fa fa-star' => 'star',
            'fa fa-star-half' => 'star-half',
            'fa fa-star-half-empty' => 'star-half-empty',
            'fa fa-star-half-full' => 'star-half-full',
            'fa fa-star-half-o' => 'star-half-o',
            'fa fa-star-o' => 'star-o',
            'fa fa-steam' => 'steam',
            'fa fa-steam-square' => 'steam-square',
            'fa fa-step-backward' => 'step-backward',
            'fa fa-step-forward' => 'step-forward',
            'fa fa-stethoscope' => 'stethoscope',
            'fa fa-sticky-note' => 'sticky-note',
            'fa fa-sticky-note-o' => 'sticky-note-o',
            'fa fa-stop' => 'stop',
            'fa fa-stop-circle' => 'stop-circle',
            'fa fa-stop-circle-o' => 'stop-circle-o',
            'fa fa-street-view' => 'street-view',
            'fa fa-strikethrough' => 'strikethrough',
            'fa fa-stumbleupon' => 'stumbleupon',
            'fa fa-stumbleupon-circle' => 'stumbleupon-circle',
            'fa fa-subscript' => 'subscript',
            'fa fa-subway' => 'subway',
            'fa fa-suitcase' => 'suitcase',
            'fa fa-sun-o' => 'sun-o',
            'fa fa-superpowers' => 'superpowers',
            'fa fa-superscript' => 'superscript',
            'fa fa-support' => 'support',
            'fa fa-table' => 'table',
            'fa fa-tablet' => 'tablet',
            'fa fa-tachometer' => 'tachometer',
            'fa fa-tag' => 'tag',
            'fa fa-tags' => 'tags',
            'fa fa-tasks' => 'tasks',
            'fa fa-taxi' => 'taxi',
            'fa fa-telegram' => 'telegram',
            'fa fa-television' => 'television',
            'fa fa-tencent-weibo' => 'tencent-weibo',
            'fa fa-terminal' => 'terminal',
            'fa fa-text-height' => 'text-height',
            'fa fa-text-width' => 'text-width',
            'fa fa-th' => 'th',
            'fa fa-th-large' => 'th-large',
            'fa fa-th-list' => 'th-list',
            'fa fa-themeisle' => 'themeisle',
            'fa fa-thermometer' => 'thermometer',
            'fa fa-thermometer-0' => 'thermometer-0',
            'fa fa-thermometer-1' => 'thermometer-1',
            'fa fa-thermometer-2' => 'thermometer-2',
            'fa fa-thermometer-3' => 'thermometer-3',
            'fa fa-thermometer-4' => 'thermometer-4',
            'fa fa-thermometer-empty' => 'thermometer-empty',
            'fa fa-thermometer-full' => 'thermometer-full',
            'fa fa-thermometer-half' => 'thermometer-half',
            'fa fa-thermometer-quarter' => 'thermometer-quarter',
            'fa fa-thermometer-three-quarters' => 'thermometer-three-quarters',
            'fa fa-thumb-tack' => 'thumb-tack',
            'fa fa-thumbs-down' => 'thumbs-down',
            'fa fa-thumbs-o-down' => 'thumbs-o-down',
            'fa fa-thumbs-o-up' => 'thumbs-o-up',
            'fa fa-thumbs-up' => 'thumbs-up',
            'fa fa-ticket' => 'ticket',
            'fa fa-times' => 'times',
            'fa fa-times-circle' => 'times-circle',
            'fa fa-times-circle-o' => 'times-circle-o',
            'fa fa-times-rectangle' => 'times-rectangle',
            'fa fa-times-rectangle-o' => 'times-rectangle-o',
            'fa fa-tint' => 'tint',
            'fa fa-toggle-down' => 'toggle-down',
            'fa fa-toggle-left' => 'toggle-left',
            'fa fa-toggle-off' => 'toggle-off',
            'fa fa-toggle-on' => 'toggle-on',
            'fa fa-toggle-right' => 'toggle-right',
            'fa fa-toggle-up' => 'toggle-up',
            'fa fa-trademark' => 'trademark',
            'fa fa-train' => 'train',
            'fa fa-transgender' => 'transgender',
            'fa fa-transgender-alt' => 'transgender-alt',
            'fa fa-trash' => 'trash',
            'fa fa-trash-o' => 'trash-o',
            'fa fa-tree' => 'tree',
            'fa fa-trello' => 'trello',
            'fa fa-tripadvisor' => 'tripadvisor',
            'fa fa-trophy' => 'trophy',
            'fa fa-truck' => 'truck',
            'fa fa-try' => 'try',
            'fa fa-tty' => 'tty',
            'fa fa-tumblr' => 'tumblr',
            'fa fa-tumblr-square' => 'tumblr-square',
            'fa fa-turkish-lira' => 'turkish-lira',
            'fa fa-tv' => 'tv',
            'fa fa-twitch' => 'twitch',
            'fa fa-twitter' => 'twitter',
            'fa fa-twitter-square' => 'twitter-square',
            'fa fa-umbrella' => 'umbrella',
            'fa fa-underline' => 'underline',
            'fa fa-undo' => 'undo',
            'fa fa-universal-access' => 'universal-access',
            'fa fa-university' => 'university',
            'fa fa-unlink' => 'unlink',
            'fa fa-unlock' => 'unlock',
            'fa fa-unlock-alt' => 'unlock-alt',
            'fa fa-unsorted' => 'unsorted',
            'fa fa-upload' => 'upload',
            'fa fa-usb' => 'usb',
            'fa fa-usd' => 'usd',
            'fa fa-user' => 'user',
            'fa fa-user-circle' => 'user-circle',
            'fa fa-user-circle-o' => 'user-circle-o',
            'fa fa-user-md' => 'user-md',
            'fa fa-user-o' => 'user-o',
            'fa fa-user-plus' => 'user-plus',
            'fa fa-user-secret' => 'user-secret',
            'fa fa-user-times' => 'user-times',
            'fa fa-users' => 'users',
            'fa fa-vcard' => 'vcard',
            'fa fa-vcard-o' => 'vcard-o',
            'fa fa-venus' => 'venus',
            'fa fa-venus-double' => 'venus-double',
            'fa fa-venus-mars' => 'venus-mars',
            'fa fa-viacoin' => 'viacoin',
            'fa fa-viadeo' => 'viadeo',
            'fa fa-viadeo-square' => 'viadeo-square',
            'fa fa-video-camera' => 'video-camera',
            'fa fa-vimeo' => 'vimeo',
            'fa fa-vimeo-square' => 'vimeo-square',
            'fa fa-vine' => 'vine',
            'fa fa-vk' => 'vk',
            'fa fa-volume-control-phone' => 'volume-control-phone',
            'fa fa-volume-down' => 'volume-down',
            'fa fa-volume-off' => 'volume-off',
            'fa fa-volume-up' => 'volume-up',
            'fa fa-warning' => 'warning',
            'fa fa-wechat' => 'wechat',
            'fa fa-weibo' => 'weibo',
            'fa fa-weixin' => 'weixin',
            'fa fa-whatsapp' => 'whatsapp',
            'fa fa-wheelchair' => 'wheelchair',
            'fa fa-wheelchair-alt' => 'wheelchair-alt',
            'fa fa-wifi' => 'wifi',
            'fa fa-wikipedia-w' => 'wikipedia-w',
            'fa fa-window-close' => 'window-close',
            'fa fa-window-close-o' => 'window-close-o',
            'fa fa-window-maximize' => 'window-maximize',
            'fa fa-window-minimize' => 'window-minimize',
            'fa fa-window-restore' => 'window-restore',
            'fa fa-windows' => 'windows',
            'fa fa-won' => 'won',
            'fa fa-wordpress' => 'wordpress',
            'fa fa-wpbeginner' => 'wpbeginner',
            'fa fa-wpexplorer' => 'wpexplorer',
            'fa fa-wpforms' => 'wpforms',
            'fa fa-wrench' => 'wrench',
            'fa fa-xing' => 'xing',
            'fa fa-xing-square' => 'xing-square',
            'fa fa-y-combinator' => 'y-combinator',
            'fa fa-y-combinator-square' => 'y-combinator-square',
            'fa fa-yahoo' => 'yahoo',
            'fa fa-yc' => 'yc',
            'fa fa-yc-square' => 'yc-square',
            'fa fa-yelp' => 'yelp',
            'fa fa-yen' => 'yen',
            'fa fa-yoast' => 'yoast',
            'fa fa-youtube' => 'youtube',
            'fa fa-youtube-play' => 'youtube-play',
            'fa fa-youtube-square' => 'youtube-square',
        ];
    }
}