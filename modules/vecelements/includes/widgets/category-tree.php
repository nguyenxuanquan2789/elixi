<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetCategoryTree extends WidgetCategoryBase
{
    public function getName()
    {
        return 'category-tree';
    }

    public function getTitle()
    {
        return __('Category Tree');
    }

    public function getIcon()
    {
        return 'eicon-toggle';
    }

    public function getCategories()
    {
        return ['premium'];
    }

    protected function _registerControls()
    {
        $this->registerCategoryTreeSection();
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }

        $settings = $this->getSettings();

        $category = $this->getRootCategory($settings['root_category']);

        
        $tpl = 'ps_categorytree/views/templates/hook/ps_categorytree.tpl';
        $theme_tpl = _PS_THEME_DIR_ . 'modules/' . $tpl;
        $override = file_exists($theme_tpl);

        $this->context->smarty->assign([
            'currentCategory' => $category->id,
            'categories' => $this->getCategoryTree($category, $settings),
        ]);
        

        echo $this->context->smarty->fetch($override ? $theme_tpl : _PS_MODULE_DIR_ . $tpl);
    }
}
