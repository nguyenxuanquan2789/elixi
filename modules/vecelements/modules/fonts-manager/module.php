<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

use VEC\CoreXBaseXModule as BaseModule;
use VEC\CoreXCommonXModulesXAjaxXModule as Ajax;

class ModulesXFontsManagerXModule extends BaseModule
{
    private $enqueued_fonts = [];

    public function getName()
    {
        return 'fonts-manager';
    }

    private function getFontTypes()
    {
        static $font_types;

        if (null === $font_types) {
            $font_types = get_post_meta(0, 'elementor_fonts_manager_font_types', true);

            empty($font_types) && $font_types = [];
        }
        return $font_types;
    }

    public function getFonts($family = null)
    {
        static $fonts;

        if (null === $fonts) {
            $fonts = get_post_meta(0, 'elementor_fonts_manager_fonts', true);

            empty($fonts) && $fonts = [];
        }

        if ($family) {
            return isset($fonts[$family]) ? $fonts[$family] : false;
        }
        return $fonts;
    }

    public function registerFontsGroups($font_groups)
    {
        $new_groups = [
            'custom' => __('Custom'),
        ];
        return array_merge($new_groups, $font_groups);
    }

    public function registerFontsInControl($fonts)
    {
        return array_merge($this->getFontTypes(), $fonts);
    }

    public function enqueueFonts($post_css)
    {
        $stylesheet = $post_css->getStylesheet();
        $used_fonts = $post_css->getFonts();
        $custom_fonts = $this->getFonts();

        foreach ($used_fonts as $font_family) {
            if (!isset($custom_fonts[$font_family]['font_face']) || in_array($font_family, $this->enqueued_fonts)) {
                continue;
            }
            $font_faces = str_replace('{{BASE}}', __PS_BASE_URI__, $custom_fonts[$font_family]['font_face']);

            $stylesheet->addRawCss("/* Start Custom Fonts CSS */ $font_faces /* End Custom Fonts CSS */");

            $this->enqueued_fonts[] = $font_family;
        }
    }

    public function fontsManagerPanelActionData(array $data)
    {
        if (empty($data['type'])) {
            throw new \Exception('font_type_is_required');
        }

        if (empty($data['font'])) {
            throw new \Exception('font_is_required');
        }

        $font_family = preg_replace('/[^\w \-]+/', '', $data['font']);

        $font = $this->getFonts($font_family);

        if (empty($font['font_face'])) {
            $error_message = sprintf(__('Font %s was not found.'), $font_family);

            throw new \Exception($error_message);
        }

        return str_replace('{{BASE}}', __PS_BASE_URI__, $font);
    }

    public function registerAjaxActions(Ajax $ajax)
    {
        $ajax->registerAjaxAction('assets_manager_panel_action_data', [$this, 'fontsManagerPanelActionData']);
    }

    public function __construct()
    {
        add_filter('elementor/fonts/groups', [$this, 'registerFontsGroups']);
        add_filter('elementor/fonts/additional_fonts', [$this, 'registerFontsInControl']);

        add_action('elementor/css-file/post/parse', [$this, 'enqueueFonts']);
        add_action('elementor/css-file/global/parse', [$this, 'enqueueFonts']);
        // Ajax
        add_action('elementor/ajax/register_actions', [$this, 'registerAjaxActions']);

        do_action('elementor/fonts_manager_loaded', $this);
    }
}
