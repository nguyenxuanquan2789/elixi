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
 * Elementor settings base model.
 *
 * Elementor settings base model handler class is responsible for registering
 * and managing Elementor settings base models.
 *
 * @since 1.6.0
 * @abstract
 */
abstract class CoreXSettingsXBaseXModel extends ControlsStack
{
    /**
     * Get CSS wrapper selector.
     *
     * Retrieve the wrapper selector for the current panel.
     *
     * @since 1.6.0
     * @access public
     * @abstract
     */
    abstract public function getCssWrapperSelector();

    /**
     * Get panel page settings.
     *
     * Retrieve the page setting for the current panel.
     *
     * @since 1.6.0
     * @access public
     * @abstract
     */
    abstract public function getPanelPageSettings();
}
