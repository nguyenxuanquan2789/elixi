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

use VEC\CoreXCommonXModulesXAjaxXModule as Ajax;
use VEC\CoreXFilesXCSSXBase as Base;

/**
 * Elementor settings base manager.
 *
 * Elementor settings base manager handler class is responsible for registering
 * and managing Elementor settings base managers.
 *
 * @since 1.6.0
 * @abstract
 */
abstract class CoreXSettingsXBaseXManager
{
    /**
     * Models cache.
     *
     * Holds all the models.
     *
     * @since 1.6.0
     * @access private
     *
     * @var Model[]
     */
    private $models_cache = [];

    /**
     * Settings base manager constructor.
     *
     * Initializing Elementor settings base manager.
     *
     * @since 1.6.0
     * @access public
     */
    public function __construct()
    {
        add_action('elementor/editor/init', [$this, 'on_elementor_editor_init']);

        add_action('elementor/ajax/register_actions', [$this, 'register_ajax_actions']);

        $name = $this->getCssFileName();

        add_action("elementor/css-file/{$name}/parse", [$this, 'add_settings_css_rules']);
    }

    /**
     * Register ajax actions.
     *
     * Add new actions to handle data after an ajax requests returned.
     *
     * Fired by `elementor/ajax/register_actions` action.
     *
     * @since 2.0.0
     * @access public
     *
     * @param Ajax $ajax_manager
     */
    public function registerAjaxActions($ajax_manager)
    {
        $name = $this->getName();
        $ajax_manager->registerAjaxAction("save_{$name}_settings", [$this, 'ajax_save_settings']);
    }

    /**
     * Get model for config.
     *
     * Retrieve the model for settings configuration.
     *
     * @since 1.6.0
     * @access public
     * @abstract
     *
     * @return Model The model object.
     */
    abstract public function getModelForConfig();

    /**
     * Get manager name.
     *
     * Retrieve settings manager name.
     *
     * @since 1.6.0
     * @access public
     * @abstract
     */
    abstract public function getName();

    /**
     * Get model.
     *
     * Retrieve the model for any given model ID.
     *
     * @since 1.6.0
     * @access public
     *
     * @param int $id Optional. Model ID. Default is `0`.
     *
     * @return Model The model.
     */
    final public function getModel($id = 0)
    {
        $uid = "$id";

        if (!isset($this->models_cache[$uid])) {
            $this->createModel($uid);
        }

        return $this->models_cache[$uid];
    }

    /**
     * Ajax request to save settings.
     *
     * Save settings using an ajax request.
     *
     * @since 1.6.0
     * @access public
     *
     * @param array $request Ajax request.
     *
     * @return array Ajax response data.
     */
    final public function ajaxSaveSettings($request)
    {
        $data = $request['data'];

        $id = 0;

        if (!empty($request['id'])) {
            $id = $request['id'];
        }

        $this->ajaxBeforeSaveSettings($data, $id);
        $this->saveSettings($data, $id);

        $settings_name = $this->getName();

        $success_response_data = [];

        /**
         * Settings success response data.
         *
         * Filters the success response data when saving settings using ajax.
         *
         * The dynamic portion of the hook name, `$settings_name`, refers to the settings name.
         *
         * @since 2.0.0
         *
         * @param array $success_response_data Success response data.
         * @param int   $id                    Settings ID.
         * @param array $data                  Settings data.
         */
        $success_response_data = apply_filters("elementor/settings/{$settings_name}/success_response_data", $success_response_data, $id, $data);

        return $success_response_data;
    }

    /**
     * Save settings.
     *
     * Save settings to the database and update the CSS file.
     *
     * @since 1.6.0
     * @access public
     *
     * @param array $settings Settings.
     * @param int   $id       Optional. Post ID. Default is `0`.
     */
    final public function saveSettings(array $settings, $id = 0)
    {
        $special_settings = $this->getSpecialSettingsNames();

        $settings_to_save = $settings;

        foreach ($special_settings as $special_setting) {
            if (isset($settings_to_save[$special_setting])) {
                unset($settings_to_save[$special_setting]);
            }
        }

        $this->saveSettingsToDb($settings_to_save, $id);

        // Clear cache after save.
        if (isset($this->models_cache[$id])) {
            unset($this->models_cache[$id]);
        }

        $css_file = $this->getCssFileForUpdate($id);

        if ($css_file) {
            $css_file->update();
        }
    }

    /**
     * Add settings CSS rules.
     *
     * Add new CSS rules to the settings manager.
     *
     * Fired by `elementor/css-file/{$name}/parse` action.
     *
     * @since 1.6.0
     * @access public
     *
     * @param Base $css_file The requested CSS file.
     */
    public function addSettingsCssRules(Base $css_file)
    {
        $model = $this->getModelForCssFile($css_file);

        $css_file->addControlsStackStyleRules(
            $model,
            $model->getStyleControls(),
            $model->getSettings(),
            ['{{WRAPPER}}'],
            [$model->getCssWrapperSelector()]
        );
    }

    /**
     * On Elementor init.
     *
     * Add editor template for the settings
     *
     * Fired by `elementor/init` action.
     *
     * @since 2.3.0
     * @access public
     */
    public function onElementorEditorInit()
    {
        Plugin::$instance->common->addTemplate($this->getEditorTemplate(), 'text');
    }

    /**
     * Get saved settings.
     *
     * Retrieve the saved settings from the database.
     *
     * @since 1.6.0
     * @access protected
     * @abstract
     *
     * @param int $id Post ID.
     */
    abstract protected function getSavedSettings($id);

    /**
     * Get CSS file name.
     *
     * Retrieve CSS file name for the settings base manager.
     *
     * @since 1.6.0
     * @access protected
     * @abstract
     */
    abstract protected function getCssFileName();

    /**
     * Save settings to DB.
     *
     * Save settings to the database.
     *
     * @since 1.6.0
     * @access protected
     * @abstract
     *
     * @param array $settings Settings.
     * @param int   $id       Post ID.
     */
    abstract protected function saveSettingsToDb(array $settings, $id);

    /**
     * Get model for CSS file.
     *
     * Retrieve the model for the CSS file.
     *
     * @since 1.6.0
     * @access protected
     * @abstract
     *
     * @param Base $css_file The requested CSS file.
     */
    abstract protected function getModelForCssFile(Base $css_file);

    /**
     * Get CSS file for update.
     *
     * Retrieve the CSS file before updating it.
     *
     * @since 1.6.0
     * @access protected
     * @abstract
     *
     * @param int $id Post ID.
     */
    abstract protected function getCssFileForUpdate($id);

    /**
     * Get special settings names.
     *
     * Retrieve the names of the special settings that are not saved as regular
     * settings. Those settings have a separate saving process.
     *
     * @since 1.6.0
     * @access protected
     *
     * @return array Special settings names.
     */
    protected function getSpecialSettingsNames()
    {
        return [];
    }

    /**
     * Ajax before saving settings.
     *
     * Validate the data before saving it and updating the data in the database.
     *
     * @since 1.6.0
     * @access public
     *
     * @param array $data Post data.
     * @param int   $id   Post ID.
     */
    public function ajaxBeforeSaveSettings(array $data, $id)
    {
    }

    /**
     * Print the setting template content in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since 1.6.0
     * @access protected
     *
     * @param string $name Settings panel name.
     */
    protected function printEditorTemplateContent($name)
    {
        ?>
        <div class="elementor-panel-navigation">
        <# _.each( elementor.config.settings.<?= esc_html($name) ?>.tabs, function( tabTitle, tabSlug ) { #>
            <div class="elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
                <a href="#">{{{ tabTitle }}}</a>
            </div>
        <# } ); #>
        </div>
        <div id="elementor-panel-<?= esc_attr($name) ?>-settings-controls"></div>
        <?php
    }

    /**
     * Create model.
     *
     * Create a new model object for any given model ID and store the object in
     * models cache property for later use.
     *
     * @since 1.6.0
     * @access private
     *
     * @param int $id Model ID.
     */
    private function createModel($id)
    {
        $class_parts = explode('X', get_called_class());

        array_splice($class_parts, count($class_parts) - 1, 1, 'Model');

        $class_name = implode('X', $class_parts);

        $this->models_cache[$id] = new $class_name([
            'id' => $id,
            'settings' => $this->getSavedSettings($id),
        ]);
    }

    /**
     * Get editor template.
     *
     * Retrieve the final HTML for the editor.
     *
     * @since 1.6.0
     * @access private
     *
     * @return string Settings editor template.
     */
    private function getEditorTemplate()
    {
        $name = $this->getName();

        ob_start();
        ?>
        <script type="text/template" id="tmpl-elementor-panel-<?= esc_attr($name) ?>-settings">
            <?php $this->printEditorTemplateContent($name) ?>
        </script>
        <?php

        return ob_get_clean();
    }
}
