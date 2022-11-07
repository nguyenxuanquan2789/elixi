<?php

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VecCompare extends Module implements WidgetInterface
{
    public $configName;

    public function __construct()
    {
        $this->name = 'veccompare';
        $this->version = '1.1.0';
        $this->author = 'ThemeVec';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->tab = 'front_office_features';
        $this->controllers = array('comparator');

        parent::__construct();
        $this->displayName = $this->l('Vec - Compare');
        $this->description = $this->l('Allow customers to compare products');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->defaults = array(
            'productsNb' => 0,
        );
    }

    public function install()
    {
        return (parent::install()
            && $this->setDefaults()
            && $this->registerHook('header')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('displayAfterButtonCart')
            && $this->registerHook('displayProductListFunctionalButtons')
        );
    }

    public function uninstall()
    {
        foreach ($this->defaults as $default => $value) {
            Configuration::deleteByName($this->configName . $default);
        }
        return parent::uninstall();
    }

    public function setDefaults()
    {
        foreach ($this->defaults as $default => $value) {
            Configuration::updateValue($this->configName . $default, $value);
        }
        return true;
    }


    public function hookHeader()
    {
        $this->context->controller->registerStylesheet('modules-poscompate-style', 'modules/'.$this->name.'/views/css/front.css', ['media' => 'all', 'priority' => 150]);
        $this->context->controller->registerJavascript('modules-veccompare-script', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 150]);

        $useSSL = ((isset($this->ssl) && $this->ssl && Configuration::get('PS_SSL_ENABLED')) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';

        $productsIds = $this->context->cookie->vecCompare;
        
        if($productsIds) {
            $productsIds = json_decode($productsIds, true);
        }else{
            $productsIds = array();
        }

        Media::addJsDef(array('veccompare' => [
            'nbProducts' =>  (int) $this->context->cookie->vecCompareNb,
            'idProducts' =>  $productsIds,
            'success_text' => $this->l('Product added to compare.'),
            'add_text' => $this->l('Add to compare'),
            'remove_text' => $this->l('Added to compare'),
            'compare_url' => $this->context->link->getModuleLink('veccompare', 'comparator'),
            'compare_text' => $this->l('View compare products'),
            'baseDir' => $protocol_content.Tools::getHttpHost().__PS_BASE_URI__,
        ]));
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }

        $templateFile = 'compare-top.tpl';
        
        if (preg_match('/^displayProductAdditionalInfo\d*$/', $hookName) || preg_match('/^displayAfterButtonCart\d*$/', $hookName)) { 
            $templateFile = 'btn-product-page.tpl';
            $assign = $this->getWidgetVariables($hookName, $configuration);
            $this->smarty->assign($assign);
        }elseif(preg_match('/^displayProductListFunctionalButtons\d*$/', $hookName)){
            $templateFile = 'btn-product-miniature.tpl';
            $assign = $this->getWidgetVariables($hookName, $configuration);
            $this->smarty->assign($assign);
        }
        
        return $this->fetch('module:' . $this->name . '/views/templates/hook/' . $templateFile);
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        if ($hookName == null && isset($configuration['hook'])) {
            $hookName = $configuration['hook'];
        }
        return array(
            'id_product' => $configuration['smarty']->tpl_vars['product']->value['id_product'],
        );
    }

    public function getFeaturesForComparison($idsArray, $idLang)
    {
        if (!Feature::isFeatureActive()) {
            return false;
        }

        $ids = implode(",", $idsArray);

        if (empty($ids)) {
            return false;
        }

        return Db::getInstance()->executeS('
			SELECT f.*, fl.*
			FROM `'._DB_PREFIX_.'feature` f
			LEFT JOIN `'._DB_PREFIX_.'feature_product` fp
				ON f.`id_feature` = fp.`id_feature`
			LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl
				ON f.`id_feature` = fl.`id_feature`
			WHERE fp.`id_product` IN ('.$ids.')
			AND `id_lang` = '.(int)$idLang.'
			GROUP BY f.`id_feature`
			ORDER BY f.`position` ASC
		');
    }
}
