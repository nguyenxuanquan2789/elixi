<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

abstract class WidgetCategoryBase extends WidgetBase
{
    protected function registerCategoryTreeSection(array $args = [])
    {
        $this->startControlsSection(
            'section_category_tree',
            [
                'label' => __('Category Tree'),
            ] + $args
        );

        $this->addControl(
            'root_category',
            [
                'label' => __('Category Root'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('Home Category'),
                    '1' => __('Current Category'),
                    '2' => __('Parent Category'),
                    '3' => __('Current Category') . ' / ' . __('Parent Category'),
                ],
            ]
        );

        $this->addControl(
            'max_depth',
            [
                'label' => __('Maximum Depth'),
                'type' => ControlsManager::NUMBER,
                'min' => 0,
                'default' => 4,
            ]
        );

        $this->addControl(
            'sort',
            [
                'label' => __('Sort'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('By Position'),
                    '1' => __('By Name'),
                ],
            ]
        );

        $this->addControl(
            'sort_way',
            [
                'label' => __('Sort Order'),
                'type' => ControlsManager::SELECT,
                'default' => '0',
                'options' => [
                    '0' => __('Ascending'),
                    '1' => __('Descending'),
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function setLastVisitedCategory()
    {
        static $isset;

        if ($isset) {
            return;
        }
        $isset = true;

        if (method_exists($this->context->controller, 'getCategory') && $category = $this->context->controller->getCategory()) {
            $this->context->cookie->last_visited_category = $category->id;
        } elseif (method_exists($this->context->controller, 'getProduct') && $product = $this->context->controller->getProduct()) {
            if (!isset($this->context->cookie->last_visited_category) ||
                !\Product::idIsOnCategoryId($product->id, [['id_category' => $this->context->cookie->last_visited_category]]) ||
                !\Category::inShopStatic($this->context->cookie->last_visited_category, $this->context->shop)
            ) {
                $this->context->cookie->last_visited_category = (int) $product->id_category_default;
            }
        }
    }

    protected function getRootCategory($root_category)
    {
        $this->setLastVisitedCategory();

        if ($root_category && isset($this->context->cookie->last_visited_category) && $this->context->cookie->last_visited_category) {
            $category = new \Category($this->context->cookie->last_visited_category, $this->context->language->id);

            if ($root_category == 2 && !$category->is_root_category && $category->id_parent ||
                $root_category == 3 && !$category->is_root_category && !$category->getSubCategories($category->id, true)
            ) {
                $category = new \Category($category->id_parent, $this->context->language->id);
            }
        } else {
            $category = new \Category((int) \Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
        }

        return $category;
    }

    protected function getCategoryTree(\Category $category, array &$settings)
    {
        $range = '';
        $maxdepth = $settings['max_depth'];
        if (\Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= ' . (int) $category->nleft . ' AND nright <= ' . (int) $category->nright;
        }

        $resultIds = [];
        $resultParents = [];
        $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT c.id_parent, c.id_category, cl.name, cl.link_rewrite
            FROM `' . _DB_PREFIX_ . 'category` c
            INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = ' . (int) $this->context->language->id . \Shop::addSqlRestrictionOnLang('cl') . ')
            INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = ' . (int) $this->context->shop->id . ')
            WHERE (c.`active` = 1 OR c.`id_category` = ' . (int) \Configuration::get('PS_HOME_CATEGORY') . ')
            AND c.`id_category` != ' . (int) \Configuration::get('PS_ROOT_CATEGORY') . '
            ' . ((int) $maxdepth != 0 ? ' AND `level_depth` <= ' . (int) $maxdepth : '') . '
            ' . $range . '
            AND c.id_category IN (
                SELECT id_category
                FROM `' . _DB_PREFIX_ . 'category_group`
                WHERE `id_group` IN (' . pSQL(implode(', ', \Customer::getGroupsStatic((int) $this->context->customer->id))) . ')
            )
            ORDER BY `level_depth` ASC, ' . ($settings['sort'] ? 'cl.`name`' : 'cs.`position`') . ' ' . ($settings['sort_way'] ? 'DESC' : 'ASC')
        );
        foreach ($result as &$row) {
            $resultParents[$row['id_parent']][] = &$row;
            $resultIds[$row['id_category']] = &$row;
        }

        return $this->getTree($resultParents, $resultIds, $maxdepth, $category->id);
    }

    private function getTree($resultParents, $resultIds, $maxDepth, $id_category, $currentDepth = 0)
    {
        $children = [];

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
        } else {
            $link = $name = '';
        }

        return [
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'children' => &$children,
        ];
    }

    public function renderPlainContent()
    {
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
