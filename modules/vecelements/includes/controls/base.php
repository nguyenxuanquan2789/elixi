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

use VEC\CoreXBaseXBaseObject as BaseObject;

/**
 * Elementor base control.
 *
 * An abstract class for creating new controls in the panel.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class BaseControl extends BaseObject
{
    /**
     * Base settings.
     *
     * Holds all the base settings of the control.
     *
     * @access private
     *
     * @var array
     */
    private $_base_settings = [
        'label' => '',
        'description' => '',
        'show_label' => true,
        'label_block' => false,
        'separator' => 'default',
    ];

    /**
     * Get features.
     *
     * Retrieve the list of all the available features. Currently Elementor uses only
     * the `UI` feature.
     *
     * @since 1.5.0
     * @access public
     * @static
     *
     * @return array Features array.
     */
    public static function getFeatures()
    {
        return [];
    }

    /**
     * Get control type.
     *
     * Retrieve the control type.
     *
     * @since 1.5.0
     * @access public
     * @abstract
     */
    abstract public function getType();

    /**
     * Control base constructor.
     *
     * Initializing the control base class.
     *
     * @since 1.5.0
     * @access public
     */
    public function __construct()
    {
        $this->setSettings(array_merge($this->_base_settings, $this->getDefaultSettings()));

        $this->setSettings('features', static::getFeatures());
    }

    /**
     * Enqueue control scripts and styles.
     *
     * Used to register and enqueue custom scripts and styles used by the control.
     *
     * @since 1.5.0
     * @access public
     */
    public function enqueue()
    {
    }

    /**
     * Control content template.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * Note that the content template is wrapped by BaseControl::printTemplate().
     *
     * @since 1.5.0
     * @access public
     * @abstract
     */
    abstract public function contentTemplate();

    /**
     * Print control template.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.5.0
     * @access public
     */
    final public function printTemplate()
    {
        ?>
        <script type="text/html" id="tmpl-elementor-control-<?= esc_attr($this->getType()) ?>-content">
            <div class="elementor-control-content">
                <?php $this->contentTemplate() ?>
            </div>
        </script>
        <?php
    }

    /**
     * Get default control settings.
     *
     * Retrieve the default settings of the control. Used to return the default
     * settings while initializing the control.
     *
     * @since 1.5.0
     * @access protected
     *
     * @return array Control default settings.
     */
    protected function getDefaultSettings()
    {
        return [];
    }
}
