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
 * Elementor template library remote source.
 *
 * Elementor template library remote source handler class is responsible for
 * handling remote templates from Elementor.com servers.
 *
 * @since 1.0.0
 */
class TemplateLibraryXSourceRemote extends TemplateLibraryXSourceBase
{
    /**
     * Get remote template ID.
     *
     * Retrieve the remote template ID.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The remote template ID.
     */
    public function getId()
    {
        return 'remote';
    }

    /**
     * Get remote template title.
     *
     * Retrieve the remote template title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The remote template title.
     */
    public function getTitle()
    {
        return __('Remote');
    }

    // public function registerData();

    /**
     * Get remote templates.
     *
     * Retrieve remote templates from Elementor.com servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args Optional. Nou used in remote source.
     *
     * @return array Remote templates.
     */
    public function getItems($args = [])
    {
        $library_data = Api::getLibraryData();

        $templates = [];

        if (!empty($library_data['templates'])) {
            foreach ($library_data['templates'] as $template_data) {
                $templates[] = $this->prepareTemplate($template_data);
            }
        }

        return $templates;
    }

    /**
     * Get remote template.
     *
     * Retrieve a single remote template from Elementor.com servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $template_id The template ID.
     *
     * @return array Remote template.
     */
    public function getItem($template_id)
    {
        $templates = $this->getItems();

        return $templates[$template_id];
    }

    /**
     * Save remote template.
     *
     * Remote template from Elementor.com servers cannot be saved on the
     * database as they are retrieved from remote servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $template_data Remote template data.
     *
     * @return WPError
     */
    public function saveItem($template_data)
    {
        return new WPError('invalid_request', 'Cannot save template to a remote source');
    }

    /**
     * Update remote template.
     *
     * Remote template from Elementor.com servers cannot be updated on the
     * database as they are retrieved from remote servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $new_data New template data.
     *
     * @return WPError
     */
    public function updateItem($new_data)
    {
        return new WPError('invalid_request', 'Cannot update template to a remote source');
    }

    /**
     * Delete remote template.
     *
     * Remote template from Elementor.com servers cannot be deleted from the
     * database as they are retrieved from remote servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $template_id The template ID.
     *
     * @return WPError
     */
    public function deleteTemplate($template_id)
    {
        return new WPError('invalid_request', 'Cannot delete template from a remote source');
    }

    /**
     * Export remote template.
     *
     * Remote template from Elementor.com servers cannot be exported from the
     * database as they are retrieved from remote servers.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $template_id The template ID.
     *
     * @return WPError
     */
    public function exportTemplate($template_id)
    {
        return new WPError('invalid_request', 'Cannot export template from a remote source');
    }

    /**
     * Get remote template data.
     *
     * Retrieve the data of a single remote template from Elementor.com servers.
     *
     * @since 1.5.0
     * @access public
     *
     * @param array  $args    Custom template arguments.
     * @param string $context Optional. The context. Default is `display`.
     *
     * @return array Remote Template data.
     */
    public function getData(array $args, $context = 'display')
    {
        $data = Api::getTemplateContent($args['template_id']);

        if (is_wp_error($data)) {
            return $data;
        }

        $data['content'] = $this->replaceElementsIds($data['content']);
        $data['content'] = $this->processExportImportContent($data['content'], 'onImport');

        $post_id = $args['editor_post_id'];
        $document = Plugin::$instance->documents->get($post_id);
        if ($document) {
            $data['content'] = $document->getElementsRawData($data['content'], true);
        }

        return $data;
    }

    /**
     * @since 2.2.0
     * @access private
     */
    private function prepareTemplate(array $template_data)
    {
        $favorite_templates = $this->getUserMeta('favorites');

        return [
            'template_id' => $template_data['id'],
            'source' => $this->getId(),
            'type' => $template_data['type'],
            'subtype' => $template_data['subtype'],
            'title' => $template_data['title'],
            'thumbnail' => $template_data['thumbnail'],
            'date' => $template_data['tmpl_created'],
            'author' => $template_data['author'],
            'tags' => json_decode($template_data['tags']),
            'isPro' => ('1' === $template_data['is_pro']),
            'popularityIndex' => (int) $template_data['popularity_index'],
            'trendIndex' => (int) $template_data['trend_index'],
            'hasPageSettings' => ('1' === $template_data['has_page_settings']),
            'url' => $template_data['url'],
            'favorite' => !empty($favorite_templates[$template_data['id']]),
        ];
    }
}
