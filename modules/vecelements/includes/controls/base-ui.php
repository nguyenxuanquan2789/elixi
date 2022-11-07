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
 * Elementor base UI control.
 *
 * An abstract class for creating new UI controls in the panel.
 *
 * @abstract
 */
abstract class BaseUiControl extends BaseControl
{
    /**
     * Get features.
     *
     * Retrieve the list of all the available features.
     *
     * @since 1.5.0
     * @access public
     * @static
     *
     * @return array Features array.
     */
    public static function getFeatures()
    {
        return ['ui'];
    }
}
