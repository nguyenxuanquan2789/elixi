<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class VecpetabProvider {

	/**
	 * GetProductsById gets the products by id fetched from database.
	 *
	 * @param mixed $ids products ids.
	 */
	public function getProductsById( $ids ) {

		$context = Context::getContext();
		$id_lang = (int) Context::getContext()->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( empty( $ids ) ) {
			return false;
		} elseif ( $ids == '' ) {
			return false;
		}
		$limit = Tools::getValue( 'limit' ) ? pSQL( Tools::getValue( 'limit' ) ) : 60;

		$sqlids = '';
		if ( is_string( $ids ) ) {
			if ( $ids == 'random' ) {
				$sqlids = '';
				$limit  = 1;
			} else {
				$ids = explode( '-', $ids );
				unset( $ids[ count( $ids ) - 1 ] );
				foreach ( $ids as $k => $id ) {
					if ( $k > 0 ) {
						$sqlids .= ',';
					}
					$sqlids .= $id;
				}
			}
		} else {
			foreach ( $ids as $k => $id ) {
				if ( $k > 0 ) {
					$sqlids .= ',';
				}
				$sqlids .= $id['id_specify_page'];
			}
		}

		$sql = '';

		if ( $sqlids == '' ) {
			$sql = 'SELECT p.`id_product`, pl.`name`
			FROM `' . _DB_PREFIX_ . 'product` p
			' . Shop::addSqlAssociation( 'product', 'p' ) . '
			LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang( 'pl' ) . ')
			WHERE pl.`id_lang` = ' . (int) $id_lang . '
			' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			'ORDER BY pl.`name` LIMIT ' . $limit;
		} else {
			$sql = 'SELECT p.`id_product`, pl.`name`
			FROM `' . _DB_PREFIX_ . 'product` p
			' . Shop::addSqlAssociation( 'product', 'p' ) . '
			LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang( 'pl' ) . ')
			WHERE pl.`id_lang` = ' . (int) $id_lang . '
			' . ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' ) .
			' AND p.`id_product` IN(' . $sqlids . ')' .
			'ORDER BY pl.`name` LIMIT ' . $limit;
		}

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
		$rslt = array();
		foreach ( $rs as $i => $r ) {
			$rslt[ $i ]['id_product'] = $r['id_product'];
			$rslt[ $i ]['name']       = $r['name'];
			$i++;
		}
		return $rslt;
	}

	/**
	 * GetCatsByIdgets the categories by id fetched from database.
	 *
	 * @param mixed $ids categories ids.
	 */
	public function getCatsById( $ids ) {
		$context = Context::getContext();
		$id_lang = (int) Context::getContext()->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( empty( $ids ) ) {
			return false;
		}

		$sqlids = '';
		if ( is_string( $ids ) ) {
			$ids = explode( '-', $ids );
			unset( $ids[ count( $ids ) - 1 ] );
			foreach ( $ids as $k => $id ) {
				if ( $k > 0 ) {
					$sqlids .= ',';
				}
				$sqlids .= $id;
			}
		} else {
			foreach ( $ids as $k => $id ) {
				if ( $k > 0 ) {
					$sqlids .= ',';
				}
				$sqlids .= $id['id_specify_page'];
			}
		}
		$limit = Tools::getValue( 'limit' ) ? pSQL( Tools::getValue( 'limit' ) ) : 60;

		$sql = 'SELECT c.`id_category`, cl.`name`
                FROM `' . _DB_PREFIX_ . 'category` c
                ' . Shop::addSqlAssociation( 'category', 'c' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` ' . Shop::addSqlRestrictionOnLang( 'cl' ) . ')
                WHERE cl.`id_lang` = ' . (int) $id_lang . '
                ' . ( $front ? ' AND c.`active` = 1' : '' ) .
		' AND c.`id_category` IN(' . $sqlids . ')' .
		'ORDER BY cl.`name` LIMIT ' . $limit;

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
		$rslt = array();
		foreach ( $rs as $i => $r ) {
			$rslt[ $i ]['id_category'] = $r['id_category'];
			$rslt[ $i ]['name']        = $r['name'];
			$i++;
		}

		return $rslt;
	}

	public static function getProductsByCatsId( $category_id ) {
		$context = Context::getContext();
		$id_lang = $context->language->id;
		$id_shop = $context->shop->id;
		$active  = true;
		$front   = true;
		$sql     = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
                                    pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
                                    il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
                                    DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
                                    INTERVAL ' . ( Validate::isUnsignedInt( Configuration::get( 'PS_NB_DAYS_NEW_PRODUCT' ) ) ? Configuration::get( 'PS_NB_DAYS_NEW_PRODUCT' ) : 20 ) . '
                        DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                    ON p.`id_product` = cp.`id_product`
                ' . Shop::addSqlAssociation( 'product', 'p' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa
                ON (p.`id_product` = pa.`id_product`)
                ' . Shop::addSqlAssociation( 'product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1' ) . '
                ' . Product::sqlStock( 'p', 'product_attribute_shop', false, $context->shop ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
                    ON (product_shop.`id_category_default` = cl.`id_category`
                    AND cl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang( 'cl' ) . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
                    ON (p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang( 'pl' ) . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image` i
                    ON (i.`id_product` = p.`id_product`)' .
		Shop::addSqlAssociation( 'image', 'i', false, 'image_shop.cover=1' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image`
                    AND il.`id_lang` = ' . (int) $id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                    ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = ' . (int) $context->shop->id . '
                    AND cp.`id_category` = ' . (int) $category_id
		. ( $active ? ' AND product_shop.`active` = 1' : '' )
		. ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' )
		. ' GROUP BY product_shop.id_product'
		. ' LIMIT 5';

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
		$rslt = array();
		foreach ( $rs as $i => $r ) {
			$rslt[ $i ]['id_product'] = $r['id_product'];
			$rslt[ $i ]['name']       = $r['name'];
			$i++;
		}
		return $rslt;

	}
	public function getManusById( $ids ) {
		$context = Context::getContext();
		$id_lang = (int) Context::getContext()->language->id;
		$front   = true;
		if ( ! in_array( $context->controller->controller_type, array( 'front', 'modulefront' ) ) ) {
			$front = false;
		}

		if ( empty( $ids ) ) {
			return false;
		}

		$sqlids = '';
		if ( is_string( $ids ) ) {
			$ids = explode( '-', $ids );
			unset( $ids[ count( $ids ) - 1 ] );
			foreach ( $ids as $k => $id ) {
				if ( $k > 0 ) {
					$sqlids .= ',';
				}
				$sqlids .= $id;
			}
		} else {
			foreach ( $ids as $k => $id ) {
				if ( $k > 0 ) {
					$sqlids .= ',';
				}
				$sqlids .= $id['id_specify_page'];
			}
		}
		$limit = Tools::getValue( 'limit' ) ? pSQL( Tools::getValue( 'limit' ) ) : 60;

		$sql = 'SELECT m.`id_manufacturer`, m.`name`
                FROM `' . _DB_PREFIX_ . 'manufacturer` m
                ' . Shop::addSqlAssociation( 'manufacturer', 'm' ) . '
                ' . ( $front ? ' AND m.`active` = 1' : '' ) .
		' AND m.`id_manufacturer` IN(' . $sqlids . ')' .
		'ORDER BY m.`id_manufacturer` LIMIT ' . $limit;

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
		$rslt = array();
		foreach ( $rs as $i => $r ) {
			$rslt[ $i ]['id_manufacturer'] = $r['id_manufacturer'];
			$rslt[ $i ]['name']        = $r['name'];
			$i++;
		}

		return $rslt;
	}
	public static function getProductsByManusId( $manu_id ) {
		$context = Context::getContext();
		$id_lang = $context->language->id;
		$id_shop = $context->shop->id;
		$active  = true;
		$front   = true;
		$sql     = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
                                    pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
                                    il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
                                    DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
                                    INTERVAL ' . ( Validate::isUnsignedInt( Configuration::get( 'PS_NB_DAYS_NEW_PRODUCT' ) ) ? Configuration::get( 'PS_NB_DAYS_NEW_PRODUCT' ) : 20 ) . '
                        DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                LEFT JOIN `' . _DB_PREFIX_ . 'product` p
                    ON p.`id_product` = cp.`id_product`
                ' . Shop::addSqlAssociation( 'product', 'p' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa
                ON (p.`id_product` = pa.`id_product`)
                ' . Shop::addSqlAssociation( 'product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1' ) . '
                ' . Product::sqlStock( 'p', 'product_attribute_shop', false, $context->shop ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'image` i
                    ON (i.`id_product` = p.`id_product`)' .
		Shop::addSqlAssociation( 'image', 'i', false, 'image_shop.cover=1' ) . '
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il
                    ON (image_shop.`id_image` = il.`id_image`
                    AND il.`id_lang` = ' . (int) $id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m
                    ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE product_shop.`id_shop` = ' . (int) $context->shop->id . '
                    AND m.`id_manufacturer` = ' . (int) $manu_id
		. ( $active ? ' AND product_shop.`active` = 1' : '' )
		. ( $front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '' )
		. ' GROUP BY product_shop.id_product'
		. ' LIMIT 5';

		$rs   = Db::getInstance( _PS_USE_SQL_SLAVE_ )->executeS( $sql );
		$rslt = array();
		foreach ( $rs as $i => $r ) {
			$rslt[ $i ]['id_product'] = $r['id_product'];
			$rslt[ $i ]['name']       = $r['name'];
			$i++;
		}
		return $rslt;
	}
}
