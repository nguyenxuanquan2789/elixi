<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class VecpetabAjax {

	/**
	 * GetProductsByName gets the products by name written in the input.
	 */
	public function getProductsByName() {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		$q     = Tools::getValue( 'q' );
		$exid  = Tools::getValue( 'excludeIds' );
		$limit = Tools::getValue( 'limit' );
		$exSql = '';
		if ( ! empty( $exid ) ) {
			$exid   = substr( $exid, strlen( $exid ) - 1 ) == ',' ? substr( $exid, 0, strrpos( $exid, ',' ) ) : $exid;
			$exSql .= ' AND p.`id_product` NOT IN(';
			$exSql .= $exid;
			$exSql .= ') ';
		}

		$sql = 'SELECT p.`id_product`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation( 'product', 'p' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
		' AND pl.`name` LIKE "%' . pSQL( $q ) . '%" ' . $exSql .
		'ORDER BY pl.`name` LIMIT ' . $limit;

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		$rslt = '';
		foreach ( $rs as $r ) {
			$rslt .= $r['name'] . '&nbsp;|';
			$rslt .= $r['id_product'] . "\n";
		}
		return $rslt;
	}

	/**
	 * GetCatsByName gets the categories by name written in the input.
	 */
	public function getCatsByName() {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$limit   = Tools::getValue( 'limit' );
		$q       = Tools::getValue( 'q' );
		$exid    = Tools::getValue( 'excludeIds' );

		$exSql = '';
		if ( ! empty( $exid ) ) {
			$exid   = substr( $exid, strlen( $exid ) - 1 ) == ',' ? substr( $exid, 0, strrpos( $exid, ',' ) ) : $exid;
			$exSql .= ' AND p.`id_category` NOT IN(';
			$exSql .= $exid;
			$exSql .= ') ';
		}

		$sql = 'SELECT p.`id_category`, pl.`name`
                FROM `' . _DB_PREFIX_ . 'category` p
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` pl ON (p.`id_category` = pl.`id_category` ' . Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                WHERE pl.`id_lang` = ' . (int) $id_lang . '
                 AND pl.`name` LIKE "%' . pSQL( $q ) . '%" ' . $exSql .
		'ORDER BY pl.`name` ASC LIMIT ' . $limit;

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		$rslt = '';
		foreach ( $rs as $r ) {
			$rslt .= $r['name'] . '&nbsp;|';
			$rslt .= $r['id_category'] . "\n";
		}
		return $rslt;
	}
	/**
	 * GetManusByName gets the categories by name written in the input.
	 */
	public function getManusByName() {
		$context = Context::getContext();
		$id_lang = (int) $context->language->id;
		$limit   = Tools::getValue( 'limit' );
		$q       = Tools::getValue( 'q' );
		$exid    = Tools::getValue( 'excludeIds' );

		$exSql = '';
		if ( ! empty( $exid ) ) {
			$exid   = substr( $exid, strlen( $exid ) - 1 ) == ',' ? substr( $exid, 0, strrpos( $exid, ',' ) ) : $exid;
			$exSql .= ' AND m.`id_manufacturer` NOT IN(';
			$exSql .= $exid;
			$exSql .= ') ';
		}

		$sql = 'SELECT m.`id_manufacturer`, m.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` m
                WHERE  m.`name` LIKE "%' . pSQL( $q ) . '%" ' . $exSql .
		'ORDER BY m.`name` ASC LIMIT ' . $limit;

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql, true, false );
		$rslt = '';
		foreach ( $rs as $r ) {
			$rslt .= $r['name'] . '&nbsp;|';
			$rslt .= $r['id_manufacturer'] . "\n";
		}
		return $rslt;
	}

}
