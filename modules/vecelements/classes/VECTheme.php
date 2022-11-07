<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECTheme extends ObjectModel
{
    public $id_vec_theme;
    public $id_employee;
    public $title;
    public $type;
    public $content;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'vec_theme',
        'primary' => 'id_vec_theme',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 64],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128],
            // Shop fields
            'position' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate'],
            // Lang fields
            'content' => ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'],
        ],
    ];

    public function add($auto_date = true, $null_values = false)
    {
        $this->id_employee = Context::getContext()->employee->id;

        return parent::add($auto_date, $null_values);
    }

    public function update($null_values = false)
    {
        if ('0000-00-00 00:00:00' === $this->date_add) {
            $this->date_add = date('Y-m-d H:i:s');
        }

        return parent::update($null_values);
    }

    public function delete()
    {
        // todo
        return parent::delete();
    }

    public static function getOptions($type, $id_lang, $id_shop)
    {
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'vec_theme';
        $id_lang = (int) $id_lang;
        $id_shop = (int) $id_shop;
        $type = $db->escape($type);
        $res = $db->executeS(
            "SELECT t.id_vec_theme as `value`, CONCAT('#', t.id_vec_theme, ' ', t.title) as `name` FROM $table AS t
            INNER JOIN {$table}_shop as ts ON t.id_vec_theme = ts.id_vec_theme
            INNER JOIN {$table}_lang as tl ON t.id_vec_theme = tl.id_vec_theme AND ts.id_shop = tl.id_shop
            WHERE ts.active = 1 AND ts.id_shop = $id_shop AND tl.id_lang = $id_lang AND t.type = '$type'
            ORDER BY t.title"
        );
        return $res ? $res : [];
    }

    public static function getTypeById($id)
    {
        $table = _DB_PREFIX_ . 'vec_theme';

        return Db::getInstance()->getValue(
            "SELECT type FROM $table WHERE id_vec_theme = " . (int) $id
        );
    }
}
