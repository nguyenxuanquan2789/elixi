<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECTemplate extends ObjectModel
{
    public $id_employee;
    public $title;
    public $type;
    public $content;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'vec_template',
        'primary' => 'id_vec_template',
        'fields' => [
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 128],
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 64],
            'content' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'position' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public function add($auto_date = true, $null_values = false)
    {
        $this->id_employee = Context::getContext()->employee->id;

        return parent::add($auto_date, $null_values);
    }
}
