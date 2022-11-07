<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or die;

use VEC\CoreXDocumentTypesXPost as Post;
use VEC\CoreXBaseXThemeDocument as ThemeDocument;

abstract class CoreXBaseXHeaderFooterBase extends ThemeDocument
{
    public function getCssWrapperSelector()
    {
        return '#' . $this->getName();
    }

    public function getElementUniqueSelector(ElementBase $element)
    {
        return '#' . $this->getName() . ' .elementor-element' . $element->getUniqueSelector();
    }

    protected static function getEditorPanelCategories()
    {
        // Move to top as active.
        $categories = [
            'theme-elements' => [
                'title' => __('Site'),
                'active' => true,
            ],
        ];

        return $categories + parent::getEditorPanelCategories();
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        Post::registerStyleControls($this);

        $this->updateControl(
            'section_page_style',
            [
                'label' => __('Style'),
            ]
        );
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = $this->getName();

        return $config;
    }
}
