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

use VEC\CoreXSettingsXBaseXManager as BaseManager;

/**
 * Elementor settings manager.
 *
 * Elementor settings manager handler class is responsible for registering and
 * managing Elementor settings managers.
 *
 * @since 1.6.0
 */
class CoreXSettingsXManager
{
    /**
     * Settings managers.
     *
     * Holds all the registered settings managers.
     *
     * @since 1.6.0
     * @access private
     *
     * @var BaseManager[]
     */
    private static $settings_managers = [];

    /**
     * Builtin settings managers names.
     *
     * Holds the names for builtin Elementor settings managers.
     *
     * @since 1.6.0
     * @access private
     *
     * @var array
     */
    private static $builtin_settings_managers_names = ['page', 'general'];

    /**
     * Add settings manager.
     *
     * Register a single settings manager to the registered settings managers.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @param BaseManager $manager Settings manager.
     */
    public static function addSettingsManager(BaseManager $manager)
    {
        self::$settings_managers[$manager->getName()] = $manager;
    }

    /**
     * Get settings managers.
     *
     * Retrieve registered settings manager(s).
     *
     * If no parameter passed, it will retrieve all the settings managers. For
     * any given parameter it will retrieve a single settings manager if one
     * exist, or `null` otherwise.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @param string $manager_name Optional. Settings manager name. Default is
     *                             null.
     *
     * @return BaseManager|BaseManager[] Single settings manager, if it exists,
     *                                     null if it doesn't exists, or the all
     *                                     the settings managers if no parameter
     *                                     defined.
     */
    public static function getSettingsManagers($manager_name = null)
    {
        if ($manager_name) {
            if (isset(self::$settings_managers[$manager_name])) {
                return self::$settings_managers[$manager_name];
            }

            return null;
        }

        return self::$settings_managers;
    }

    /**
     * Register default settings managers.
     *
     * Register builtin Elementor settings managers.
     *
     * @since 1.6.0
     * @access private
     * @static
     */
    private static function registerDefaultSettingsManagers()
    {
        foreach (self::$builtin_settings_managers_names as $manager_name) {
            $manager_class = call_user_func('substr', __CLASS__, 0, strrpos(__CLASS__, 'X') + 1) . call_user_func('ucfirst', $manager_name) . 'XManager';

            self::addSettingsManager(new $manager_class());
        }
    }

    /**
     * Get settings managers config.
     *
     * Retrieve the settings managers configuration.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @return array The settings managers configuration.
     */
    public static function getSettingsManagersConfig()
    {
        $config = [];

        // $user_can = Plugin::instance()->role_manager->userCan('design');

        foreach (self::$settings_managers as $name => $manager) {
            $settings_model = $manager->getModelForConfig();

            $tabs = $settings_model->getTabsControls();

            // if (!$user_can) {
            //     unset($tabs['style']);
            // }

            $config[$name] = [
                'name' => $manager->getName(),
                'panelPage' => $settings_model->getPanelPageSettings(),
                'cssWrapperSelector' => $settings_model->getCssWrapperSelector(),
                'controls' => $settings_model->getControls(),
                'tabs' => $tabs,
                'settings' => $settings_model->getSettings(),
            ];
        }

        return $config;
    }

    /**
     * Get settings frontend config.
     *
     * Retrieve the settings managers frontend configuration.
     *
     * @since 1.6.0
     * @access public
     * @static
     *
     * @return array The settings managers frontend configuration.
     */
    public static function getSettingsFrontendConfig()
    {
        $config = [];

        foreach (self::$settings_managers as $name => $manager) {
            $settings_model = $manager->getModelForConfig();

            if ($settings_model) {
                $config[$name] = $settings_model->getFrontendSettings();
            }
        }

        return $config;
    }

    /**
     * Run settings managers.
     *
     * Register builtin Elementor settings managers.
     *
     * @since 1.6.0
     * @access public
     * @static
     */
    public static function run()
    {
        self::registerDefaultSettingsManagers();
    }
}
