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

/**
 * Elementor utils.
 *
 * Elementor utils handler class is responsible for different utility methods
 * used by Elementor.
 *
 * @since 1.0.0
 */
class Utils
{
    /**
     * Is ajax.
     *
     * Whether the current request is an ajax request.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return bool True if it's an ajax request, false otherwise.
     */
    public static function isAjax()
    {
        return \Tools::getIsset('ajax');
    }

    /**
     * Is script debug.
     *
     * Whether script debug is enabled or not.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return bool True if it's a script debug is active, false otherwise.
     */
    public static function isScriptDebug()
    {
        return _PS_MODE_DEV_;
    }

    // public static function getProLink($link)

    // public static function replaceUrls($from, $to)

    /**
     * Is post supports Elementor.
     *
     * Whether the post supports editing with Elementor.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param int $post_id Optional. Post ID. Default is `0`.
     *
     * @return string True if post supports editing with Elementor, false otherwise.
     */
    public static function isPostSupport($post_id = 0)
    {
        $post_type = get_post_type($post_id);

        $is_supported = self::isPostTypeSupport($post_type);

        /**
         * Is post support.
         *
         * Filters whether the post supports editing with Elementor.
         *
         * @since 2.2.0
         *
         * @param bool $is_supported Whether the post type supports editing with Elementor.
         * @param int $post_id Post ID.
         * @param string $post_type Post type.
         */
        $is_supported = apply_filters('elementor/utils/is_post_support', $is_supported, $post_id, $post_type);

        return $is_supported;
    }

    /**
     * Is post type supports Elementor.
     *
     * Whether the post type supports editing with Elementor.
     *
     * @since 2.2.0
     * @access public
     * @static
     *
     * @param string $post_type Post Type.
     *
     * @return string True if post type supports editing with Elementor, false otherwise.
     */
    public static function isPostTypeSupport($post_type)
    {
        if (!post_type_exists($post_type)) {
            return false;
        }

        if (!post_type_supports($post_type, 'elementor')) {
            return false;
        }

        return true;
    }

    /**
     * Get placeholder image source.
     *
     * Retrieve the source of the placeholder image.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string The source of the default placeholder image used by Elementor.
     */
    public static function getPlaceholderImageSrc()
    {
        $placeholder_image = basename(_MODULE_DIR_) . '/vecelements/views/img/placeholder.png';
        /**
         * Get placeholder image source.
         *
         * Filters the source of the default placeholder image used by Elementor.
         *
         * @since 1.0.0
         *
         * @param string $placeholder_image The source of the default placeholder image.
         */
        $placeholder_image = apply_filters('elementor/utils/get_placeholder_image_src', $placeholder_image);

        return $placeholder_image;
    }

    /**
     * Generate random string.
     *
     * Returns a string containing a hexadecimal representation of random number.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Random string.
     */
    public static function generateRandomString()
    {
        return dechex(rand());
    }

    // public static function doNotCache();

    /**
     * Get timezone string.
     *
     * Retrieve timezone string from the database.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Timezone string.
     */
    public static function getTimezoneString()
    {
        return \Configuration::get('PS_TIMEZONE');
    }

    /**
     * Get create new post URL.
     *
     * Retrieve a custom URL for creating a new post/page using Elementor.
     *
     * @since 1.9.0
     * @access public
     * @static
     *
     * @param string $post_type Optional. Post type slug. Default is 'page'.
     *
     * @return string A URL for creating new post using Elementor.
     */
    public static function getCreateNewPostUrl($post_type = 'page')
    {
        $new_post_url = add_query_arg([
            'action' => 'elementor_new_post',
            'post_type' => $post_type,
        ], admin_url('edit.php'));

        $new_post_url = add_query_arg('_wpnonce', wp_create_nonce('elementor_action_new_post'), $new_post_url);

        return $new_post_url;
    }

    /**
     * Get post autosave.
     *
     * Retrieve an autosave for any given post.
     *
     * @since 1.9.2
     * @access public
     * @static
     *
     * @param int $post_id Post ID.
     * @param int $user_id Optional. User ID. Default is `0`.
     *
     * @return WPPost|false Post autosave or false.
     */
    public static function getPostAutosave($post_id, $user_id = 0)
    {
        $autosave = wp_get_post_autosave($post_id, $user_id);

        return $autosave && strtotime($autosave->post_modified) > strtotime(get_post($post_id)->post_modified) ? $autosave : false;
    }

    /**
     * Is CPT supports custom templates.
     *
     * Whether the Custom Post Type supports templates.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return bool True is templates are supported, False otherwise.
     */
    public static function isCptCustomTemplatesSupported(UId $uid)
    {
        // return method_exists(wp_get_theme(), 'get_post_templates');

        return UId::CONTENT !== $uid->id_type;
    }

    /**
     * @since 2.1.2
     * @access public
     * @static
     */
    public static function arrayInject($array, $key, $insert)
    {
        $length = array_search($key, array_keys($array), true) + 1;

        return array_slice($array, 0, $length, true) + $insert + array_slice($array, $length, null, true);
    }

    /**
     * Render html attributes
     *
     * @access public
     * @static
     * @param array $attributes
     *
     * @return string
     */
    public static function renderHtmlAttributes(array $attributes)
    {
        $rendered_attributes = [];

        foreach ($attributes as $attribute_key => $attribute_values) {
            if (is_array($attribute_values)) {
                $attribute_values = implode(' ', $attribute_values);
            }

            $rendered_attributes[] = sprintf('%1$s="%2$s"', $attribute_key, esc_attr($attribute_values));
        }

        return implode(' ', $rendered_attributes);
    }

    public static function getMetaViewport($context = '')
    {
        $meta_tag = '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />';
        /**
         * Viewport meta tag.
         *
         * Filters the Elementor preview URL.
         *
         * @since 2.5.0
         *
         * @param string $meta_tag Viewport meta tag.
         */
        return apply_filters('elementor/template/viewport_tag', $meta_tag, $context);
    }

    // public static function printJsConfig($handle, $js_var, $config) - Use wp_localize_script instead
}
