<?php

if ( ! defined( '_PS_VERSION_' ) ) {
	exit;
}

class Vecpextratab extends ObjectModel {
	/**
	 * Id_vecproductextratabs id of the item.
	 *
	 * @var mixed
	 */
	public $id_vecproductextratabs;

	/**
	 * Active
	 *
	 * @var int
	 */
	public $active = 1;
	/**
	 * Specific_product
	 *
	 * @var mixed
	 */
	public $specific_product;
	/**
	 * Specific_product_catg
	 *
	 * @var mixed
	 */
	public $specific_product_catg;
	/**
	 * Specific_product_manu
	 *
	 * @var mixed
	 */
	public $specific_product_manu;
	/**
	 * Condition
	 *
	 * @var mixed
	 */
	public $condition;

	/**
	 * Position
	 *
	 * @var mixed
	 */
	public $position;
	/**
	 * Title
	 *
	 * @var mixed
	 */
	public $title;
	/**
	 * Content
	 *
	 * @var mixed
	 */
	public $content;

	public static $definition = array(
		'table'     => 'vecproductextratabs',
		'primary'   => 'id_vecproductextratabs',
		'multilang' => true,
		'fields'    => array(
			'condition'          => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
			),
			'specific_product'      => array( 
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
			),
			'specific_product_catg' => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
			),
			'specific_product_manu' => array(
				'type'     => self::TYPE_STRING,
				'validate' => 'isString',
			),
			'position'     => array( 'type' => self::TYPE_INT ),
			'active'       => array(
				'type'     => self::TYPE_BOOL,
				'validate' => 'isBool',
				'required' => true,
			),
			'title'        => array(
				'type'     => self::TYPE_STRING,
				'lang'     => true,
				'validate' => 'isString',
				'required' => true,
			),
			'content'      => array(
				'type'     => self::TYPE_HTML,
				'lang'     => true,
				'validate' => 'isString',
			),
		),
	);


	/**
	 * __construct
	 *
	 * @param  mixed $id      id of the tab.
	 * @param  mixed $id_lang laguage id.
	 * @param  mixed $id_shop id of the shop.
	 * @return void
	 */
	public function __construct( $id = null, $id_lang = null, $id_shop = null ) {
		Shop::addTableAssociation( 'vecproductextratabs', array( 'type' => 'shop' ) );
		parent::__construct( $id, $id_lang, $id_shop );
	}

	/**
	 * Add
	 *
	 * @param mixed $autodate    automatically add the date.
	 * @param mixed $null_values if accept null values.
	 */
	public function add( $autodate = true, $null_values = false ) {

		if ( $this->position <= 0 ) {
			$this->position = self::getHigherPosition() + 1;
		}
		if ( ! parent::add( $autodate, $null_values ) || ! Validate::isLoadedObject( $this ) ) {
			return false;
		}

		return true;
	}

	/**
	 * GetHigherPosition gets the higher position.
	 */
	public static function getHigherPosition() {
		$sql      = 'SELECT MAX(`position`)
                FROM `' . _DB_PREFIX_ . 'vecproductextratabs`';
		$position = DB::getInstance()->getValue( $sql );
		return ( is_numeric( $position ) ) ? $position : -1;
	}

	/**
	 * GetInstance provides the instance of the class.
	 */
	public static function GetInstance() {
		$ins = new Vecpextratab();
		return $ins;
	}

	/**
	 * UpdatePosition updates the osition of the class.
	 *
	 * @param mixed $way      update way.
	 * @param mixed $position postion of the item.
	 */
	public function updatePosition( $way, $position ) {
		if ( ! $res = Db::getInstance()->executeS(
			'
            SELECT `id_vecproductextratabs`, `position`
            FROM `' . _DB_PREFIX_ . 'vecproductextratabs`
            ORDER BY `position` ASC'
		)
		) {
			return false;
		}
		foreach ( $res as $vecproductextratabs ) {
			if ( (int) $vecproductextratabs['id_vecproductextratabs'] == (int) $this->id ) {
				$moved_vecproductextratabs = $vecproductextratabs;
			}
		}
		if ( ! isset( $moved_vecproductextratabs ) || ! isset( $position ) ) {
			return false;
		}
		$query_1 = ' UPDATE `' . _DB_PREFIX_ . 'vecproductextratabs`
        SET `position`= `position` ' . ( $way ? '- 1' : '+ 1' ) . '
        WHERE `position`
        ' . ( $way
		? '> ' . (int) $moved_vecproductextratabs['position'] . ' AND `position` <= ' . (int) $position
		: '< ' . (int) $moved_vecproductextratabs['position'] . ' AND `position` >= ' . (int) $position . '
        ' );
		$query_2 = ' UPDATE `' . _DB_PREFIX_ . 'vecproductextratabs`
        SET `position` = ' . (int) $position . '
        WHERE `id_vecproductextratabs` = ' . (int) $moved_vecproductextratabs['id_vecproductextratabs'];
		return ( Db::getInstance()->execute( $query_1 )
		&& Db::getInstance()->execute( $query_2 ) );
	}


	/**
	 * GetTabContentByProductId gets the tab contents by product id.
	 *
	 * @param mixed $id_product id of the product.
	 */
	public function GetTabContentByProductId( $id_product = 1 ) {
		$reslt       = array();
		$resltcat    = array();
		$resltmanu   = array();
		$id_lang     = (int) Context::getContext()->language->id;
		$id_shop     = (int) Context::getContext()->shop->id;
		$sql         = 'SELECT * FROM `' . _DB_PREFIX_ . 'vecproductextratabs` v 
                INNER JOIN `' . _DB_PREFIX_ . 'vecproductextratabs_lang` vl ON (v.`id_vecproductextratabs` = vl.`id_vecproductextratabs` AND vl.`id_lang` = ' . $id_lang . ')
                INNER JOIN `' . _DB_PREFIX_ . 'vecproductextratabs_shop` vs ON (v.`id_vecproductextratabs` = vs.`id_vecproductextratabs` AND vs.`id_shop` = ' . $id_shop . ')
                WHERE ';
		$sql        .= ' v.`active` = 1 ORDER BY v.`position` ASC';
		$sqlcat      = 'SELECT `id_category` FROM `' . _DB_PREFIX_ . 'category_product` 
                		WHERE `id_product`=' . $id_product;
        $sqlmanu     = 'SELECT `id_manufacturer` FROM `' . _DB_PREFIX_ . 'product` 
                		WHERE `id_product`=' . $id_product;
		$cache_id    = md5( $sql );
		$cachecat_id = md5( $sqlcat );
		$cachemanu_id = md5( $sqlmanu );

		if ( ! Cache::isStored( $cache_id ) ) {
			$resultcats = Db::getInstance()->executeS( $sqlcat );
			if ( isset( $resultcats ) && ! empty( $resultcats ) ) {

				foreach ( $resultcats as $i => $result ) {

					$resltcat[] = $result['id_category'];
				}
			}

			$resultmanus = Db::getInstance()->executeS( $sqlmanu );
			if ( isset( $resultmanus ) && ! empty( $resultmanus ) ) {

				foreach ( $resultmanus as $i => $result ) {

					$resltmanu[] = $result['id_manufacturer'];
				}
			}
		}

		if ( ! Cache::isStored( $cache_id ) ) {
			$results = Db::getInstance()->executeS( $sql );
			if ( isset( $results ) && ! empty( $results ) ) {
				foreach ( $results as $i => $result ) {
					switch ($result['condition']) {
						case '1':
							$reslt[ $i ] = $result;
							break;
						case '2':
							$specific_product_arr = explode( '-', $result['specific_product'] );
							if ( isset( $specific_product_arr ) && ! empty( $specific_product_arr ) ) {
								//unset( $specific_product_arr[ count( $specific_product_arr ) - 1 ] );
								if ( in_array( $id_product, $specific_product_arr ) ) {
									$reslt[ $i ] = $result;
								}
							}
							break;
						case '3':
							$specific_product_catg_arr = explode( '-', $result['specific_product_catg'] );
							unset( $specific_product_catg_arr[ count( $specific_product_catg_arr ) - 1 ] );
							$intersect = array_intersect( $resltcat, $specific_product_catg_arr );
							if ( isset( $intersect ) && ! empty( $intersect ) ) {
								$reslt[ $i ] = $result;
							}
							break;
						case '4':
							$specific_product_manu_arr = explode( '-', $result['specific_product_manu'] );
							unset( $specific_product_manu_arr[ count( $specific_product_manu_arr ) - 1 ] );
							$intersect = array_intersect( $resltmanu, $specific_product_manu_arr );
							if ( isset( $intersect ) && ! empty( $intersect ) ) {
								$reslt[ $i ] = $result;
							}
							break;
						
					}
				}

			}
			$outputs = $this->ContentFilterEngine( $reslt );
			Cache::store( $cache_id, $outputs );
		}
		return Cache::retrieve( $cache_id );
	}

	/**
	 * ContentFilterEngine filters the results.
	 *
	 * @param mixed $results results fetched from database.
	 */
	public function ContentFilterEngine( $results = array() ) {
		$outputs = array();
		if ( isset( $results ) && ! empty( $results ) ) {
			$i = 0;
			foreach ( $results as $vecpetab_values ) {
				foreach ( $vecpetab_values as $vecpetab_key => $vcval ) {
					if ( $vecpetab_key == 'content' ) {
						// $outputs[ $i ]['content'] = $this->vctab_content_filter( $vcval );
						$outputs[ $i ]['content'] = $vcval;
					}
					if ( $vecpetab_key == 'title' ) {
						$outputs[ $i ]['title'] = $vcval;
					}
					if ( $vecpetab_key == 'id_vecproductextratabs' ) {
						$outputs[ $i ]['id_vecproductextratabs'] = $vcval;
					}
					if ( $vecpetab_key == 'condition' ) {
						$outputs[ $i ]['condition'] = $vcval;
					}
				}
				$i++;
			}
		}

		return $outputs;
	}
}
