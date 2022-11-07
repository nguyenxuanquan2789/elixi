<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class AdminVecProductExtraTabsController extends ModuleAdminController {
	/**
	 * Countries_array
	 *
	 * @var array
	 */
	protected $countries_array = array();
	/**
	 * Position_identifier
	 *
	 * @var string
	 */
	protected $position_identifier = 'id_vecproductextratabs';
	/**
	 * Asso_type
	 *
	 * @var string
	 */
	public $asso_type = 'shop';

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

		$this->table            = 'vecproductextratabs';
		$this->className        = 'Vecpextratab';
		$this->lang             = true;
		$this->deleted          = false;
		$this->module           = 'vecproductextratab';
		$this->explicitSelect   = true;
		$this->_defaultOrderBy  = 'position';
		$this->allow_export     = true;
		$this->bootstrap        = true;
		$this->_defaultOrderWay = 'DESC';
		$this->context          = Context::getContext();

		parent::__construct();

		include_once VEC_PEXTRATAB_CLASS_DIR . 'vecpextratab.php';
		include_once VEC_PEXTRATAB_CLASS_DIR . 'vecpetab_ajax.php';
		include_once VEC_PEXTRATAB_CLASS_DIR . 'vecpetab_provider.php';

		$this->fields_list = array(
			'id_vecproductextratabs' => array(
				'title'   => $this->l('Id'),
				'width'   => 100,
				'type'    => 'text',
				'orderby' => false,
				'filter'  => false,
				'search'  => false,
			),
			'title' => array(
				'title'   => $this->l('Title'),
				'width'   => 440,
				'type'    => 'text',
				'lang'    => true,
				'orderby' => false,
				'filter'  => true,
				'search'  => true,
			),
			'active' => array(
				'title'   => $this->l('Status'),
				'width'   => 270,
				'align'   => 'center',
				'active'  => 'status',
				'type'    => 'bool',
				'orderby' => false,
				'filter'  => true,
				'search'  => true,
			),
			'position' => array(
				'title'      => $this->l('Position'),
				'filter_key' => 'a!position',
				'position'   => 'position',
				'search'     => false,
			),
		);
		$this->conditions = array(
			1 => array('id' =>1 , 'name' => 'All products'),
			2 => array('id' =>2 , 'name' => 'Select products'),
	        3 => array('id' =>3 , 'name' => 'Products from categories'),
	        4 => array('id' =>4 , 'name' => 'Products from brands'),
		);
	}

	/**
	 * Init inints the controller.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->_join            = 'LEFT JOIN ' . _DB_PREFIX_ . 'vecproductextratabs_shop extrabsshops ON a.id_vecproductextratabs=extrabsshops.id_vecproductextratabs && extrabsshops.id_shop IN(' . implode( ',', Shop::getContextListShopID() ) . ')';
		$this->_select          = 'extrabsshops.id_shop';
		$this->_defaultOrderBy  = 'a.position';
		$this->_defaultOrderWay = 'DESC';
		if ( Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP ) {
			$this->_group = 'GROUP BY a.id_vecproductextratabs';
		}
		$this->_select = 'a.position position';
	}

	/**
	 * SetMedia registers css and js files and js values.
	 *
	 * @param  mixed $isNewTheme in new theme.
	 * @return void
	 */
	public function setMedia( $isNewTheme = false ) {
		parent::setMedia();
		$this->addJqueryUi( 'ui.widget' );
		$this->addJqueryPlugin( 'tagify' );
		$this->addJqueryPlugin( 'autocomplete' );
		$this->addJs( VEC_PEXTRATAB_ASSETS_DIR . '/js/admin.js' );
		Media::addJsDef( array( 'vecpetab_ajaxurl' => $this->context->link->getAdminLink( 'AdminVecProductExtraTabs' ) ) );

	}

	/**
	 * GetList gets the extra tabs list.
	 *
	 * @param mixed $id_lang      language id.
	 * @param mixed $order_by     orderby param.
	 * @param mixed $order_way    oreder way param.
	 * @param mixed $start        start param.
	 * @param mixed $limit        limit param.
	 * @param mixed $id_lang_shop shop language param.
	 */
	public function getList( $id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false ) {
		if ( $order_way == null ) {
			$order_way = 'ASC';
		}
		return parent::getList( $id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop );
	}

	/**
	 * RenderList Renders the whole list.
	 */
	public function renderList() {
		if ( isset( $this->_filter ) && trim( $this->_filter ) == '' ) {
			$this->_filter = $this->original_filter;
		}

		$this->addRowAction( 'edit' );
		$this->addRowAction( 'delete' );
		return parent::renderList();
	}

	/**
	 * RenderForm renders the create and edit form.
	 *
	 * @return void
	 */
	public function renderForm() {
		$condition                    = '';
		$vecpextra_is_edit         = false;
		$specific_prd_values          = '';
		$specific_product_catg_values = '';
		$specific_product_manu_values = '';
		$product_page_values          = '';
		$products_list_array          = array();
		$cats_list_array              = array();
		$manus_list_array             = array();
		$vecpetab_hascrazy         = 0;
		$proper_url                   = '';
		$icon_url                     = '';
		$edit_all                     = true;

		if ( Tools::getvalue( 'id_vecproductextratabs' ) ) {
			$vecpextra_is_edit         = true;
			$vecproductextratabs          = new Vecpextratab( Tools::getvalue( 'id_vecproductextratabs' ) );
			$condition                    = $vecproductextratabs->condition;
			$specific_prd_values          = $vecproductextratabs->specific_product;
			$specific_product_catg_values = $vecproductextratabs->specific_product_catg;
			$specific_product_manu_values = $vecproductextratabs->specific_product_manu;

			$provider_obj        = new VecpetabProvider();
			$products_list_array = $provider_obj->getProductsById( $specific_prd_values );
			$cats_list_array     = $provider_obj->getCatsById( $specific_product_catg_values );
			$manus_list_array    = $provider_obj->getManusById( $specific_product_manu_values );
		}

		$this->fields_form = array(
			'legend'  => array(
				'title' => $this->l('Vec Additional Product Tab'),
			),
			'input'   => array(
				array(
					'type'     => 'switch',
					'label'    => $this->l('Status'),
					'name'     => 'active',
					'required' => false,
					'class'    => 't',
					'is_bool'  => true,
					'values'   => array(
						array(
							'id'    => 'active',
							'value' => 1,
							'label' => $this->l('Enabled'),
						),
						array(
							'id'    => 'active',
							'value' => 0,
							'label' => $this->l('Disabled'),
						),
					),
				),
				array(
					'type'     => 'text',
					'label'    => $this->l('Title of tab'),
					'name'     => 'title',
					'lang'     => true,
					'required' => true,
					'desc'     => $this->l('Enter Your Title'),
				),
				array(
					'type'         => 'textarea',
					'label'        => $this->l('Content of tab'),
					'name'         => 'content',
					'rows'         => 10,
					'cols'         => 62,
					'class'        => 'vecpetab_content_class rte',
					'lang'         => true,
					'autoload_rte' => true,
				),
				array(
					'type'                => 'vecpetab_content_type',
					'name'                => 'title',
					'vecpetab_is_edit' => $vecpextra_is_edit,
					'specific_prd_values' => $specific_prd_values,
					'product_page_values' => $product_page_values,
				),
				array(
		            'type' => 'select',
		            'label' => $this->l('Show this tab in products :'),
		            'name' => 'specific_product_condition',
		            'options' => array (
		                'query' => $this->conditions,
		                'id' => 'id',
		                'name' => 'name'
		           ),
		       ),
				array(
					'type'     => 'ajaxproducts',
					'label'    => $this->l('Select Products'),
					'name'     => 'specific_product_temp',
					'class'    => 'specific_product_class',
					'id'       => 'specific_product_id',
					'multiple' => true,
					'saved'    => $products_list_array,
				),
				array(
					'type'     => 'ajaxproductcats',
					'label'    => $this->l('Select Product Categories'),
					'name'     => 'specific_product_catg_temp',
					'class'    => 'specific_product_catg_class',
					'id'       => 'specific_product_catg_id',
					'multiple' => true,
					'saved'    => $cats_list_array,
				),
				array(
					'type'     => 'ajaxproductmanus',
					'label'    => $this->l('Select Product Manufacturers'),
					'name'     => 'specific_product_manu_temp',
					'class'    => 'specific_product_manu_class',
					'id'       => 'specific_product_manu_is',
					'multiple' => true,
					'saved'    => $manus_list_array,
				),
			),
			'submit'  => array(
				'title' => $this->l('Save And Close'),
				'class' => 'btn btn-default pull-right',
			),
			'buttons' => array(
				'save-and-stay' => array(
					'name'  => 'submitAdd' . $this->table . 'AndStay',
					'type'  => 'submit',
					'title' => $this->l('Save And Stay'),
					'class' => 'btn btn-default pull-right',
					'icon'  => 'process-icon-save',
				),
			),
		);
		if ( Shop::isFeatureActive() ) {
			$this->fields_form['input'][] = array(
				'type'  => 'shop',
				'label' => $this->l('Shop association:'),
				'name'  => 'checkBoxShopAsso',
			);
		}
		if ( ! ( $vecproductextratabs = $this->loadObject( true ) ) ) {
			return;
		}
		$this->fields_form['submit'] = array(
			'title' => $this->l('Save And Close'),
			'class' => 'btn btn-default pull-right',
		);

		if ( ! Tools::getvalue( 'id_vecproductextratabs' ) ) {
			$this->fields_value['condition'] = 1;
		} else {
			$vecproductextratabs = new Vecpextratab( Tools::getvalue( 'id_vecproductextratabs' ) );
			$this->fields_value['specific_product_temp']      = $vecproductextratabs->specific_product;
			$this->fields_value['specific_product_catg_temp'] = $vecproductextratabs->specific_product_catg;
			$this->fields_value['specific_product_manu_temp'] = $vecproductextratabs->specific_product_manu;
			$this->fields_value['specific_product_condition'] = $vecproductextratabs->condition;
		}
		return parent::renderForm();
	}

	/**
	 * InitToolbar inits toolbar.
	 *
	 * @return void
	 */
	public function initToolbar() {
		parent::initToolbar();
	}

	/**
	 * InitContent initializes the whole content.
	 */
	public function initContent() {

		if ( Tools::getvalue( 'vecpetab_ajaxgetproducts' ) ) {
			$ajax_obj = new VecpetabAjax();
			echo $ajax_obj->getProductsByName();
			die();
		}
		if ( Tools::getvalue( 'vecpetab_ajaxgetcats' ) ) {
			$ajax_obj = new VecpetabAjax();
			echo $ajax_obj->getCatsByName();
			die();
		}
		if ( Tools::getvalue( 'vecpetab_ajaxgetmanus' ) ) {
			$ajax_obj = new VecpetabAjax();
			echo $ajax_obj->getManusByName();
			die();
		}

		return parent::initContent();
	}

	/**
	 * ProcessPosition processes the position of the items in the list.
	 *
	 * @return void
	 */
	public function processPosition() {

		if ( $this->tabAccess['edit'] !== '1' ) {
			$this->errors[] = Tools::displayError( 'You do not have permission to edit this.' );
		} elseif ( ! Validate::isLoadedObject( $object = new Vecpextratab( (int) Tools::getValue( $this->identifier, Tools::getValue( 'id_vecproductextratabs', 1 ) ) ) ) ) {
			$this->errors[] = Tools::displayError( 'An error occurred while updating the status for an object.' ) . ' <b>' .
			$this->table . '</b> ' . Tools::displayError( '(cannot load object)' );
		}
		if ( ! $object->updatePosition( (int) Tools::getValue( 'way'), (int) Tools::getValue( 'position' ) ) ) {
			$this->errors[] = Tools::displayError( 'Failed to update the position.' );
		} else {
			$object->regenerateEntireNtree();
			Tools::redirectAdmin( self::$currentIndex . '&' . $this->table . 'Orderby=position&' . $this->table . 'Orderway=asc&conf=5' . ( ( $id_vecproductextratabs = (int) Tools::getValue( $this->identifier ) ) ? ( '&' . $this->identifier . '=' . $id_vecproductextratabs ) : '' ) . '&token=' . Tools::getAdminTokenLite( 'Admintabcreation' ) );
		}
	}

	/**
	 * AjaxProcessUpdatePositions called when updating the position of the items of the list.
	 *
	 * @return void
	 */
	public function ajaxProcessUpdatePositions() {
		$id_vecproductextratabs = (int) ( Tools::getValue( 'id' ) );
		$way                       = (int) ( Tools::getValue( 'way' ) );
		$positions                 = Tools::getValue( $this->table );
		if ( is_array( $positions ) ) {
			foreach ( $positions as $key => $value ) {
				$pos = explode( '_', $value );
				if ( ( isset( $pos[1] ) && isset( $pos[2] ) ) && ( $pos[2] == $id_vecproductextratabs ) ) {
					$position = $key + 1;
					break;
				}
			}
		}

		$vecproductextratabs = new Vecpextratab( $id_vecproductextratabs );
		if ( Validate::isLoadedObject( $vecproductextratabs ) ) {
			if ( isset( $position ) && $vecproductextratabs->updatePosition( $way, $position ) ) {
				Hook::exec( 'actionvecproductextratabsUpdate' );
				die( true );
			} else {
				die( '{"hasError" : true, errors : "Can not update vecproductextratabs position"}' );
			}
		} else {
			die( '{"hasError" : true, "errors" : "This vecproductextratabs can not be loaded"}' );
		}
	}

	/**
	 * ProcessSave saves the item.
	 */
	public function processSave() {

		if ( Tools::isSubmit( 'submitAddvecproductextratabsAndStay' )
			|| Tools::isSubmit( 'submitAddvecproductextratabs' )
		) {
			$object = parent::processSave();

			$object->specific_product = Tools::getValue( 'inputAccessories' );			
			$object->specific_product_catg = Tools::getValue( 'inputCatAccessories' );
			$object->specific_product_manu = Tools::getValue( 'inputManuAccessories' );
			$object->condition = Tools::getValue( 'specific_product_condition' );

			if ( $object ) {
				$object->update();

				return $object;
			} else {
				return false;
			}
		}

		return true;
	}

}