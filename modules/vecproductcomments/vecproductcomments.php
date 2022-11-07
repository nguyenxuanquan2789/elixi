<?php
/*
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class VecProductComments extends Module implements WidgetInterface
{
	const INSTALL_SQL_FILE = 'install.sql';

	private $_html = '';
	private $_postErrors = array();
	private $_filters = array();

	private $_productCommentsCriterionTypes = array();
	private $_baseUrl;

	public function __construct()
	{
		$this->name = 'vecproductcomments';
		$this->tab = 'front_office_features';
		$this->version = '1.5.0';
		$this->author = 'ThemeVec';
		$this->need_instance = 0;
		$this->bootstrap = true;

		$this->_setFilters();

		parent::__construct();

		$this->secure_key = Tools::encrypt($this->name);

		$this->displayName = $this->l('Vec - Product Comments');
		$this->description = $this->l('Allows users to post reviews and rate products on specific criteria.');
	}

	public function install($keep = true)
	{
		if ($keep)
		{
			if (!file_exists(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			else if (!$sql = file_get_contents(dirname(__FILE__).'/'.self::INSTALL_SQL_FILE))
				return false;
			$sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
			$sql = preg_split("/;\s*[\r\n]+/", trim($sql));

			foreach ($sql as $query)
				if (!Db::getInstance()->execute(trim($query)))
					return false;

		}

		if (parent::install() == false ||
			!$this->registerHook('displayProductTab') ||
			!$this->_createMenu() ||
			!$this->registerHook('displayProductTabContent') ||
			!$this->registerHook('header') ||
			!$this->registerHook('displayHeader') ||
			!$this->registerHook('displayReviewsProduct') ||
			!$this->registerHook('displayProductListReviews') ||
			!Configuration::updateValue('VEC_PRODUCT_COMMENTS_MINIMAL_TIME', 30) ||
			!Configuration::updateValue('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS', 1) ||
			!Configuration::updateValue('VEC_PRODUCT_COMMENTS_MODERATE', 1))
			return false;
		return true;
	}

	public function uninstall($keep = true)
	{
		if (!parent::uninstall() || ($keep && !$this->deleteTables()) ||
			!Configuration::deleteByName('VEC_PRODUCT_COMMENTS_MODERATE') ||
			!Configuration::deleteByName('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS') ||
			!Configuration::deleteByName('VEC_PRODUCT_COMMENTS_MINIMAL_TIME') ||
			!$this->unregisterHook('displayReviewsProduct') ||
			!$this->unregisterHook('displayProductTabContent') ||
			!$this->unregisterHook('header') ||
			!$this->unregisterHook('displayProductTab') ||
			!$this->_deleteMenu() || 
			!$this->unregisterHook('displayProductListReviews'))
			return false;
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
        $tab->class_name = "AdminVecProductComments";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Product comments";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminVecProductComments');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

	public function reset()
	{
		if (!$this->uninstall(false))
			return false;
		if (!$this->install(false))
			return false;
		return true;
	}

	public function deleteTables()
	{
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'vec_product_comment`,
			`'._DB_PREFIX_.'vec_product_comment_criterion`,
			`'._DB_PREFIX_.'vec_product_comment_criterion_product`,
			`'._DB_PREFIX_.'vec_product_comment_criterion_lang`,
			`'._DB_PREFIX_.'vec_product_comment_criterion_category`,
			`'._DB_PREFIX_.'vec_product_comment_grade`');
	}
	
	public function getCacheId($id_product = null)
	{
		return parent::getCacheId().'|'.(int)$id_product;
	}

	protected function _postProcess()
	{
		$this->_setFilters();

		if (Tools::isSubmit('submitModerate'))
		{
			Configuration::updateValue('VEC_PRODUCT_COMMENTS_MODERATE', (int)Tools::getValue('VEC_PRODUCT_COMMENTS_MODERATE'));
			Configuration::updateValue('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS', (int)Tools::getValue('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS'));
			Configuration::updateValue('VEC_PRODUCT_COMMENTS_MINIMAL_TIME', (int)Tools::getValue('VEC_PRODUCT_COMMENTS_MINIMAL_TIME'));
			$this->_html .= '<div class="conf confirm alert alert-success">'.$this->l('Settings updated').'</div>';
		}
		elseif (Tools::isSubmit('vecproductcomments'))
		{
			$id_product_comment = (int)Tools::getValue('id_product_comment');
			$comment = new VecProductComment($id_product_comment);
			$comment->validate();
		}
		elseif (Tools::isSubmit('deletevecproductcomments'))
		{
			$id_product_comment = (int)Tools::getValue('id_product_comment');
			$comment = new VecProductComment($id_product_comment);
			$comment->delete();
		}
		elseif (Tools::isSubmit('submitEditCriterion'))
		{
			$criterion = new VecProductCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
			$criterion->id_product_comment_criterion_type = Tools::getValue('id_product_comment_criterion_type');
			$criterion->active = Tools::getValue('active');

			$languages = Language::getLanguages();
			$name = array();
			foreach ($languages as $key => $value) {
				$name[$value['id_lang']] = Tools::getValue('name_'.$value['id_lang']);
			}
			$criterion->name = $name;

			$criterion->save();

			// Clear before reinserting data
			$criterion->deleteCategories();
			$criterion->deleteProducts();
			if ($criterion->id_product_comment_criterion_type == 2)
			{
				if ($categories = Tools::getValue('categoryBox'))
					if (count($categories))
						foreach ($categories as $id_category)
							$criterion->addCategory((int)$id_category);
			}
			else if ($criterion->id_product_comment_criterion_type == 3)
			{
				if ($products = Tools::getValue('ids_product'))
					if (count($products))
						foreach ($products as $product)
							$criterion->addProduct((int)$product);
			}
			if ($criterion->save())
				Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules').'&configure='.$this->name.'&conf=4');
			else
				$this->_html .= '<div class="conf confirm alert alert-danger">'.$this->l('The criterion could not be saved').'</div>';
		}
		elseif (Tools::isSubmit('deletevecproductcommentscriterion'))
		{
			$productCommentCriterion = new VecProductCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
			if ($productCommentCriterion->id)
				if ($productCommentCriterion->delete())
					$this->_html .= '<div class="conf confirm alert alert-success">'.$this->l('Criterion deleted').'</div>';
		}
		elseif (Tools::isSubmit('statusvecproductcommentscriterion'))
		{
			$criterion = new VecProductCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
			if ($criterion->id)
			{
				$criterion->active = (int)(!$criterion->active);
				$criterion->save();
			}
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&conf=4&module_name='.$this->name);
		}
		elseif ($id_product_comment = (int)Tools::getValue('approveComment'))
		{
			$comment = new VecProductComment($id_product_comment);
			$comment->validate();
		}

		$this->_clearcache('vecproductcomments_reviews.tpl');
	}

	public function getContent()
	{
		include_once(dirname(__FILE__).'/VecProductComment.php');
		include_once(dirname(__FILE__).'/VecProductCommentCriterion.php');

		$this->_html  = '';
		if (Tools::isSubmit('updatevecproductcommentscriterion'))
			$this->_html .= $this->renderCriterionForm((int)Tools::getValue('id_product_comment_criterion'));
		else
		{
			$this->_postProcess();
			$this->_html .= $this->renderConfigForm();
			$this->_html .= $this->renderModerateLists();
			$this->_html .= $this->renderCriterionList();
			$this->_html .= $this->renderCommentsList();
		}

		$this->_setBaseUrl();
		$this->_productCommentsCriterionTypes = VecProductCommentCriterion::getTypes();

		$this->context->controller->addJs($this->_path.'js/moderate.js');

		return $this->_html;
	}

	private function _setBaseUrl()
	{
		$this->_baseUrl = 'index.php?';
		foreach ($_GET as $k => $value)
			if (!in_array($k, array('deleteCriterion', 'editCriterion')))
				$this->_baseUrl .= $k.'='.$value.'&';
		$this->_baseUrl = rtrim($this->_baseUrl, '&');
	}

	public function renderConfigForm()
	{
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Configuration'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'is_bool' => true, //retro compat 1.5
						'label' => $this->l('All reviews must be validated by an employee'),
						'name' => 'VEC_PRODUCT_COMMENTS_MODERATE',
						'values' => array(
										array(
											'id' => 'active_on',
											'value' => 1,
											'label' => $this->l('Enabled')
										),
										array(
											'id' => 'active_off',
											'value' => 0,
											'label' => $this->l('Disabled')
										)
									),
					),
					array(
						'type' => 'switch',
						'is_bool' => true, //retro compat 1.5
						'label' => $this->l('Allow guest reviews'),
						'name' => 'VEC_PRODUCT_COMMENTS_ALLOW_GUESTS',
						'values' => array(
										array(
											'id' => 'active_on',
											'value' => 1,
											'label' => $this->l('Enabled')
										),
										array(
											'id' => 'active_off',
											'value' => 0,
											'label' => $this->l('Disabled')
										)
									),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Minimum time between 2 reviews from the same user'),
						'name' => 'VEC_PRODUCT_COMMENTS_MINIMAL_TIME',
						'class' => 'fixed-width-xs',
						'suffix' => 'seconds',
					),
				),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right',
				'name' => 'submitModerate',
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->name;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitProducCommentsConfiguration';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_1));
	}

	public function renderModerateLists()
	{
		require_once(dirname(__FILE__).'/VecProductComment.php');
		$return = null;

		if (Configuration::get('VEC_PRODUCT_COMMENTS_MODERATE'))
		{
			$comments = VecProductComment::getByValidate(0, false);

			$fields_list = $this->getStandardFieldList();

			
			$actions = array('approve', 'delete');

			$helper = new HelperList();
			$helper->shopLinkType = '';
			$helper->simple_header = true;
			$helper->actions = $actions;
			$helper->show_toolbar = false;
			$helper->module = $this;
			$helper->listTotal = count($comments);
			$helper->identifier = 'id_product_comment';
			$helper->title = $this->l('Reviews waiting for approval');
			$helper->table = $this->name;
			$helper->token = Tools::getAdminTokenLite('AdminModules');
			$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
			//$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

			$return .= $helper->generateList($comments, $fields_list);
		}

		return $return;

	}

	public function renderCriterionList()
	{

		include_once(dirname(__FILE__).'/VecProductCommentCriterion.php');

		$criterions = VecProductCommentCriterion::getCriterions($this->context->language->id, false, false);

		$fields_list = array(
			'id_product_comment_criterion' => array(
				'title' => $this->l('ID'),
				'type' => 'text',
			),
			'name' => array(
				'title' => $this->l('Name'),
				'type' => 'text',
			),
			'type_name' => array(
				'title' => $this->l('Type'),
				'type' => 'text',
			),
			'active' => array(
				'title' => $this->l('Status'),
				'active' => 'status',
				'type' => 'bool',
			),
		);

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = false;
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->toolbar_btn['new'] = array(
			'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&updatevecproductcommentscriterion',
			'desc' => $this->l('Add New Criterion', null, null, false)
		);
		$helper->module = $this;
		$helper->identifier = 'id_product_comment_criterion';
		$helper->title = $this->l('Review Criterions');
		$helper->table = $this->name.'criterion';
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		//$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

		return $helper->generateList($criterions, $fields_list);
	}

	public function renderCommentsList()
	{
		require_once(dirname(__FILE__).'/VecProductComment.php');

		$comments = VecProductComment::getByValidate(1, false);

		$fields_list = $this->getStandardFieldList();

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->actions = array('delete');
		$helper->show_toolbar = false;
		$helper->module = $this;
		$helper->listTotal = count($comments);
		$helper->identifier = 'id_product_comment';
		$helper->title = $this->l('Approved Reviews');
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		//$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

		return $helper->generateList($comments, $fields_list);
	}


	public function getConfigFieldsValues()
	{
		return array(
			'VEC_PRODUCT_COMMENTS_MODERATE' => Tools::getValue('VEC_PRODUCT_COMMENTS_MODERATE', Configuration::get('VEC_PRODUCT_COMMENTS_MODERATE')),
			'VEC_PRODUCT_COMMENTS_ALLOW_GUESTS' => Tools::getValue('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS', Configuration::get('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS')),
			'VEC_PRODUCT_COMMENTS_MINIMAL_TIME' => Tools::getValue('VEC_PRODUCT_COMMENTS_MINIMAL_TIME', Configuration::get('VEC_PRODUCT_COMMENTS_MINIMAL_TIME')),
		);
	}

	public function getCriterionFieldsValues($id = 0)
	{
		$criterion = new VecProductCommentCriterion($id);

		return array(
					'name' => $criterion->name,
					'id_product_comment_criterion_type' => $criterion->id_product_comment_criterion_type,
					'active' => $criterion->active,
					'id_product_comment_criterion' => $criterion->id,
				);
	}

	public function getStandardFieldList()
	{
		return array(
			'id_product_comment' => array(
				'title' => $this->l('ID'),
				'type' => 'text',
			),
			'title' => array(
				'title' => $this->l('Review title'),
				'type' => 'text',
			),
			'content' => array(
				'title' => $this->l('Review'),
				'type' => 'text',
			),
			'grade' => array(
				'title' => $this->l('Rating'),
				'type' => 'text',
				'suffix' => '/5',
			),
			'customer_name' => array(
				'title' => $this->l('Author'),
				'type' => 'text',
			),
			'name' => array(
				'title' => $this->l('Product'),
				'type' => 'text',
			),
			'date_add' => array(
				'title' => $this->l('Time of publication'),
				'type' => 'date',
			),
		);
	}

	function renderCriterionForm($id_criterion = 0)
	{
		$types = VecProductCommentCriterion::getTypes();
		$query =array();
		foreach ($types as $key => $value)
		{
			$query[] = array(
					'id' => $key,
					'label' => $value,
				);
		}

		$criterion = new VecProductCommentCriterion((int)$id_criterion);
		$selected_categories = $criterion->getCategories();

		$product_table_values = Product::getSimpleProducts($this->context->language->id);
		$selected_products = $criterion->getProducts();
		foreach ($product_table_values as $key => $product) {
			if(false !== array_search($product['id_product'], $selected_products))
				$product_table_values[$key]['selected'] = 1;
		}

		if (version_compare(_PS_VERSION_, '1.6', '<'))
			$field_category_tree = array(
									'type' => 'categories_select',
									'name' => 'categoryBox',
									'label' => $this->l('Criterion will be restricted to the following categories'),
									'category_tree' => $this->initCategoriesAssociation(null, $id_criterion),
								);
		else
			$field_category_tree = array(
							'type' => 'categories',
							'label' => $this->l('Criterion will be restricted to the following categories'),
							'name' => 'categoryBox',
							'desc' => $this->l('Mark the boxes of categories to which this criterion applies.'),
							'tree' => array(
								'use_search' => false,
								'id' => 'categoryBox',
								'use_checkbox' => true,
								'selected_categories' => $selected_categories,
							),
							//retro compat 1.5 for category tree
							'values' => array(
								'trads' => array(
									'Root' => Category::getTopCategory(),
									'selected' => $this->l('Selected'),
									'Collapse All' => $this->l('Collapse All'),
									'Expand All' => $this->l('Expand All'),
									'Check All' => $this->l('Check All'),
									'Uncheck All' => $this->l('Uncheck All')
								),
								'selected_cat' => $selected_categories,
								'input_name' => 'categoryBox[]',
								'use_radio' => false,
								'use_search' => false,
								'disabled_categories' => array(),
								'top_category' => Category::getTopCategory(),
								'use_context' => true,
							)
						);

		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Add new criterion'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'hidden',
						'name' => 'id_product_comment_criterion',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Criterion name'),
						'name' => 'name',
					),
					array(
						'type' => 'select',
						'name' => 'id_product_comment_criterion_type',
						'label' => $this->l('Application scope of the criterion'),
						'options' => array(
										'query' => $query,
										'id' => 'id',
										'name' => 'label'
									),
					),
					$field_category_tree,
					array(
						'type' => 'products',
						'label' => $this->l('The criterion will be restricted to the following products'),
						'name' => 'ids_product',
						'values' => $product_table_values,
					),
					array(
						'type' => 'switch',
						'is_bool' => true, //retro compat 1.5
						'label' => $this->l('Active'),
						'name' => 'active',
						'values' => array(
										array(
											'id' => 'active_on',
											'value' => 1,
											'label' => $this->l('Enabled')
										),
										array(
											'id' => 'active_off',
											'value' => 0,
											'label' => $this->l('Disabled')
										)
									),
					),
				),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right',
				'name' => 'submitEditCriterion',
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->name;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitEditCriterion';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getCriterionFieldsValues($id_criterion),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form_1));
	}

	private function _checkDeleteComment()
	{
		$action = Tools::getValue('delete_action');
		if (empty($action) === false)
		{
			$product_comments = Tools::getValue('delete_id_product_comment');

			if (count($product_comments))
			{
				require_once(dirname(__FILE__).'/VecProductComment.php');
				if ($action == 'delete')
				{
					foreach ($product_comments as $id_product_comment)
					{
						if (!$id_product_comment)
							continue;
						$comment = new VecProductComment((int)$id_product_comment);
						$comment->delete();
						VecProductComment::deleteGrades((int)$id_product_comment);
					}
				}
			}
		}
	}

	private function _setFilters()
	{
		$this->_filters = array(
							'page' => (string)Tools::getValue('submitFilter'.$this->name),
							'pagination' => (string)Tools::getValue($this->name.'_pagination'),
							'filter_id' => (string)Tools::getValue($this->name.'Filter_id_product_comment'),
							'filter_content' => (string)Tools::getValue($this->name.'Filter_content'),
							'filter_customer_name' => (string)Tools::getValue($this->name.'Filter_customer_name'),
							'filter_grade' => (string)Tools::getValue($this->name.'Filter_grade'),
							'filter_name' => (string)Tools::getValue($this->name.'Filter_name'),
							'filter_date_add' => (string)Tools::getValue($this->name.'Filter_date_add'),
						);
	}

	public function displayApproveLink($token = null, $id, $name = null)
	{
		$this->smarty->assign(array(
			'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&approveComment='.$id,
			'action' => $this->l('Approve'),
		));

		return $this->display(__FILE__, 'views/templates/admin/list_action_approve.tpl');
	}

	public function displayNoabuseLink($token = null, $id, $name = null)
	{
		$this->smarty->assign(array(
			'href' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&module_name='.$this->name.'&noabuseComment='.$id,
			'action' => $this->l('Not abusive'),
		));

		return $this->display(__FILE__, 'views/templates/admin/list_action_noabuse.tpl');
	}

	public function hookDisplayProductTab($params)
	{
		return ($this->display(__FILE__, '/views/templates/hook/tab.tpl'));
	}

	public function hookDisplayProductListReviews($params)
	{
		$id_product = (int)$params['product']['id_product'];
		if (!$this->isCached('module:vecproductcomments/views/templates/hook/vecproductcomments_reviews.tpl', $this->getCacheId($id_product)))
		{
			require_once(dirname(__FILE__).'/VecProductComment.php');
			$average = VecProductComment::getAverageGrade($id_product);
			$avg_percent = 0;
			if($average){
				$avg_percent = round($average['grade']/5*100, 1);
			}
			$this->smarty->assign(array(
				'product' => $params['product'],
				'avg_percent' => $avg_percent,
				'averageTotal' => round($average['grade']),
				'ratings' => VecProductComment::getRatings($id_product),
				'nbComments' => (int)VecProductComment::getCommentNumber($id_product)
			));
		}
		return $this->fetch('module:vecproductcomments/views/templates/hook/vecproductcomments_reviews.tpl', $this->getCacheId($id_product));
	}

	public function hookDisplayReviewsProduct($params)
	{
		require_once(dirname(__FILE__).'/VecProductComment.php');
		require_once(dirname(__FILE__).'/VecProductCommentCriterion.php');

		$id_guest = (!$id_customer = (int)$this->context->cookie->id_customer) ? (int)$this->context->cookie->id_guest : false;
		$customerComment = VecProductComment::getByCustomer((int)(Tools::getValue('id_product')), (int)$this->context->cookie->id_customer, true, (int)$id_guest);

		$average = VecProductComment::getAverageGrade((int)Tools::getValue('id_product'));
		$avg_percent = 0;
		if($average){
			$avg_percent = round($average['grade']/5*100, 1);
		}
		$this->context->smarty->assign(array(
			'id_product_comment_form' => (int)Tools::getValue('id_product'),
			'secure_key' => $this->secure_key,
			'avg_percent' => $avg_percent,
			'logged' => $this->context->customer->isLogged(true),
			'allow_guests' => (int)Configuration::get('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS'),
			'criterions' => VecProductCommentCriterion::getByProduct((int)Tools::getValue('id_product'), $this->context->language->id),
			'action_url' => '',
			'averageTotal_extra' => round($average['grade']),
			'ratings' => VecProductComment::getRatings((int)Tools::getValue('id_product')),
			'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('VEC_PRODUCT_COMMENTS_MINIMAL_TIME')) > time()),
			'nbComments_extra' => (int)(VecProductComment::getCommentNumber((int)Tools::getValue('id_product')))
	   ));

		return ($this->display(__FILE__, '/views/templates/hook/vecproductcomments-extra.tpl'));
	}

	public function hookDisplayProductTabContent($params)
	{
		require_once(dirname(__FILE__).'/VecProductComment.php');
		require_once(dirname(__FILE__).'/VecProductCommentCriterion.php');
		
		$id_guest = (!$id_customer = (int)$this->context->cookie->id_customer) ? (int)$this->context->cookie->id_guest : false;
		$customerComment = VecProductComment::getByCustomer((int)(Tools::getValue('id_product')), (int)$this->context->cookie->id_customer, true, (int)$id_guest);

		$averages = VecProductComment::getAveragesByProduct((int)Tools::getValue('id_product'), $this->context->language->id);
		$averageTotal = 0;
		foreach ($averages as $average)
			$averageTotal += (float)($average);
		$averageTotal = count($averages) ? ($averageTotal / count($averages)) : 0;

		$product = $this->context->controller->getProduct();

		$image = Product::getCover((int)Tools::getValue('id_product'));
		$cover_image = $this->context->link->getImageLink($product->link_rewrite, $image['id_image'], 'medium_default');
		$this->context->smarty->assign(array(
			'logged' => $this->context->customer->isLogged(true),
			'action_url' => '',
			'product' => $product,
			'comments' => VecProductComment::getByProduct((int)Tools::getValue('id_product'), 1, null, $this->context->cookie->id_customer),
			'criterions' => VecProductCommentCriterion::getByProduct((int)Tools::getValue('id_product'), $this->context->language->id),
			'averages' => $averages,
			'product_comment_path' => $this->_path,
			'averageTotal' => $averageTotal,
			'allow_guests' => (int)Configuration::get('VEC_PRODUCT_COMMENTS_ALLOW_GUESTS'),
			'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('VEC_PRODUCT_COMMENTS_MINIMAL_TIME')) > time()),
			'delay' => Configuration::get('VEC_PRODUCT_COMMENTS_MINIMAL_TIME'),
			'id_product_comment_form' => (int)Tools::getValue('id_product'),
			'secure_key' => $this->secure_key,
			'productcomment_cover' => (int)Tools::getValue('id_product').'-'.(int)$image['id_image'],
			'productcomment_cover_image' => $cover_image,
			'nbComments' => (int)VecProductComment::getCommentNumber((int)Tools::getValue('id_product')),
			'vecproductcomments_controller_url' => $this->context->link->getModuleLink('vecproductcomments'),
			'vecproductcomments_url_rewriting_activated' => Configuration::get('PS_REWRITING_SETTINGS', 0),
			'moderation_active' => (int)Configuration::get('VEC_PRODUCT_COMMENTS_MODERATE')
	   ));

		//$this->context->controller->pagination((int)VecProductComment::getCommentNumber((int)Tools::getValue('id_product')));

		return ($this->display(__FILE__, '/views/templates/hook/vecproductcomments.tpl'));
	}

 	public function hookHeader()
	{
		$this->context->controller->registerStylesheet('modules-vecproductcomments', 'modules/'.$this->name.'/views/css/vecproductcomments.css', ['media' => 'all', 'priority' => 150]);
    
		$this->page_name = Dispatcher::getInstance()->getController();
		if (in_array($this->page_name, array('product')))
		{  
			$this->context->controller->registerJavascript('modules-textareaCounter', 'modules/'.$this->name.'/js/jquery.textareaCounter.plugin.js', ['position' => 'bottom', 'priority' => 150]);
			$this->context->controller->registerJavascript('modules-rating', 'modules/'.$this->name.'/js/jquery.rating.pack.js', ['position' => 'bottom', 'priority' => 150]);
			$this->context->controller->registerJavascript('modules-vecproductcomments', 'modules/'.$this->name.'/views/js/vecproductcomments.js', ['position' => 'bottom', 'priority' => 150]);
		}
	}


	public function initCategoriesAssociation($id_root = null, $id_criterion = 0)
	{
		if (is_null($id_root))
			$id_root = Configuration::get('PS_ROOT_CATEGORY');
		$id_shop = (int)Tools::getValue('id_shop');
		$shop = new Shop($id_shop);
		if ($id_criterion == 0)
			$selected_cat = array();
		else
		{
			$pdc_object = new VecProductCommentCriterion($id_criterion);
			$selected_cat = $pdc_object->getCategories();
		}

		if (Shop::getContext() == Shop::CONTEXT_SHOP && Tools::isSubmit('id_shop'))
			$root_category = new Category($shop->id_category);
		else
			$root_category = new Category($id_root);
		$root_category = array('id_category' => $root_category->id, 'name' => $root_category->name[$this->context->language->id]);

		$helper = new Helper();
		return $helper->renderCategoryTree($root_category, $selected_cat, 'categoryBox', false, true);
	}
	public function renderWidget($hookName = null, array $configuration = [])
    {
		return false;
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        return false;
    }
}
