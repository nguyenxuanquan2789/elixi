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
 * Elementor image size control.
 *
 * A base control for creating image size control. Displays input fields to define
 * one of the default image sizes (thumbnail, medium, medium_large, large) or custom
 * image dimensions.
 *
 * @since 1.0.0
 */
class GroupControlImageSize extends GroupControlBase
{
    /**
     * Fields.
     *
     * Holds all the image size control fields.
     *
     * @since 1.2.2
     * @access protected
     * @static
     *
     * @var array Image size control fields.
     */
    protected static $fields;

    /**
     * Get image size control type.
     *
     * Retrieve the control type, in this case `image-size`.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return string Control type.
     */
    public static function getType()
    {
        return 'image-size';
    }

    /**
     * Get attachment image HTML.
     *
     * Retrieve the attachment image HTML code.
     *
     * Note that some widgets use the same key for the media control that allows
     * the image selection and for the image size control that allows the user
     * to select the image size, in this case the third parameter should be null
     * or the same as the second parameter. But when the widget uses different
     * keys for the media control and the image size control, when calling this
     * method you should pass the keys.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param array  $settings       Control settings.
     * @param string $image_key      Optional. Settings key for image. Default
     *                               is null. If not defined uses image size key
     *                               as the image key.
     *
     * @return string Image HTML.
     */
    public static function getAttachmentImageHtml($settings, $setting_key = 'image', $loading = 'lazy', $class = '')
    {
        if (empty($settings[$setting_key]['url'])) {
            return '';
        }
        if(isset($settings['_css_classes']) && $settings['_css_classes'] == 'nolazy') $loading = '';
        $attr = [
            'src="' . esc_attr(Helper::getMediaLink($settings[$setting_key]['url'])) . '"',
            'loading="' . esc_attr($loading) . '"',
            'alt="' . ControlMedia::getImageAlt($settings[$setting_key]) . '"',
        ];
        if ($title = ControlMedia::getImageTitle($settings[$setting_key])) {
            $attr[] = 'title="' . $title . '"';
        }
        if (isset($settings[$setting_key]['width'], $settings[$setting_key]['height'])) {
            $attr[] = 'width="' . (int) $settings[$setting_key]['width'] . '"';
            $attr[] = 'height="' . (int) $settings[$setting_key]['height'] . '"';
        }
        if (!empty($settings['hover_animation'])) {
            $class .= ($class ? ' ' : '') . 'elementor-animation-' . $settings['hover_animation'];
        }
        if ($class) {
            $attr[] = 'class="' . $class . '"';
        }

        $html = '<img ' . implode(' ', $attr) . '>';

        /**
         * Get Attachment Image HTML
         *
         * Filters the Attachment Image HTML
         *
         * @since 2.4.0
         * @param string $html the attachment image HTML string
         * @param array  $settings       Control settings.
         * @param string $image_size_key Optional. Settings key for image size.
         *                               Default is `image`.
         * @param string $image_key      Optional. Settings key for image. Default
         *                               is null. If not defined uses image size key
         *                               as the image key.
         */
        return apply_filters('elementor/image_size/get_attachment_image_html', $html, $settings, $setting_key, $loading);
    }

    /**
     * Get all image sizes.
     *
     * Retrieve available image sizes with data like `width`, `height` and `crop`.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return array An array of available image sizes.
     */
    public static function getAllImageSizes()
    {
        // global
        $_wp_additional_image_sizes = [];

        $default_image_sizes = ['thumbnail', 'medium', 'medium_large', 'large'];

        $image_sizes = [];

        foreach ($default_image_sizes as $size) {
            $image_sizes[$size] = [
                'width' => (int) get_option($size . '_size_w'),
                'height' => (int) get_option($size . '_size_h'),
                'crop' => (bool) get_option($size . '_crop'),
            ];
        }

        if ($_wp_additional_image_sizes) {
            $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
        }

        /** This filter is documented in wp-admin/includes/media.php */
        return apply_filters('image_size_names_choose', $image_sizes);
    }

    /**
     * Get attachment image src.
     *
     * Retrieve the attachment image source URL.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @param string $attachment_id  The attachment ID.
     * @param string $image_size_key Settings key for image size.
     * @param array  $settings       Control settings.
     *
     * @return string Attachment image source URL.
     */
    public static function getAttachmentImageSrc($attachment_id, $image_size_key, array $settings)
    {
        return false;
    }

    /**
     * Get child default arguments.
     *
     * Retrieve the default arguments for all the child controls for a specific group
     * control.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Default arguments for all the child controls.
     */
    protected function getChildDefaultArgs()
    {
        return [
            'include' => [],
            'exclude' => [],
        ];
    }

    /**
     * Init fields.
     *
     * Initialize image size control fields.
     *
     * @since 1.2.2
     * @access protected
     *
     * @return array Control fields.
     */
    protected function initFields()
    {
        $fields = [];

        $fields['size'] = [
            'label' => _x('Image Size', 'Image Size Control'),
            'type' => ControlsManager::SELECT,
            'label_block' => false,
        ];

        // $fields['custom_dimension'] = [
        //     'label' => _x('Image Dimension', 'Image Size Control'),
        //     'type' => ControlsManager::IMAGE_DIMENSIONS,
        //     'description' => __('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.'),
        //     'condition' => [
        //         'size' => 'custom',
        //     ],
        //     'separator' => 'none',
        // ];

        return $fields;
    }

    /**
     * Prepare fields.
     *
     * Process image size control fields before adding them to `add_control()`.
     *
     * @since 1.2.2
     * @access protected
     *
     * @param array $fields Image size control fields.
     *
     * @return array Processed fields.
     */
    protected function prepareFields($fields)
    {
        $image_sizes = $this->getImageSizes();

        $args = $this->getArgs();

        if (!empty($args['default']) && isset($image_sizes[$args['default']])) {
            $default_value = $args['default'];
        } else {
            // Get the first item for default value.
            $default_value = array_keys($image_sizes);
            $default_value = array_shift($default_value);
        }

        $fields['size']['options'] = $image_sizes;

        $fields['size']['default'] = $default_value;

        if (!isset($image_sizes['custom'])) {
            unset($fields['custom_dimension']);
        }

        return parent::prepareFields($fields);
    }

    /**
     * Get image sizes.
     *
     * Retrieve available image sizes after filtering `include` and `exclude` arguments.
     *
     * @since 2.0.0
     * @access private
     *
     * @return array Filtered image sizes.
     */
    private function getImageSizes()
    {
        $wp_image_sizes = self::getAllImageSizes();

        $args = $this->getArgs();

        if ($args['include']) {
            $wp_image_sizes = array_intersect_key($wp_image_sizes, array_flip($args['include']));
        } elseif ($args['exclude']) {
            $wp_image_sizes = array_diff_key($wp_image_sizes, array_flip($args['exclude']));
        }

        $image_sizes = [];

        foreach ($wp_image_sizes as $size_key => $size_attributes) {
            $control_title = ucwords(str_replace('_', ' ', $size_key));
            if (is_array($size_attributes)) {
                $control_title .= sprintf(' - %d x %d', $size_attributes['width'], $size_attributes['height']);
            }

            $image_sizes[$size_key] = $control_title;
        }

        $image_sizes['full'] = _x('Full', 'Image Size Control');

        if (!empty($args['include']['custom']) || !in_array('custom', $args['exclude'])) {
            $image_sizes['custom'] = _x('Custom', 'Image Size Control');
        }

        return $image_sizes;
    }

    /**
     * Get default options.
     *
     * Retrieve the default options of the image size control. Used to return the
     * default options while initializing the image size control.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Default image size control options.
     */
    protected function getDefaultOptions()
    {
        return [
            'popover' => false,
        ];
    }
}
