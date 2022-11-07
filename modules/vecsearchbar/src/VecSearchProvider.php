<?php
/**
 * package   Vec Search
 *
 * @version 2.0.0
 * @author    Posthemes Website: themevec.com
 * @copyright (c) 2021 YouTech Company. All Rights Reserved.
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace ThemeVec\Module\Adapter\Search;

use Hook;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchProviderInterface;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchResult;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrderFactory;
use Search;
use Symfony\Component\Translation\TranslatorInterface;
use Tools;

/**
 * Class responsible of retrieving products in Search page of Front Office.
 *
 * @see SearchController
 */
class VecSearchProvider implements ProductSearchProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SortOrderFactory
     */
    private $sortOrderFactory;
	
	private $searchCore;

    public function __construct(
        TranslatorInterface $translator,
		$searchCore
    ) {
        $this->translator = $translator;
		$this->searchCore = $searchCore;
        $this->sortOrderFactory = new SortOrderFactory($this->translator);
    }

    /**
     * {@inheritdoc}
     */
    public function runQuery(
        ProductSearchContext $context,
        ProductSearchQuery $query
    ) {
        $products = [];
        $count = 0;

		$queryString = Tools::replaceAccentedChars(urldecode($query->getSearchString()));
		$result = $this->searchCore->find(
			$context->getIdLang(),
			$queryString,
			$query->getPage(),
			$query->getResultsPerPage(),
			$query->getSortOrder()->toLegacyOrderBy(),
			$query->getSortOrder()->toLegacyOrderWay(),
			false, 
			false,
			null
		);

		$products = $result['result'];
		$count = $result['total'];

		Hook::exec('actionSearch', [
			'searched_query' => $queryString,
			'total' => $count,

			// deprecated since 1.7.x
			'expr' => $queryString,
		]);
		
        $result = new ProductSearchResult();

        if (!empty($products)) {
            $result
                ->setProducts($products)
                ->setTotalProductsCount($count);

            $result->setAvailableSortOrders(
                $this->sortOrderFactory->getDefaultSortOrders()
            );
        }

        return $result;
    }
}
