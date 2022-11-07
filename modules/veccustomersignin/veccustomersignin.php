<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VecCustomerSignIn extends Module implements WidgetInterface
{

    private $templateFile;

    public function __construct()
    {
        $this->name = 'veccustomersignin';
        $this->author = 'ThemeVec';
        $this->version = '1.0.4';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Vec - Customer "Sign in"', [], 'Modules.Customersignin.Admin');
        $this->description = $this->getTranslator()->trans('Make your customers feel at home on your store, invite them to sign in!', [], 'Modules.Customersignin.Admin');
        $this->ps_versions_compliancy = ['min' => '1.7.6.0', 'max' => _PS_VERSION_];

        $this->templateFile = 'module:veccustomersignin/veccustomersignin.tpl';
    }

    public function install()
    {
        Configuration::updateValue('VEC_CUSTOMER_SIGNIN_AJAX', 1);
        Configuration::updateValue('VEC_CUSTOMER_SIGNIN_REDIRECT', 1);
        return parent::install() 
        && $this->registerHook('header')
        && $this->registerHook('displayBeforeBodyClosingTag');
    }

    public function uninstall()
    {
        // Configuration
        Configuration::deleteByName('VEC_CUSTOMER_SIGNIN_AJAX');
        Configuration::deleteByName('VEC_CUSTOMER_SIGNIN_REDIRECT');

        return parent::uninstall(); 
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $logged = $this->context->customer->isLogged();

        if ($logged) {
            $customerName = $this->getTranslator()->trans(
                '%firstname% %lastname%',
                [
                    '%firstname%' => $this->context->customer->firstname,
                    '%lastname%' => $this->context->customer->lastname,
                ],
                'Modules.Customersignin.Admin'
            );
        } else {
            $customerName = '';
        }

        return [
            'logged' => $logged,
            'customerName' => $customerName,
            'icon' => isset($configuration['icon']) ? $configuration['icon'] : '',
        ];
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch($this->templateFile);
    }

    public function hookHeader()
    {
        if(! Configuration::get('VEC_CUSTOMER_SIGNIN_AJAX')) return;

        $this->context->controller->registerJavascript('modules-veccustomersignin', 'modules/' . $this->name . '/js/customersignin.js', ['position' => 'bottom', 'priority' => 150]);
        Media::addJsDef(array(
            'csi_ajax_url' => $this->context->link->getModuleLink('veccustomersignin', 'customersignin'),
            'csi_redirect' => Configuration::get('VEC_CUSTOMER_SIGNIN_REDIRECT'),
            'csi_myaccount_url' => $this->context->link->getPageLink('my-account', true),
        ));
    }
    public function hookDisplayBeforeBodyClosingTag(){
        if(! Configuration::get('VEC_CUSTOMER_SIGNIN_AJAX')) return;

        $output = $this->fetch('module:veccustomersignin/modal.tpl');

        return $output;
    }
    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitVecCustomerSignin')) {
            Configuration::updateValue('VEC_CUSTOMER_SIGNIN_AJAX', Tools::getValue('VEC_CUSTOMER_SIGNIN_AJAX'));
            Configuration::updateValue('VEC_CUSTOMER_SIGNIN_REDIRECT', Tools::getValue('VEC_CUSTOMER_SIGNIN_REDIRECT'));
        }

        return $output . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ajax login'),
                        'name' => 'VEC_CUSTOMER_SIGNIN_AJAX',
                        'is_bool' => true,
                        'desc' => $this->l('Activate Ajax mode for login.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Redirect after login'),
                        'name' => 'VEC_CUSTOMER_SIGNIN_REDIRECT',
                        'options' => array (
                            'query' => array(
                                1 => array('id' =>1 , 'name' => 'None'),
                                2 => array('id' =>2 , 'name' => 'My account page'),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitVecCustomerSignin';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab
        . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'VEC_CUSTOMER_SIGNIN_AJAX' => Tools::getValue('VEC_CUSTOMER_SIGNIN_AJAX', Configuration::get('VEC_CUSTOMER_SIGNIN_AJAX')),
            'VEC_CUSTOMER_SIGNIN_REDIRECT' => Tools::getValue('VEC_CUSTOMER_SIGNIN_REDIRECT', Configuration::get('VEC_CUSTOMER_SIGNIN_REDIRECT')),
        );
    }
}
