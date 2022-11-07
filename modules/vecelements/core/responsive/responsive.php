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

use VEC\CoreXResponsiveXFilesXFrontend as FrontendFile;

/**
 * Elementor responsive.
 *
 * Elementor responsive handler class is responsible for setting up Elementor
 * responsive breakpoints.
 *
 * @since 1.0.0
 */
class CoreXResponsiveXResponsive
{
    /**
     * The Elementor breakpoint prefix.
     */
    const BREAKPOINT_OPTION_PREFIX = 'elementor_viewport_';

    /**
     * Default breakpoints.
     *
     * Holds the default responsive breakpoints.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var array Default breakpoints.
     */
    private static $default_breakpoints = [
        'xs' => 0,
        'sm' => 480,
        'md' => 768,
        'lg' => 1025,
        'xl' => 1440,
        'xxl' => 1600,
    ];

    /**
     * Editable breakpoint keys.
     *
     * Holds the editable breakpoint keys.
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var array Editable breakpoint keys.
     */
    private static $editable_breakpoints_keys = [
        'md',
        'lg',
    ];

    /**
     * Get default breakpoints.
     *
     * Retrieve the default responsive breakpoints.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array Default breakpoints.
     */
    public static function getDefaultBreakpoints()
    {
        return self::$default_breakpoints;
    }

    /**
     * Get editable breakpoints.
     *
     * Retrieve the editable breakpoints.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array Editable breakpoints.
     */
    public static function getEditableBreakpoints()
    {
        return array_intersect_key(self::getBreakpoints(), array_flip(self::$editable_breakpoints_keys));
    }

    /**
     * Get breakpoints.
     *
     * Retrieve the responsive breakpoints.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array Responsive breakpoints.
     */
    public static function getBreakpoints()
    {
        $self = __CLASS__;

        return array_reduce(
            array_keys(self::$default_breakpoints),
            function ($new_array, $breakpoint_key) use ($self) {
                if (!in_array($breakpoint_key, $self::${'editable_breakpoints_keys'})) {
                    $new_array[$breakpoint_key] = $self::${'default_breakpoints'}[$breakpoint_key];
                } else {
                    $saved_option = get_option($self::BREAKPOINT_OPTION_PREFIX . $breakpoint_key);

                    $new_array[$breakpoint_key] = $saved_option ? (int) $saved_option : $self::${'default_breakpoints'}[$breakpoint_key];
                }

                return $new_array;
            },
            []
        );
    }

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function hasCustomBreakpoints()
    {
        return !!array_diff(self::$default_breakpoints, self::getBreakpoints());
    }

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function getStylesheetTemplatesPath()
    {
        return _VEC_PATH_ . 'views/css/';
    }

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function compileStylesheetTemplates()
    {
        foreach (self::getStylesheetTemplates() as $file_name => $template_path) {
            $file = new FrontendFile($file_name, $template_path);

            $file->update();
        }
    }

    /**
     * @since 2.1.0
     * @access private
     * @static
     */
    private static function getStylesheetTemplates()
    {
        // $templates_paths = glob(self::getStylesheetTemplatesPath() . '*.css');
        $stylesheet_path = self::getStylesheetTemplatesPath();
        $templates_paths = [
            "{$stylesheet_path}frontend.css",
            "{$stylesheet_path}frontend.min.css",
            "{$stylesheet_path}frontend-rtl.css",
            "{$stylesheet_path}frontend-rtl.min.css",
        ];

        $templates = [];
        $id_shop = (int) \Context::getContext()->shop->id;

        foreach ($templates_paths as $template_path) {
            $file_name = "$id_shop-" . basename($template_path);

            $templates[$file_name] = $template_path;
        }

        return apply_filters('elementor/core/responsive/get_stylesheet_templates', $templates);
    }
}
