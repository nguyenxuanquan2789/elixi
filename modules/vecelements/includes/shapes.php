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
 * Elementor shapes.
 *
 * Elementor shapes handler class is responsible for setting up the supported
 * shape dividers.
 *
 * @since 1.3.0
 */
class Shapes
{
    /**
     * The exclude filter.
     */
    const FILTER_EXCLUDE = 'exclude';

    /**
     * The include filter.
     */
    const FILTER_INCLUDE = 'include';

    /**
     * Shapes.
     *
     * Holds the list of supported shapes.
     *
     * @since 1.3.0
     * @access private
     * @static
     *
     * @var array A list of supported shapes.
     */
    private static $shapes;

    /**
     * Get shapes.
     *
     * Retrieve a shape from the lists of supported shapes. If no shape specified
     * it will return all the supported shapes.
     *
     * @since 1.3.0
     * @access public
     * @static
     *
     * @param array $shape Optional. Specific shape. Default is `null`.
     *
     * @return array The specified shape or a list of all the supported shapes.
     */
    public static function getShapes($shape = null)
    {
        if (null === self::$shapes) {
            self::initShapes();
        }

        if ($shape) {
            return isset(self::$shapes[$shape]) ? self::$shapes[$shape] : null;
        }

        return self::$shapes;
    }

    /**
     * Filter shapes.
     *
     * Retrieve shapes filtered by a specific condition, from the list of
     * supported shapes.
     *
     * @since 1.3.0
     * @access public
     * @static
     *
     * @param string $by     Specific condition to filter by.
     * @param string $filter Optional. Comparison condition to filter by.
     *                       Default is `include`.
     *
     * @return array A list of filtered shapes.
     */
    public static function filterShapes($by, $filter = self::FILTER_INCLUDE)
    {
        return array_filter(
            self::getShapes(),
            function ($shape) use ($by, $filter) {
                return self::FILTER_INCLUDE === $filter xor empty($shape[$by]);
            }
        );
    }

    /**
     * Get shape path.
     *
     * For a given shape, retrieve the file path.
     *
     * @since 1.3.0
     * @access public
     * @static
     *
     * @param string $shape       The shape.
     * @param bool   $is_negative Optional. Whether the file name is negative or
     *                            not. Default is `false`.
     *
     * @return string Shape file path.
     */
    public static function getShapePath($shape, $is_negative = false)
    {

        if (isset(self::$shapes[$shape]) && isset(self::$shapes[$shape]['path'])) {
            return self::$shapes[$shape]['path'];
        }

        $file_name = $shape;

        if ($is_negative) {
            $file_name .= '-negative';
        }

        return _VEC_PATH_ . 'views/img/shapes/' . $file_name . '.svg';
    }

    /**
     * Init shapes.
     *
     * Set the supported shapes.
     *
     * @since 1.3.0
     * @access private
     * @static
     */
    private static function initShapes()
    {
        $native_shapes = [
            'mountains' => [
                'title' => _x('Mountains', 'Shapes'),
                'has_flip' => true,
            ],
            'drops' => [
                'title' => _x('Drops', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
                'height_only' => true,
            ],
            'clouds' => [
                'title' => _x('Clouds', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
                'height_only' => true,
            ],
            'zigzag' => [
                'title' => _x('Zigzag', 'Shapes'),
            ],
            'pyramids' => [
                'title' => _x('Pyramids', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
            ],
            'triangle' => [
                'title' => _x('Triangle', 'Shapes'),
                'has_negative' => true,
            ],
            'triangle-asymmetrical' => [
                'title' => _x('Triangle Asymmetrical', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
            ],
            'tilt' => [
                'title' => _x('Tilt', 'Shapes'),
                'has_flip' => true,
                'height_only' => true,
            ],
            'opacity-tilt' => [
                'title' => _x('Tilt Opacity', 'Shapes'),
                'has_flip' => true,
            ],
            'opacity-fan' => [
                'title' => _x('Fan Opacity', 'Shapes'),
            ],
            'curve' => [
                'title' => _x('Curve', 'Shapes'),
                'has_negative' => true,
            ],
            'curve-asymmetrical' => [
                'title' => _x('Curve Asymmetrical', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
            ],
            'waves' => [
                'title' => _x('Waves', 'Shapes'),
                'has_negative' => true,
                'has_flip' => true,
            ],
            'wave-brush' => [
                'title' => _x('Waves Brush', 'Shapes'),
                'has_flip' => true,
            ],
            'waves-pattern' => [
                'title' => _x('Waves Pattern', 'Shapes'),
                'has_flip' => true,
            ],
            'arrow' => [
                'title' => _x('Arrow', 'Shapes'),
                'has_negative' => true,
            ],
            'split' => [
                'title' => _x('Split', 'Shapes'),
                'has_negative' => true,
            ],
            'book' => [
                'title' => _x('Book', 'Shapes'),
                'has_negative' => true,
            ],
        ];

        self::$shapes = array_merge($native_shapes, self::getAdditionalShapes());
    }

    /**
     * Get Additional Shapes
     *
     * Used to add custom shapes to elementor.
     *
     * @since 2.5.0
     *
     * @return array
     */
    private static function getAdditionalShapes()
    {
        static $additional_shapes = null;

        if (null !== $additional_shapes) {
            return $additional_shapes;
        }
        $additional_shapes = [];
        /**
         * Additional shapes.
         *
         * Filters the shapes used by Elementor to add additional shapes.
         *
         * @since 2.0.1
         *
         * @param array $additional_shapes Additional Elementor shapes.
         */
        $additional_shapes = apply_filters('elementor/shapes/additional_shapes', $additional_shapes);
        return $additional_shapes;
    }

    /**
     * Get Additional Shapes For Config
     *
     * Used to set additional shape paths for editor
     *
     * @since 2.5.0
     *
     * @return array|bool
     */
    public static function getAdditionalShapesForConfig()
    {
        $additional_shapes = self::getAdditionalShapes();
        if (empty($additional_shapes)) {
            return false;
        }

        $additional_shapes_config = [];
        foreach ($additional_shapes as $shape_name => $shape_settings) {
            if (!isset($shape_settings['url'])) {
                continue;
            }
            $additional_shapes_config[$shape_name] = $shape_settings['url'];
        }

        if (empty($additional_shapes_config)) {
            return false;
        }

        return $additional_shapes_config;
    }
}
