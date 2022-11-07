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
 * Group control interface.
 *
 * An interface for Elementor group control.
 *
 * @since 1.0.0
 */
interface GroupControlInterface
{
    /**
     * Get group control type.
     *
     * Retrieve the group control type.
     *
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function getType();
}
