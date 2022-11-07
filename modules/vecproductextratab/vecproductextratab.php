<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class VecProductextratab extends Module {

	public function __construct() {

		$this->name                   = 'vecproductextratab';
		$this->version                = '1.1.0';
		$this->author                 = 'ThemeVec';
		$this->need_instance          = 0;
		$this->bootstrap              = true;

		parent::__construct();

		$this->displayName = $this->l( 'Vec - Product Additional Tabs' );
		$this->description = $this->l( 'Add extra product tab to product detail page.' );

		$this->confirmUninstall = $this->l( 'Are you sure you want to uninstall?' );

		$this->ps_versions_compliancy = array(
			'min' => '1.7',
			'max' => _PS_VERSION_,
		);

		$this->define_constants();
	}

	/**
	 * Install function runs on installing the module.
	 */
	public function install() {
		$this->insertTables();
		return parent::install()
		&& $this->_createMenu()
		&& $this->registerHook( 'displayProductExtraContent' )
		&& $this->registerHook( 'backOfficeHeader' );
	}

	/**
	 * Uninstall function runs on uninstallation of the module.
	 */
	public function uninstall() {
		return ( parent::uninstall()
		&& $this->deleteTables()
		&& $this->_deleteMenu() );
	}

	public function getContent(){
		$token = Tools::getAdminTokenLite('AdminVecProductExtraTabs');
     	$currentIndex='index.php?controller=AdminVecProductExtraTabs&token='.$token;
		Tools::redirectAdmin($currentIndex);
	}

	/**
	 * Define_constants function defines constants.
	 *
	 * @return void
	 */
	private function define_constants() {

		if ( ! defined( 'VEC_PEXTRATAB_URL' ) ) {
			define( 'VEC_PEXTRATAB_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . '/modules/' . 'vecproductextratab/' );
		}

		if ( ! defined( 'VEC_PEXTRATAB_CLASS_DIR' ) ) {
			define( 'VEC_PEXTRATAB_CLASS_DIR', _PS_MODULE_DIR_ . 'vecproductextratab/classes/' );
		}

		if ( ! defined( 'VEC_PEXTRATAB_ASSETS_DIR' ) ) {
			define( 'VEC_PEXTRATAB_ASSETS_DIR', _PS_MODULE_DIR_ . 'vecproductextratab/assets/' );
		}

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
        $tab->class_name = "AdminVecProductExtraTabs";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "Additional tabs";
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
        
        $id_tab = (int)Tab::getIdFromClassName('AdminVecProductExtraTabs');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    /**
	 * InsertTables in sertst tables for the module.
	 */
	private function insertTables() {
		$sql = array();
		include_once dirname( __FILE__ ) . '/helpers/install_sql.php';
		if ( is_array( $sql ) && ! empty( $sql ) ) {
			foreach ( $sql as $sq ) :
				if ( ! Db::getInstance()->Execute( $sq ) ) {
					return false;
				}
			endforeach;
		};
		return true;
	}

	private function deleteTables() {
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`'._DB_PREFIX_.'vecproductextratabs`,
			`'._DB_PREFIX_.'vecproductextratabs_lang`,
			`'._DB_PREFIX_.'vecproductextratabs_shop`');
	}

	/**
	 * HookdisplayProductExtraContent hook callback for the hook "displayProductExtraContent"
	 *
	 * @param mixed $params pramaeters for the functions.
	 */
	public function hookdisplayProductExtraContent( $params ) {

		include_once VEC_PEXTRATAB_CLASS_DIR . 'vecpextratab.php';

		$id_product = Tools::getValue( 'id_product' );

		$vecextraobg = new Vecpextratab();

		$results = $vecextraobg->GetTabContentByProductId( $id_product, 'title' );

		$array = array();
		foreach ( $results as $result ) {
			$content = $result['content'];
			
			$array[] = ( new PrestaShop\PrestaShop\Core\Product\ProductExtraContent() )
				->setTitle( $result['title'] )
				->setContent( $content );
		}
		return $array;
	}

	/**
	 * HookDisplayBackOfficeHeader
	 *
	 * @return void
	 */
	public function hookDisplayBackOfficeHeader() {
		$this->context->controller->addCSS( VEC_PEXTRATAB_ASSETS_DIR . '/css/admin.css' );
	}
	/**
	 * HookBackOfficeHeader
	 */
	public function hookBackOfficeHeader() {
		return $this->hookDisplayBackOfficeHeader();
	}
}