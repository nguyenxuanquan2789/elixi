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

// use VEC\CoreXDynamicTagsXDynamicCSS as DynamicCSS;
use VEC\CoreXBaseXModule as BaseModule;

class ModulesXCustomCssXModule extends BaseModule
{
    public function getName()
    {
        return 'custom-css';
    }

    /**
     * @param $post_css Post
     * @param $element  ElementBase
     */
    public function addPostCss($post_css, $element)
    {
        // if ($post_css instanceof DynamicCSS) {
        //     return;
        // }
        $element_settings = $element->getSettings();

        if (empty($element_settings['custom_css'])) {
            return;
        }

        $css = trim($element_settings['custom_css']);

        if (empty($css)) {
            return;
        }
        $css = str_replace('selector', $post_css->getElementUniqueSelector($element), $css);

        // Add a css comment
        $css = sprintf('/* Start custom CSS for %s, class: %s */', $element->getName(), $element->getUniqueSelector()) . $css . '/* End custom CSS */';

        $post_css->getStylesheet()->addRawCss($css);
    }

    /**
     * @param $post_css Post
     */
    public function addPageSettingsCss($post_css)
    {
        $document = Plugin::$instance->documents->get($post_css->getPostId());
        $custom_css = $document->getSettings('custom_css');

        $custom_css = trim($custom_css);

        if (empty($custom_css)) {
            return;
        }

        $custom_css = str_replace('selector', $document->getCssWrapperSelector(), $custom_css);

        // Add a css comment
        $custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

        $post_css->getStylesheet()->addRawCss($custom_css);
    }

    public function __construct()
    {
        add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
        add_action('elementor/css-file/post/parse', [$this, 'add_page_settings_css']);
    }
}
