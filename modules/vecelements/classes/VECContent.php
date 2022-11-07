<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECContent extends ObjectModel
{
    public $id_employee;
    public $id_product = 0;
    public $title;
    public $hook;
    public $content;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'vec_content',
        'primary' => 'id_vec_content',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'hook' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 64],
            // Shop fields
            'position' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128],
            // Lang fields
            'content' => ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'],
        ],
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);

        // Insert missing vec_content_lang row if new language was added
        if (!$this->id && $id && $id_lang && $id_shop && Shop::getShop($id_shop) && Language::getLanguage($id_lang) && self::getHookById($id) !== false) {
            $res = Db::getInstance()->insert('vec_content_lang', [
                'id_vec_content' => (int) $id,
                'id_lang' => (int) $id_lang,
                'id_shop' => (int) $id_shop,
                'content' => '',
            ]);
            empty($res) or parent::__construct($id, $id_lang, $id_shop, $translator);
        }
    }

    public function add($auto_date = true, $null_values = false)
    {
        $ctx = Context::getContext();
        $this->id_employee = $ctx->employee->id;

        $res = parent::add($auto_date, $null_values);

        if ($res && $this->hook && !empty($ctx->controller->module)) {
            $ctx->controller->module->registerHook($this->hook, Shop::getContextListShopID());
        }
        return $res;
    }

    public function update($null_values = false)
    {
        if ('0000-00-00 00:00:00' === $this->date_add) {
            $this->date_add = date('Y-m-d H:i:s');
        }
        $before = new self($this->id);

        if ($res = parent::update($null_values)) {
            $module = Context::getContext()->controller->module;
            // handle hook changes
            if ($before->hook && !method_exists($module, 'hook' . $before->hook) && !self::hasHook($before->hook)) {
                $module->unregisterHook($before->hook, Shop::getContextListShopID());
            }
            empty($this->hook) or $module->registerHook($this->hook, Shop::getContextListShopID());
        }
        return $res;
    }

    public function delete()
    {
        $res = parent::delete();

        if ($res && $this->hook != 'displayFooterProduct') {
            $module = Context::getContext()->controller->module;
            $shops = Shop::getContextListShopID();

            // unregister hook if needed
            if (!method_exists($module, 'hook' . $this->hook) && !self::hasHook($this->hook)) {
                $module->unregisterHook($this->hook, $shops);
            }
        }
        return $res;
    }

    public static function displayFieldName($field, $class = __CLASS__, $htmlentities = true, Context $ctx = null)
    {
        return 'hook' == $field ? 'Position' : parent::displayFieldName($field, $class, $htmlentities, $ctx);
    }

    public static function hasHook($hook)
    {
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'vec_content';
        $hook = $db->escape($hook);

        return $db->getValue("SELECT 1 FROM $table WHERE hook LIKE '$hook'");
    }

    public static function getHookById($id)
    {
        $table = _DB_PREFIX_ . 'vec_content';

        return Db::getInstance()->getValue(
            "SELECT hook FROM $table WHERE id_vec_content = " . (int) $id
        );
    }

    public static function getIdsByHook($hook, $id_lang, $id_shop, $id_product = 0, $preview = false)
    {
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'vec_content';
        $hook = $db->escape($hook);
        $id_lang = (int) $id_lang;
        $id_shop = (int) $id_shop;
        $id_product = (int) $id_product;
        $id_preview = isset($preview->id, $preview->id_type) && VEC\UId::CONTENT == $preview->id_type
            ? (int) $preview->id
            : 0
        ;
        $res = $db->executeS(
            "SELECT a.id_vec_content as id FROM $table a
            LEFT JOIN {$table}_lang AS b ON a.id_vec_content = b.id_vec_content
            LEFT JOIN {$table}_shop sa ON sa.id_vec_content = a.id_vec_content AND sa.id_shop = b.id_shop
            WHERE b.id_lang = $id_lang AND sa.id_shop = $id_shop AND a.hook LIKE '$hook' " .
            ($id_product ? "AND (a.id_product = 0 OR a.id_product = $id_product) " : "") .
            ($id_preview ? "AND (a.active = 1 OR a.id_vec_content = $id_preview) " : "AND a.active = 1 ") .
            "ORDER BY a.id_product DESC"
        );

        return $res ? $res : [];
    }

    public static function getFooterProductId($id_product)
    {
        if (!$id_product = (int) $id_product) {
            return 0;
        }
        $table = _DB_PREFIX_ . 'vec_content';

        return (int) Db::getInstance()->getValue(
            "SELECT id_vec_content FROM $table WHERE id_product = $id_product AND hook = 'displayFooterProduct'"
        );
    }

    public static function getMaintenanceId()
    {
        $table = _DB_PREFIX_ . 'vec_content';

        return (int) Db::getInstance()->getValue(
            "SELECT id_vec_content FROM $table WHERE hook LIKE 'displayMaintenance' ORDER BY active DESC"
        );
    }
}
