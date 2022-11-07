<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

/**
 * Unique Identifier
 */
class UId
{
    const REVISION = 0;
    const TEMPLATE = 1;
    const CONTENT = 2;
    const PRODUCT = 3;
    const CATEGORY = 4;
    const MANUFACTURER = 5;
    const SUPPLIER = 6;
    const CMS = 7;
    const CMS_CATEGORY = 8;
    const THEME = 9;
    const SMARTBLOG_POST = 10;

    public $id;
    public $id_type;
    public $id_lang;
    public $id_shop;

    private static $models = [
        'VECRevision',
        'VECTemplate',
        'VECContent',
        'Product',
        'Category',
        'Manufacturer',
        'Supplier',
        'CMS',
        'CMSCategory',
        'VECTheme',
        'SmartBlogPost',
    ];
    private static $admins = [
        'AdminVECEditor',
        'AdminVECTemplates',
        'AdminVECContent',
        'AdminProducts',
        'AdminCategories',
        'AdminManufacturers',
        'AdminSuppliers',
        'AdminCmsContent',
        'AdminCmsContent',
        'AdminVECHeader',
        'AdminVECHome',
        'AdminVECFooter',
        'AdminBlogPost',
    ];
    private static $modules = [
        self::SMARTBLOG_POST => 'smartblog',
    ];
    private static $shop_ids = [];

    public static $_ID;

    public function __construct($id, $id_type, $id_lang = null, $id_shop = null)
    {
        $this->id = abs((int) $id);
        $this->id_type = abs($id_type % 100);

        if ($this->id_type <= self::TEMPLATE) {
            $this->id_lang = 0;
            $this->id_shop = 0;
        } else {
            is_null($id_lang) && $id_lang = \Context::getContext()->language->id;

            $this->id_lang = abs($id_lang % 100);
            $this->id_shop = $id_shop ? abs($id_shop % 100) : 0;
        }
    }

    public function getModel()
    {
        if (empty(self::$models[$this->id_type])) {
            throw new \RuntimeException('Unknown ObjectModel');
        }
        return self::$models[$this->id_type];
    }

    public function getAdminController()
    {
        if (empty(self::$admins[$this->id_type])) {
            throw new \RuntimeException('Unknown AdminController');
        }
        if ((int) \Tools::getValue('footerProduct')) {
            return self::$admins[self::PRODUCT];
        }
        return self::$admins[$this->id_type];
    }

    public function getModule()
    {
        return isset(self::$modules[$this->id_type]) ? self::$modules[$this->id_type] : '';
    }

    /**
     * Get shop ID list where the object is allowed
     *
     * @param bool $all     Get all or just by shop context
     *
     * @return array
     */
    public function getShopIdList($all = false)
    {
        if ($this->id_type <= self::TEMPLATE) {
            return [0];
        }
        if (isset(self::$shop_ids[$this->id_type][$this->id])) {
            return self::$shop_ids[$this->id_type][$this->id];
        }
        isset(self::$shop_ids[$this->id_type]) or self::$shop_ids[$this->id_type] = [];

        $ids = [];
        $model = $this->getModel();
        $def = &$model::${'definition'};
        $db = \Db::getInstance();
        $table = $db->escape(_DB_PREFIX_ . $def['table'] . '_shop');
        $primary = $db->escape($def['primary']);
        $id = (int) $this->id;
        $ctx_ids = implode(', ', $all ? \Shop::getShops(true, null, true) : \Shop::getContextListShopID());
        $rows = $db->executeS(
            "SELECT id_shop FROM $table WHERE $primary = $id AND id_shop IN ($ctx_ids)"
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $ids[] = $row['id_shop'];
            }
        }
        return self::$shop_ids[$this->id_type][$this->id] = $ids;
    }

    public function getDefaultShopId()
    {
        return ($ids = $this->getShopIdList()) ? $ids[0] : 0;
    }

    /**
     * Get UId list by shop context
     *
     * @param bool $strict  Collect only from allowed shops
     *
     * @return array
     */
    public function getListByShopContext($strict = false)
    {
        if ($this->id_shop || $this->id_type <= self::TEMPLATE) {
            return ["$this"];
        }
        $list = [];
        $ids = $strict ? $this->getShopIdList() : \Shop::getContextListShopID();

        foreach ($ids as $id_shop) {
            $this->id_shop = $id_shop;
            $list[] = "$this";
        }
        $this->id_shop = 0;

        return $list;
    }

    /**
     * Get Language ID list of CE built contents
     *
     * @return array
     */
    public function getBuiltLangIdList()
    {
        $ids = [];

        if (self::TEMPLATE === $this->id_type) {
            $ids[] = 0;
        } elseif (self::CONTENT === $this->id_type || self::THEME === $this->id_type) {
            foreach (\Language::getLanguages(false) as $lang) {
                $ids[] = (int) $lang['id_lang'];
            }
        } else {
            $id_shop = $this->id_shop ? $this->id_shop : $this->getDefaultShopId();
            $uids = self::getBuiltList($this->id, $this->id_type, $id_shop);

            empty($uids[$id_shop]) or $ids = array_keys($uids[$id_shop]);
        }
        return $ids;
    }

    public function toDefault()
    {
        $id_shop = $this->id_shop ? $this->id_shop : $this->getDefaultShopId();

        return sprintf('%d%02d%02d%02d', $this->id, $this->id_type, $this->id_lang, $id_shop);
    }

    public function __toString()
    {
        return sprintf('%d%02d%02d%02d', $this->id, $this->id_type, $this->id_lang, $this->id_shop);
    }

    public static function parse($id)
    {
        if ($id instanceof UId) {
            return $id;
        }
        if (!is_numeric($id) || \Tools::strlen($id) <= 6) {
            return false;
        }
        return new self(
            \Tools::substr($id, 0, -6),
            \Tools::substr($id, -6, 2),
            \Tools::substr($id, -4, 2),
            \Tools::substr($id, -2)
        );
    }

    public static function getTypeId($model)
    {
        return array_search(\Tools::strtolower($model), array_map('strtolower', self::$models));
    }

    /**
     * Get UId list of CE built contents grouped by shop(s)
     *
     * @param int $id
     * @param int $id_type
     * @param int|null $id_shop
     *
     * @return array [
     *     id_shop => [
     *         id_lang => UId,
     *     ],
     * ]
     */
    public static function getBuiltList($id, $id_type, $id_shop = null)
    {
        $uids = [];
        $table = _DB_PREFIX_ . 'vec_meta';
        $shop = null === $id_shop ? '__' : '%02d';
        $__id = sprintf("%d%02d__$shop", $id, $id_type, $id_shop);
        $rows = \Db::getInstance()->executeS(
            "SELECT id FROM $table WHERE id LIKE '$__id' AND name = '_elementor_edit_mode'"
        );
        if ($rows) {
            foreach ($rows as &$row) {
                $uid = self::parse($row['id']);
                isset($uids[$uid->id_shop]) or $uids[$uid->id_shop] = [];
                $uids[$uid->id_shop][$uid->id_lang] = $uid;
            }
        }
        return $uids;
    }
}

function absint($num)
{
    if ($num instanceof UId) {
        return $num;
    }
    $absint = preg_replace('/\D+/', '', $num);

    return $absint ? $absint : 0;
}

function get_user_meta($user_id, $key = '', $single = false)
{
    return get_post_meta($user_id, '_u_' . $key, $single);
}

function update_user_meta($user_id, $key, $value, $prev_value = '')
{
    return update_post_meta($user_id, '_u_' . $key, $value, $prev_value);
}

function get_the_ID()
{
    if ($uid_preview = \VecElements::getPreviewUId()) {
        return $uid_preview;
    }
    if (UId::$_ID) {
        return UId::$_ID;
    }
    $controller = \Context::getContext()->controller;

    if ($controller instanceof \AdminVECEditorController ||
        $controller instanceof \VecElementsPreviewModuleFrontController
    ) {
        $id_key = \Tools::getIsset('editor_post_id') ? 'editor_post_id' : 'template_id';

        return UId::parse(\Tools::getValue('uid', \Tools::getValue($id_key)));
    }
    return false;
}

function get_preview_post_link($post = null, array $args = [], $relative = true)
{
    $uid = uidval($post);
    $ctx = \Context::getContext();
    $id_shop = $uid->id_shop ? $uid->id_shop : $uid->getDefaultShopId();
    $args['id_employee'] = $ctx->employee->id;
    $args['adtoken'] = \Tools::getAdminTokenLite($uid->getAdminController());
    $args['preview_id'] = $uid->toDefault();

    switch ($uid->id_type) {
        case UId::REVISION:
            throw new \RuntimeException('TODO');
        case UId::TEMPLATE:
            $link = $ctx->link->getModuleLink('vecelements', 'preview', [], null, null, null, $relative);
            break;
        case UId::THEME:
            $type = \VECTheme::getTypeById($uid->id);

            if ('page-contact' === $type) {
                $link = $ctx->link->getPageLink('contact', null, $uid->id_lang, null, false, $id_shop, $relative);
            } elseif ('page-not-found' === $type) {
                $link = $ctx->link->getPageLink('pagenotfound', null, $uid->id_lang, null, false, $id_shop, $relative);
            } else {
                $link = $ctx->link->getPageLink('index', null, $uid->id_lang, null, false, $id_shop, $relative);

                \Configuration::get('PS_REWRITING_SETTINGS') && $link = preg_replace('~[^/]+$~', '', $link);
            }
            break;
        case UId::CONTENT:
            $hook = \Tools::strtolower(\VECContent::getHookById($uid->id));

            if (in_array($hook, Helper::$productHooks)) {
                if ($id_product = (int) \Tools::getValue('footerProduct')) {
                    $args['footerProduct'] = $id_product;
                    $prod = new \Product($id_product, false, $uid->id_lang, $id_shop);
                } else {
                    $prods = \Product::getProducts($uid->id_lang, 0, 1, 'date_upd', 'DESC', false, $relative);
                    $prod = new \Product(!empty($prods[0]['id_product']) ? $prods[0]['id_product'] : null, false, $uid->id_lang);
                }
                $prod_attr = empty($prod->cache_default_attribute) ? 0 : $prod->cache_default_attribute;
                empty($prod->active) && empty($args['preview']) && $args['preview'] = 1;

                $link = $ctx->link->getProductLink($prod, null, null, null, $uid->id_lang, $id_shop, $prod_attr, false, $relative);
                break;
            }
            $page = 'index';

            if (stripos($hook, 'shoppingcart') !== false) {
                $page = 'cart';
                $args['action'] = 'show';
            } elseif ('displayleftcolumn' === $hook || 'displayrightcolumn' === $hook) {
                $layout = 'r' != $hook[7] ? 'layout-left-column' : 'layout-right-column';
                $layouts = Helper::getPageLayouts();
                unset($layouts['category']);

                if ($key = array_search($layout, $layouts)) {
                    $page = $key;
                } elseif ($key = array_search('layout-both-columns', $layouts)) {
                    $page = $key;
                }
            } elseif ('displaynotfound' === $hook) {
                $page = 'search';
            } elseif ('displaymaintenance' === $hook) {
                $args['maintenance'] = 1;
            }
            $link = $ctx->link->getPageLink($page, null, $uid->id_lang, null, false, $id_shop, $relative);

            if ('index' === $page && \Configuration::get('PS_REWRITING_SETTINGS')) {
                // Remove rewritten URL if exists
                $link = preg_replace('~[^/]+$~', '', $link);
            }
            if('display404pagebuilder' === $hook){
                $link = $ctx->link->getModuleLink('vecelements', 'preview', [], null, null, null, $relative);
            }
            break;
        case UId::PRODUCT:
            $prod = new \Product($uid->id, false, $uid->id_lang, $id_shop);
            $prod_attr = !empty($prod->cache_default_attribute) ? $prod->cache_default_attribute : 0;
            empty($prod->active) && empty($args['preview']) && $args['preview'] = 1;

            $link = $ctx->link->getProductLink($prod, null, null, null, $uid->id_lang, $id_shop, $prod_attr, false, $relative);
            break;
        case UId::CATEGORY:
            $link = $ctx->link->getCategoryLink($uid->id, null, $uid->id_lang, null, $id_shop, $relative);
            break;
        case UId::CMS:
            $link = $ctx->link->getCmsLink($uid->id, null, null, $uid->id_lang, $id_shop, $relative);
            break;
        case UId::SMARTBLOG_POST:
            $post = new \SmartBlogPost($uid->id, $uid->id_lang);
            $smartblog_link = new \SmartBlogLink();
            $link = $smartblog_link->getSmartBlogPostLink($post, null, null, $uid->id_lang, null, $relative);
            break;
        default:
            $method = "get{$uid->getModel()}Link";

            $link = $ctx->link->$method($uid->id, null, $uid->id_lang, $id_shop, $relative);
            break;
    }
    return explode('#', $link)[0] . (stripos($link, '?') === false ? '?' : '&') . http_build_query($args);
}

function uidval($var, $fallback = -1)
{
    if (null === $var) {
        return get_the_ID();
    }
    if ($var instanceof UId) {
        return $var;
    }
    if ($var instanceof WPPost) {
        return $var->uid;
    }
    if (is_numeric($var)) {
        return UId::parse($var);
    }
    if ($fallback !== -1) {
        return $fallback;
    }
    throw new \RuntimeException('Can not convert to UId');
}

function get_edit_post_link($post_id)
{
    $uid = uidval($post_id);
    $ctx = \Context::getContext();
    $id = $uid->id;
    $model = $uid->getModel();
    $admin = $uid->getAdminController();

    switch ($uid->id_type) {
        case UId::REVISION:
            throw new \RuntimeException('TODO');
        case UId::CONTENT:
            if (\Tools::getIsset('footerProduct')) {
                $id = (int) \Tools::getValue('footerProduct');
                $model = 'Product';
                $admin = 'AdminProducts';
            }
            // Continue default case
        default:
            $def = &$model::${'definition'};
            $args = [
                $def['primary'] => $id,
                "update{$def['table']}" => 1,
            ];
            $link = $ctx->link->getAdminLink($admin, true, $args) . '&' . http_build_query($args);
            break;
    }
    return $link;
}