<?php
class VecCompareActionsModuleFrontController extends ModuleFrontController
{

    public $id_product;

    public function init()
    {
        parent::init();

        $this->id_product = (int)Tools::getValue('id_product');
    }

    public function postProcess()
    {
        if (Tools::getValue('action') == 'remove') {
            $this->ajaxProcessRemove();
        } elseif (Tools::getValue('action') == 'add') {
            $this->ajaxProcessAdd();
        } elseif (Tools::getValue('action') == 'removeAll') {
            $this->ajaxProcessRemoveAll();
        }
		 elseif (Tools::getValue('action') == 'checkCompare') {
            $this->checkCompare();
        }
    }

    /**
     * Add product to compare.
     */
    public function ajaxProcessAdd()
    {   
        header('Content-Type: application/json');

        $idProduct = (int)Tools::getValue('id');

        $productsIds = $this->context->cookie->vecCompare;
        $productsIds = json_decode($productsIds, true);
        
        if (!$productsIds || !in_array($idProduct, $productsIds)) {
            $productsIds[] = $idProduct;
            $productsIds = json_encode($productsIds, true);

            $this->context->cookie->__set('vecCompare', $productsIds);
            $this->context->cookie->__set('vecCompareNb', (int) $this->context->cookie->vecCompareNb + 1);

            $this->ajaxDie(json_encode(array(
                'success' => true,
                'data' => [
                    'message' => $this->l('Product added to compare'),
                    'type' => 'added'
                ]
            )));
        }
    }

	 /**
     * Add product to compare.
     */
    public function checkCompare()
    {   
        header('Content-Type: application/json');

        $idProduct = (int)Tools::getValue('id');

        $productsIds = $this->context->cookie->vecCompare;
        $productsIds = json_decode($productsIds, true);

        if (isset($productsIds[$idProduct])) {
            
            $this->ajaxDie(json_encode(array(
                'success' => true,
            )));
        }
    }
	
    /**
     * Remove a product from compare.
     */
    public function ajaxProcessRemove()
    {
        header('Content-Type: application/json');

        $idProduct = (int)Tools::getValue('id');
        $productsIds = $this->context->cookie->vecCompare;
        $productsIds = json_decode($productsIds, true);
        if (($key = array_search($idProduct, $productsIds)) !== false) {
            unset($productsIds[$key]);
        }
        $productsIds = json_encode($productsIds, true);
        $this->context->cookie->__set('vecCompare', $productsIds);
        $this->context->cookie->__set('vecCompareNb', (int) $this->context->cookie->vecCompareNb - 1);

        $this->ajaxDie(json_encode(array(
            'success' => true,
            'data' => [
                'message' => $this->l('Product removed'),
                'type' => 'removed'
            ]
        )));
    }

    /**
     * Remove all compare products.
     */
    public function ajaxProcessRemoveAll()
    {
        header('Content-Type: application/json');

        $productsIds = array();
        $productsIds = json_encode($productsIds, true);
        $this->context->cookie->__set('vecCompare', $productsIds);
        $this->context->cookie->__set('vecCompareNb', 0);

        $this->ajaxDie(json_encode(array(
            'success' => true,
            'data' => [
                'message' => $this->l('All products removed'),
                'type' => 'removedAll'
            ]
        )));
    }

    
}
