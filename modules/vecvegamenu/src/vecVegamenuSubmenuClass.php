<?php 
/**
* 2007-2014 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2014 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class VecVegamenuSubmenuClass extends ObjectModel
{
	public $id_vecvegamenu_item;
	public $submenu_width;
	public $submenu_class;
	public $submenu_bg;
	public $submenu_bg_color;
	public $submenu_bg_image;
	public $submenu_bg_repeat;
	public $submenu_bg_position;

	public static $definition = array(
		'table' => 'vecvegamenu_submenu',
		'primary' => 'id_submenu',
		'fields' => array(
			'id_vecvegamenu_item' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'submenu_width' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'submenu_class' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255),
			'submenu_bg' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'submenu_bg_color' =>	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'submenu_bg_image' =>	array('type' => self::TYPE_STRING ,'validate' => 'isString'),
			'submenu_bg_repeat' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'submenu_bg_position' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
		)
	);

	public	function __construct($id_submenu = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_submenu);
	}

	public function add($autodate = true, $null_values = false)
	{
		$res = parent::add($autodate, $null_values);
		return $res;
	}

	public static function getSubmenuConfig($id_item){
		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT ps.*
			FROM '._DB_PREFIX_.'vecvegamenu_submenu ps
			WHERE ps.`id_vecvegamenu_item` = '.$id_item.'');
	}
}