<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or exit;

class VecElementsAjaxModuleFrontController extends ModuleFrontController
{
    protected $content_only = true;

    public function postProcess()
    {
        parent::initContent();
        $this->action = Tools::getValue('action');

        Tools::getValue('submitMessage') && $this->ajaxProcessSubmitMessage();
        Tools::getValue('submitNewsletter') && $this->ajaxProcessSubmitNewsletter();

        method_exists($this, "ajaxProcess{$this->action}") && $this->{"ajaxProcess{$this->action}"}();
    }

    public function ajaxProcessSubmitMessage()
    {
        if ($contact = Module::getInstanceByName('contactform')) {
            $contact->sendMessage();

            $this->ajaxDie([
                'success' => implode(nl2br("\n", false), $this->success),
                'errors' => $this->errors,
            ]);
        }

        $this->ajaxDie([
            'errors' => ['Error: Contact Form module should be enabled!'],
        ]);
    }

    public function ajaxProcessSubmitNewsletter()
    {
        $name = 'ps_emailsubscription';
        $newsletter = Module::getInstanceByName($name);

        if (!$newsletter) {
            $this->ajaxDie([
                'errors' => ["Error: $name module should be enabled!"],
            ]);
        }

        
        $newsletter->newsletterRegistration(${'_POST'}['blockHookName'] = 'displayCE');
        

        $this->ajaxDie([
            'success' => empty($newsletter->valid) ? '' : [$newsletter->valid],
            'errors' => empty($newsletter->error) ? [] : [$newsletter->error],
        ]);
    }

    public function ajaxProcessAddToCartModal()
    {
        $cart = $this->cart_presenter->present($this->context->cart, true);
        $product = null;
        $id_product = (int) Tools::getValue('id_product');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $id_customization = (int) Tools::getValue('id_customization');

        foreach ($cart['products'] as &$p) {
            if ($id_product === (int) $p['id_product'] &&
                $id_product_attribute === (int) $p['id_product_attribute'] &&
                $id_customization === (int) $p['id_customization']
            ) {
                $product = $p;
                break;
            }
        }

        $this->context->smarty->assign([
            'configuration' => $this->getTemplateVarConfiguration(),
            'product' => $product,
            'cart' => $cart,
            'cart_url' => $this->context->link->getPageLink('cart', null, $this->context->language->id, [
                'action' => 'show',
            ], false, null, true),
        ]);

        $this->ajaxDie([
            'modal' => $this->context->smarty->fetch('module:ps_shoppingcart/modal.tpl'),
        ]);
    }

    public function ajaxProcessTabProducts()
    {
        $tab_data = Tools::getValue('tabData');
        $listing = $tab_data['listing'];
        $order_by = $tab_data['order_by'];
        $order_dir = $tab_data['order_dir'];
        $limit = $tab_data['limit'];
        $id_category = $tab_data['category_id'];
        $products = $tab_data['products'];

        $products = $this->module->getProducts($listing, $order_by, $order_dir, $limit, $id_category, $products);
        $this->context->smarty->assign(array(
            'products' => $products,
            'tab_class' => $tab_data['tab_class']
        ));
        $template = _VEC_TEMPLATES_ . 'front/widgets/product-tab.tpl';

        if (!$template){
            $template = $this->module->l('No template found', 'ajax');
        }

        $this->ajaxDie(array(
            'html' => $this->context->smarty->fetch($template)
        ));

    }

    protected function ajaxDie($value = null, $controller = null, $method = null)
    {
        if (null === $controller) {
            $controller = get_class($this);
        }
        if (null === $method) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $method = $bt[1]['function'];
        }
        
        Hook::exec('actionAjaxDie' . $controller . $method . 'Before', ['value' => $value]);
        
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        die(json_encode($value));
    }
}
