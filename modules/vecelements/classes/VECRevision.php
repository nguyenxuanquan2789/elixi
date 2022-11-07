<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECRevision extends ObjectModel
{
    public $parent;
    public $id_employee;
    public $title;
    public $content;
    public $active;
    public $date_upd;

    public static $definition = [
        'table' => 'vec_revision',
        'primary' => 'id_vec_revision',
        'fields' => [
            'parent' => ['type' => self::TYPE_STRING, 'validate' => 'isIp2Long', 'required' => true],
            'id_employee' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 255],
            'type' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'size' => 64],
            'content' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'size' => 3999999999999],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isBool'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];
}
