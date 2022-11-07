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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__).'/src/VecWishlistProduct.php';

class VecWishlist extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'vecwishlist';
		$this->tab = 'front_office_features';
        $this->version = '1.0.8';
		$this->author = 'ThemeVec';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->controllers = array('view');
        parent::__construct();
        $this->displayName = $this->l('Vec - Wishlist block');
        $this->description = $this->l('Adds a block containing the customer\'s wishlists.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
	
    public function install()
    {
        return parent::install()
			&& $this->createTables()
            && $this->registerHook('registerGDPRConsent')
            && $this->registerHook('actionDeleteGDPRCustomer')
            && $this->registerHook('actionExportGDPRData')
            && $this->registerHook('actionProductDelete')
            && $this->registerHook('header')
            && $this->registerHook('displayCustomerAccount')
			&& $this->registerHook('displayWishlistHeader')
            && $this->registerHook('displayWishlistButton')
			&& $this->registerHook('displayMenuMobileCanVas')
			&& $this->registerHook('displayMyAccountCanVas')
            && $this->registerHook('displayBeforeBodyClosingTag');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->dropTables();
    }
	
    public function createTables()
    {
        $return = true;
        $this->dropTables();

        $return &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'vec_wishlist_product` (
				`id_vec_wishlist_product` int(10) NOT NULL auto_increment,
				`id_product` int(10) unsigned NOT NULL,
				`id_product_attribute` int(10) unsigned NOT NULL,
				`id_customer` int(10) unsigned NOT NULL,
				`id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY  (`id_vec_wishlist_product`, `id_product` ,`id_product_attribute`, `id_customer`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
					
        return $return;
    }

    public function dropTables()
    {
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'vec_wishlist_product`');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->registerStylesheet($this->name.'-css', 'modules/'.$this->name.'/views/css/front.css', ['media' => 'all', 'priority' => 998]);
        $this->context->controller->registerJavascript($this->name.'-js', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 150]);
		if ($this->context->customer->isLogged()){
            $productsIds = VecWishlistProduct::getWishlistProductsIds((int)$this->context->customer->id);
        }else{
            $productsIds = [];
        }
        

        Media::addJsDef(array(
			'wishListVar' => array(
					'login_url' => $this->context->link->getPageLink('my-account', true),
					'ids' =>  $productsIds,
                    'actions' => $this->context->link->getModuleLink('vecwishlist', 'actions', array(), null, null, null, true),
					'alert' => ['add' => $this->l('Add to Wishlist'),
								'view' => $this->l('Remove to Wishlist')],
                    'loggin_required_text' => $this->l('You have to login to use wishlist'),
                    'loggin_text' => $this->l('Login'),
        )));
		
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }
		
		$templateFile = 'module:' . $this->name . '/views/templates/hook/' . 'wishlist-top.tpl';
		
		$cacheId = 'nbWishList';
		
        if (preg_match('/^displayCustomerAccount\d*$/', $hookName)) {
            $templateFile = 'module:' . $this->name . '/views/templates/hook/' . 'customer-account.tpl';
			$cacheId = 'acWishList';
        } elseif (preg_match('/^displayBeforeBodyClosingTag\d*$/', $hookName)) {
            $templateFile = 'module:' . $this->name . '/views/templates/hook/' . 'wishlist-modal.tpl';
			$cacheId = 'mdWishList';
        } elseif (preg_match('/^displayWishlistButton\d*$/', $hookName) || preg_match('/^displayAfterButtonCart\d*$/', $hookName)) {
            $templateFile = 'module:' . $this->name . '/views/templates/hook/' . 'wishlist-btn.tpl';
			$cacheId = 'btnWishList|'.$configuration['smarty']->tpl_vars['product']->value['id_product'].'|'.$configuration['smarty']->tpl_vars['product']->value['id_product_attribute'];
			
			if (!$this->isCached($templateFile, $this->getCacheId($cacheId))) {
				$this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
			}
        }
		
        return $this->fetch($templateFile, $this->getCacheId($cacheId));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }
		return array(
			'id_product_attribute' => $configuration['smarty']->tpl_vars['product']->value['id_product_attribute'],
			'id_product' => $configuration['smarty']->tpl_vars['product']->value['id_product'],
		);
    }

    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['id'])) {
            $sql = "DELETE FROM "._DB_PREFIX_."vec_wishlist_product WHERE id_customer = '".(int)pSQL($customer['id'])."'";
            if (Db::getInstance()->execute($sql)) {
                return json_encode(true);
            }
        }
    }

    public function hookActionExportGDPRData($customer)
    {
        if (!empty($customer['id'])) {
            $sql = "SELECT id_product FROM "._DB_PREFIX_."vec_wishlist_product WHERE id_customer = '".(int)pSQL($customer['id'])."'";
            if ($res = Db::getInstance()->executeS($sql)) {

                $arr = array();
                foreach ($res as $key => $val) {
                    $arr[] = $val['id_product'];
                }
                $productsIds = implode(",",  $arr);

                $sql = 'SELECT p.`id_product` as "Id", p.`reference`, pl.`name`
		        FROM `'._DB_PREFIX_.'product` p
		        '.Shop::addSqlAssociation('product', 'p').'
		        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').')
		        WHERE p.id_product IN ('.$productsIds.')';

                $items = Db::getInstance()->executeS($sql);

                return json_encode($items);
            }
        }
    }
   
}
