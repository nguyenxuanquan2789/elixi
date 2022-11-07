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
use VEC\CoreXSettingsXManager as SettingsManager;
use VEC\TemplateLibraryXClassesXImportImages as ImportImages;
use VEC\TemplateLibraryXSourceBase as SourceBase;
use VEC\TemplateLibraryXSourceLocal as SourceLocal;

/**
 * Elementor template library manager.
 *
 * Elementor template library manager handler class is responsible for
 * initializing the template library.
 *
 * @since 1.0.0
 */
class TemplateLibraryXManager
{
    /**
     * Registered template sources.
     *
     * Holds a list of all the supported sources with their instances.
     *
     * @access protected
     *
     * @var SourceBase[]
     */
    protected $_registered_sources = [];

    /**
     * Imported template images.
     *
     * Holds an instance of `Import_Images` class.
     *
     * @access private
     *
     * @var ImportImages
     */
    private $_import_images = null;

    /**
     * Template library manager constructor.
     *
     * Initializing the template library manager by registering default template
     * sources and initializing ajax calls.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        $this->registerDefaultSources();

        $this->addActions();
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function addActions()
    {
        add_action('elementor/ajax/register_actions', [$this, 'register_ajax_actions']);
        add_action('wp_ajax_elementor_library_direct_actions', [$this, 'handle_direct_actions']);

        // TODO: bc since 2.3.0
        add_action('wp_ajax_elementor_update_templates', function () {
            if (!isset(${'_POST'}['templates'])) {
                return;
            }

            foreach (${'_POST'}['templates'] as &$template) {
                if (!isset($template['content'])) {
                    return;
                }

                $template['content'] = call_user_func('stripslashes', $template['content']);
            }

            wp_send_json_success(${'this'}->handleAjaxRequest('update_templates', ${'_POST'}));
        });
    }

    /**
     * Get `Import_Images` instance.
     *
     * Retrieve the instance of the `Import_Images` class.
     *
     * @since 1.0.0
     * @access public
     *
     * @return ImportImages Imported images instance.
     */
    public function getImportImagesInstance()
    {
        if (null === $this->_import_images) {
            $this->_import_images = new ImportImages();
        }

        return $this->_import_images;
    }

    /**
     * Register template source.
     *
     * Used to register new template sources displayed in the template library.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $source_class The name of source class.
     * @param array  $args         Optional. Class arguments. Default is an
     *                             empty array.
     *
     * @return WPError|true True if the source was registered, `WP_Error`
     *                        otherwise.
     */
    public function registerSource($source_class, $args = [])
    {
        if (!class_exists($source_class)) {
            return new WPError('source_class_name_not_exists');
        }

        $source_instance = new $source_class($args);

        if (!$source_instance instanceof SourceBase) {
            return new WPError('wrong_instance_source');
        }
        $this->_registered_sources[$source_instance->getId()] = $source_instance;

        return true;
    }

    /**
     * Unregister template source.
     *
     * Remove an existing template sources from the list of registered template
     * sources.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $id The source ID.
     *
     * @return bool Whether the source was unregistered.
     */
    public function unregisterSource($id)
    {
        if (!isset($this->_registered_sources[$id])) {
            return false;
        }

        unset($this->_registered_sources[$id]);

        return true;
    }

    /**
     * Get registered template sources.
     *
     * Retrieve registered template sources.
     *
     * @since 1.0.0
     * @access public
     *
     * @return SourceBase[] Registered template sources.
     */
    public function getRegisteredSources()
    {
        return $this->_registered_sources;
    }

    /**
     * Get template source.
     *
     * Retrieve single template sources for a given template ID.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $id The source ID.
     *
     * @return false|Source_Base Template sources if one exist, False otherwise.
     */
    public function getSource($id)
    {
        $sources = $this->getRegisteredSources();

        if (!isset($sources[$id])) {
            return false;
        }

        return $sources[$id];
    }

    /**
     * Get templates.
     *
     * Retrieve all the templates from all the registered sources.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Templates array.
     */
    public function getTemplates()
    {
        $templates = [];

        foreach ($this->getRegisteredSources() as $source) {
            $templates = array_merge($templates, $source->getItems());
        }

        return $templates;
    }

    /**
     * Get library data.
     *
     * Retrieve the library data.
     *
     * @since 1.9.0
     * @access public
     *
     * @param array $args Library arguments.
     *
     * @return array Library data.
     */
    public function getLibraryData(array $args)
    {
        return [
            'templates' => $this->getTemplates(),
        ];
    }

    /**
     * Save template.
     *
     * Save new or update existing template on the database.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return WPError|int The ID of the saved/updated template.
     */
    public function saveTemplate(array $args)
    {
        $validate_args = $this->ensureArgs(['post_id', 'source', 'content', 'type'], $args);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        $source = $this->getSource($args['source']);

        if (!$source) {
            return new WPError('template_error', 'Template source not found.');
        }

        $args['content'] = json_decode($args['content'], true);

        $page = SettingsManager::getSettingsManagers('page')->getModel($args['post_id']);

        $args['page_settings'] = $page->getData('settings');

        $template_id = $source->saveItem($args);

        if (is_wp_error($template_id)) {
            return $template_id;
        }

        return $source->getItem($template_id);
    }

    /**
     * Update template.
     *
     * Update template on the database.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $template_data New template data.
     *
     * @return WPError|Source_Base Template sources instance if the templates
     *                               was updated, `WP_Error` otherwise.
     */
    public function updateTemplate(array $template_data)
    {
        $validate_args = $this->ensureArgs(['source', 'content', 'type'], $template_data);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        $source = $this->getSource($template_data['source']);

        if (!$source) {
            return new WPError('template_error', 'Template source not found.');
        }

        $template_data['content'] = json_decode($template_data['content'], true);

        $update = $source->updateItem($template_data);

        if (is_wp_error($update)) {
            return $update;
        }

        return $source->getItem($template_data['id']);
    }

    /**
     * Update templates.
     *
     * Update template on the database.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return WPError|true True if templates updated, `WP_Error` otherwise.
     */
    public function updateTemplates(array $args)
    {
        foreach ($args['templates'] as $template_data) {
            $result = $this->updateTemplate($template_data);

            if (is_wp_error($result)) {
                return $result;
            }
        }

        return true;
    }

    /**
     * Get template data.
     *
     * Retrieve the template data.
     *
     * @since 1.5.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return WPError|bool|array ??
     */
    public function getTemplateData(array $args)
    {
        $validate_args = $this->ensureArgs(['source', 'template_id'], $args);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        if (isset($args['edit_mode'])) {
            Plugin::$instance->editor->setEditMode($args['edit_mode']);
        }

        $source = $this->getSource($args['source']);

        if (!$source) {
            return new WPError('template_error', 'Template source not found.');
        }

        do_action('elementor/template-library/before_get_source_data', $args, $source);

        $data = $source->getData($args);

        do_action('elementor/template-library/after_get_source_data', $args, $source);

        return $data;
    }

    /**
     * Delete template.
     *
     * Delete template from the database.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return WPPost|WPError|false|null Post data on success, false or null
     *                                       or 'WP_Error' on failure.
     */
    public function deleteTemplate(array $args)
    {
        $validate_args = $this->ensureArgs(['source', 'template_id'], $args);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        $source = $this->getSource($args['source']);

        if (!$source) {
            return new WPError('template_error', 'Template source not found.');
        }

        return $source->deleteTemplate($args['template_id']);
    }

    /**
     * Export template.
     *
     * Export template to a file.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return mixed Whether the export succeeded or failed.
     */
    public function exportTemplate(array $args)
    {
        $validate_args = $this->ensureArgs(['source', 'template_id'], $args);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        $source = $this->getSource($args['source']);

        if (!$source) {
            return new WPError('template_error', 'Template source not found');
        }

        return $source->exportTemplate($args['template_id']);
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function directImportTemplate()
    {
        /** @var SourceLocal $source */
        $source = $this->getSource('local');

        return $source->importTemplate($_FILES['file']['name'], $_FILES['file']['tmp_name']);
    }

    /**
     * Import template.
     *
     * Import template from a file.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $data
     *
     * @return mixed Whether the export succeeded or failed.
     */
    public function importTemplate(array $data)
    {
        /** @var SourceLocal $source */
        $file_content = call_user_func('base64_decode', $data['fileData']);

        $tmp_file = tmpfile();

        fwrite($tmp_file, $file_content);

        $source = $this->getSource('local');

        $result = $source->importTemplate($data['fileName'], stream_get_meta_data($tmp_file)['uri']);

        fclose($tmp_file);

        return $result;
    }

    /**
     * Mark template as favorite.
     *
     * Add the template to the user favorite templates.
     *
     * @since 1.9.0
     * @access public
     *
     * @param array $args Template arguments.
     *
     * @return mixed Whether the template marked as favorite.
     */
    public function markTemplateAsFavorite($args)
    {
        unset($args['editor_post_id']);

        $validate_args = $this->ensureArgs(['source', 'template_id', 'favorite'], $args);

        if (is_wp_error($validate_args)) {
            return $validate_args;
        }

        $source = $this->getSource($args['source']);

        return $source->markAsFavorite($args['template_id'], filter_var($args['favorite'], FILTER_VALIDATE_BOOLEAN));
    }

    /**
     * Register default template sources.
     *
     * Register the 'local' and 'remote' template sources that Elementor use by
     * default.
     *
     * @since 1.0.0
     * @access private
     */
    private function registerDefaultSources()
    {
        $sources = [
            'local',
        ];

        foreach ($sources as $source_filename) {
            $class_name = ucwords($source_filename);
            $class_name = str_replace('-', '_', $class_name);

            $this->registerSource(__NAMESPACE__ . '\TemplateLibraryXSource' . $class_name);
        }
    }

    /**
     * Handle ajax request.
     *
     * Fire authenticated ajax actions for any given ajax request.
     *
     * @since 1.0.0
     * @access private
     *
     * @param string $ajax_request Ajax request.
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    private function handleAjaxRequest($ajax_request, array $data)
    {
        if (!User::isCurrentUserCanEditPostType(SourceLocal::CPT)) {
            throw new \Exception('Access Denied');
        }

        if (!empty($data['editor_post_id'])) {
            $editor_post_id = absint($data['editor_post_id']);

            if (!get_post($editor_post_id)) {
                throw new \Exception(__('Post not found.'));
            }

            Plugin::$instance->db->switchToPost($editor_post_id);
        }

        $result = call_user_func([$this, \Tools::toCamelCase($ajax_request)], $data);

        if (is_wp_error($result)) {
            throw new \Exception($result->getErrorMessage());
        }

        return $result;
    }

    /**
     * Init ajax calls.
     *
     * Initialize template library ajax calls for allowed ajax requests.
     *
     * @since 2.3.0
     * @access public
     *
     * @param Ajax $ajax
     */
    public function registerAjaxActions(Ajax $ajax)
    {
        $library_ajax_requests = [
            'get_library_data',
            'get_template_data',
            'save_template',
            'update_templates',
            'delete_template',
            'import_template',
            'mark_template_as_favorite',
        ];

        foreach ($library_ajax_requests as $ajax_request) {
            $ajax->registerAjaxAction($ajax_request, function ($data) use ($ajax_request) {
                return ${'this'}->handleAjaxRequest($ajax_request, $data);
            });
        }
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function handleDirectActions()
    {
        if (!User::isCurrentUserCanEditPostType(SourceLocal::CPT)) {
            return;
        }

        /** @var Ajax $ajax */
        // $ajax = Plugin::$instance->common->getComponent('ajax');

        // if (!$ajax->verifyRequestNonce()) {
        //     $this->handleDirectActionError('Access Denied');
        // }

        $action = \Tools::toCamelCase(\Tools::getValue('library_action'));

        $result = $this->$action($_REQUEST);

        if (is_wp_error($result)) {
            /** @var WPError $result */
            $this->handleDirectActionError($result->getErrorMessage() . '.');
        }

        $callback = "on{$action}Success";

        if (method_exists($this, $callback)) {
            $this->$callback($result);
        }

        die;
    }

    // private function onDirectImportTemplateSuccess()

    /**
     * @since 2.3.0
     * @access private
     */
    private function handleDirectActionError($message)
    {
        // _default_wp_die_handler($message, 'Elementor Library');
        wp_send_json_error('Elementor Library - ' . $message);
    }

    /**
     * Ensure arguments exist.
     *
     * Checks whether the required arguments exist in the specified arguments.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $required_args  Required arguments to check whether they
     *                              exist.
     * @param array $specified_args The list of all the specified arguments to
     *                              check against.
     *
     * @return WPError|true True on success, 'WP_Error' otherwise.
     */
    private function ensureArgs(array $required_args, array $specified_args)
    {
        $not_specified_args = array_diff($required_args, array_keys(array_filter($specified_args)));

        if ($not_specified_args) {
            return new WPError('arguments_not_specified', 'The required argument(s) `' . implode(', ', $not_specified_args) . '` not specified');
        }

        return true;
    }
}
