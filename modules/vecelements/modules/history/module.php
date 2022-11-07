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

use VEC\CoreXBaseXModule as BaseModule;

/**
 * Elementor history module.
 *
 * Elementor history module handler class is responsible for registering and
 * managing Elementor history modules.
 *
 * @since 1.7.0
 */
class ModulesXHistoryXModule extends BaseModule
{
    /**
     * Get module name.
     *
     * Retrieve the history module name.
     *
     * @since 1.7.0
     * @access public
     *
     * @return string Module name.
     */
    public function getName()
    {
        return 'history';
    }

    /**
     * Localize settings.
     *
     * Add new localized settings for the history module.
     *
     * Fired by `elementor/editor/localize_settings` filter.
     *
     * @since 1.7.0
     * @access public
     *
     * @param array $settings Localized settings.
     *
     * @return array Localized settings.
     */
    public function localizeSettings($settings)
    {
        $settings = array_replace_recursive($settings, [
            'i18n' => [
                'history' => __('History'),
                'template' => __('Template'),
                'added' => __('Added'),
                'removed' => __('Removed'),
                'edited' => __('Edited'),
                'moved' => __('Moved'),
                'editing_started' => __('Editing Started'),
                'style_pasted' => __('Style Pasted'),
                'style_reset' => __('Style Reset'),
                'all_content' => __('All Content'),
            ],
        ]);

        return $settings;
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function addTemplates()
    {
        Plugin::$instance->common->addTemplate(_VEC_PATH_ . 'modules/history/views/history-panel-template.php');
        Plugin::$instance->common->addTemplate(_VEC_PATH_ . 'modules/history/views/revisions-panel-template.php');
    }

    /**
     * History module constructor.
     *
     * Initializing Elementor history module.
     *
     * @since 1.7.0
     * @access public
     */
    public function __construct()
    {
        add_filter('elementor/editor/localize_settings', [$this, 'localize_settings']);

        add_action('elementor/editor/init', [$this, 'add_templates']);
    }
}
