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

use VEC\CoreXBaseXModule as Module;

/**
 * Elementor modules manager.
 *
 * Elementor modules manager handler class is responsible for registering and
 * managing Elementor modules.
 *
 * @since 1.6.0
 */
class CoreXModulesManager
{
    /**
     * Registered modules.
     *
     * Holds the list of all the registered modules.
     *
     * @since 1.6.0
     * @access public
     *
     * @var array
     */
    private $modules = [];

    /**
     * Modules manager constructor.
     *
     * Initializing the Elementor modules manager.
     *
     * @since 1.6.0
     * @access public
     */
    public function __construct()
    {
        $modules_namespace_prefix = $this->getModulesNamespacePrefix();

        foreach ($this->getModulesNames() as $module_name) {
            $class_name = str_replace('-', ' ', $module_name);

            $class_name = str_replace(' ', '', ucwords($class_name));

            $class_name = $modules_namespace_prefix . '\ModulesX' . $class_name . 'XModule';

            /** @var Module $class_name */
            if ($class_name::isActive()) {
                $this->modules[$module_name] = $class_name::instance();
            }
        }
    }

    /**
     * Get modules names.
     *
     * Retrieve the modules names.
     *
     * @since 2.0.0
     * @access public
     *
     * @return string[] Modules names.
     */
    public function getModulesNames()
    {
        return [
            'history',
            'library',
            // 'dynamic-tags',
            'page-templates',
            'creative',
            'fonts-manager',
            'custom-css',
            'sticky',
        ];
    }

    /**
     * Get modules.
     *
     * Retrieve all the registered modules or a specific module.
     *
     * @since 2.0.0
     * @access public
     *
     * @param string $module_name Module name.
     *
     * @return null|Module|Module[] All the registered modules or a specific module.
     */
    public function getModules($module_name)
    {
        if ($module_name) {
            if (isset($this->modules[$module_name])) {
                return $this->modules[$module_name];
            }

            return null;
        }

        return $this->modules;
    }

    /**
     * Get modules namespace prefix.
     *
     * Retrieve the modules namespace prefix.
     *
     * @since 2.0.0
     * @access protected
     *
     * @return string Modules namespace prefix.
     */
    protected function getModulesNamespacePrefix()
    {
        return 'VEC';
    }
}
