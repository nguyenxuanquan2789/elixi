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

class VecWishlistActionsModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        if (Tools::getValue('process') == 'add') {
            $this->processAdd();
        } elseif (Tools::getValue('process') == 'remove') {
            $this->processRemove();
        } elseif (Tools::getValue('process') == 'removeAll') {
            $this->processRemoveAll();
        }
    }

    public function processAdd()
    {
        header('Content-Type: application/json');

        if (!$this->context->customer->isLogged()) {
            $this->ajaxDie(json_encode(array(
                'is_logged' => false
            )));
        }
		
        $productsIds = VecWishlistProduct::getWishlistProductsIds((int)$this->context->customer->id);
		
        $idProduct = (int)Tools::getValue('idProduct');
        $idProductAttribute = (int)Tools::getValue('idProductAttribute');
        $idCustomer = (int)$this->context->customer->id;
        $idShop = (int)$this->context->shop->id;
        $idLang = (int)$this->context->language->id;

        $product = new Product($idProduct, false, $idLang, $idShop, $this->context);

        if (!Validate::isLoadedObject($product) || VecWishlistProduct::getIdWishlistProduct($idCustomer, (int)$product->id, $idProductAttribute)) {
            $this->ajaxDie(json_encode(array(
                'is_logged' => true,
                'productsIds' => $productsIds
            )));
        } else {
            $obj = new VecWishlistProduct();
            $obj->id_product = $idProduct;
            $obj->id_customer = $idCustomer;
            $obj->id_product_attribute = $idProductAttribute;
            $obj->id_shop = $idShop;

            if ($obj->add()) {
				$productsIds = VecWishlistProduct::getWishlistProductsIds((int)$this->context->customer->id);
                $this->ajaxDie(json_encode(array(
                    'is_logged' => true,
                	'productsIds' => $productsIds
                )));
            }
        }
    }
	
    public function processRemove()
    {
        header('Content-Type: application/json');

        if (!$this->context->customer->isLogged()) {
            $this->ajaxDie(json_encode(array(
                'is_logged' => false,
            )));
        }
		
        $idProduct = (int)Tools::getValue('idProduct');
        $idProductAttribute = (int)Tools::getValue('idProductAttribute');
        $idCustomer = (int)$this->context->customer->id;
		
		$id_wishlist_product = VecWishlistProduct::getIdWishlistProduct($idCustomer, $idProduct, $idProductAttribute);
		
        $wishlistProduct = new VecWishlistProduct((int)$id_wishlist_product);
        $wishlistProduct->delete();
		
        $productsIds = VecWishlistProduct::getWishlistProductsIds($idCustomer);
		
        $this->ajaxDie(json_encode(array(
            'is_logged' => true,
            'productsIds' => $productsIds
        )));
    }

    public function processRemoveAll()
    {
        header('Content-Type: application/json');

        if (!$this->context->customer->isLogged()) {
            $this->ajaxDie(json_encode(array(
                'is_logged' => false,
            )));
        }

        $idCustomer = (int)$this->context->customer->id;

        $wlProducts = VecWishlistProduct::getWishlistProducts((int)$idCustomer);

		foreach($wlProducts as $item){
            $idProduct = (int)$item['id_product'];
            $idProductAttribute = (int)$item['id_product_attribute'];
            
            $id_wishlist_product = VecWishlistProduct::getIdWishlistProduct($idCustomer, $idProduct, $idProductAttribute);
            
            $wishlistProduct = new VecWishlistProduct((int)$id_wishlist_product);
            $wishlistProduct->delete();
		}

        $productsIds = VecWishlistProduct::getWishlistProductsIds($idCustomer);
		
		$this->ajaxDie(json_encode(array(
            'is_logged' => true,
			'productsIds' => $productsIds
		)));
    }
}
