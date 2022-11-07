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

class VecWishlistProduct extends ObjectModel
{
    public $id_vec_wishlist_product;
    public $id_product;
    public $id_customer;
    public $id_product_attribute;
    public $id_shop;

    public static $definition = array(
        'table' => 'vec_wishlist_product',
        'primary' => 'id_vec_wishlist_product',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_product_attribute' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
        ),
    );
	
    public static function getIdWishlistProduct($idCustomer, $idProduct, $idProductAttribute, $idShop = null)
    {		
        if (!$idShop) {
            $idShop = Context::getContext()->shop->id;
        }

		$sql = 'SELECT id_vec_wishlist_product FROM `' . _DB_PREFIX_ . 'vec_wishlist_product` WHERE `id_customer` = ' . (int)$idCustomer . ' AND `id_product` = ' . (int)$idProduct . ' AND `id_product_attribute` = ' . (int)$idProductAttribute . ' AND `id_shop` = ' . (int)$idShop;
		//getValue   getRow    
		return Db::getInstance()->getValue($sql);
    }

    public static function getWishlistProductsIds($idCustomer, $idShop = null)
    {		
        if (!$idShop) {
            $idShop = Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'vec_wishlist_product` WHERE `id_customer` = ' . (int)$idCustomer . ' AND `id_shop` = ' . (int)$idShop;
		
		$results = Db::getInstance()->executeS($sql);
		
		$ids = array();
		
		foreach ($results as $row) {
			$ids[] = $row['id_product'].'-'.$row['id_product_attribute'];
		}
		
        return $ids;
    }

    public static function getWishlistProducts($idCustomer, $idShop = null)
    {
        if (!$idShop) {
            $idShop = Context::getContext()->shop->id;
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'vec_wishlist_product` WHERE `id_customer` = ' . (int)$idCustomer . ' AND `id_shop` = ' . (int)$idShop;
		
        return Db::getInstance()->executeS($sql);		
    }
}
