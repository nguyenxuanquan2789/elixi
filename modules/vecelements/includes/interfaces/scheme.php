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
 * Scheme interface.
 *
 * An interface for Elementor Scheme.
 *
 * @since 1.0.0
 */
interface SchemeInterface
{
    /**
     * Get scheme type.
     *
     * Retrieve the scheme type.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function getType();

    /**
     * Get scheme title.
     *
     * Retrieve the scheme title.
     *
     * @since 1.0.0
     * @access public
     */
    public function getTitle();

    /**
     * Get scheme disabled title.
     *
     * Retrieve the scheme disabled title.
     *
     * @since 1.0.0
     * @access public
     */
    public function getDisabledTitle();

    /**
     * Get scheme titles.
     *
     * Retrieve the scheme titles.
     *
     * @since 1.0.0
     * @access public
     */
    public function getSchemeTitles();

    /**
     * Get default scheme.
     *
     * Retrieve the default scheme.
     *
     * @since 1.0.0
     * @access public
     */
    public function getDefaultScheme();

    /**
     * Print scheme content template.
     *
     * Used to generate the HTML in the editor using Underscore JS template. The
     * variables for the class are available using `data` JS object.
     *
     * @since 1.0.0
     * @access public
     */
    public function printTemplateContent();
}
