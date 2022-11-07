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

use VEC\CoreXFilesXCSSXBase as Base;
use VEC\CoreXFilesXCSSXPost as Post;
use VEC\CoreXFilesXCSSXPostPreview as PostPreview;
use VEC\CoreXSettingsXBaseXManager as BaseManager;
use VEC\CoreXSettingsXBaseXModel as BaseModel;
use VEC\CoreXSettingsXManager as SettingsManager;
use VEC\CoreXUtilsXExceptions as Exceptions;

/**
 * Elementor page settings manager.
 *
 * Elementor page settings manager handler class is responsible for registering
 * and managing Elementor page settings managers.
 *
 * @since 1.6.0
 */
class CoreXSettingsXPageXManager extends BaseManager
{
    /**
     * Meta key for the page settings.
     */
    const META_KEY = '_elementor_page_settings';

    /**
     * Get manager name.
     *
     * Retrieve page settings manager name.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string Manager name.
     */
    public function getName()
    {
        return 'page';
    }

    /**
     * Get model for config.
     *
     * Retrieve the model for settings configuration.
     *
     * @since 1.6.0
     * @access public
     *
     * @return BaseModel The model object.
     */
    public function getModelForConfig()
    {
        if (!is_singular() && !Plugin::$instance->editor->isEditMode()) {
            return null;
        }

        if (Plugin::$instance->editor->isEditMode()) {
            $post_id = Plugin::$instance->editor->getPostId();
            $document = Plugin::$instance->documents->getDocOrAutoSave($post_id);
        } else {
            $post_id = get_the_ID();
            $document = Plugin::$instance->documents->getDocForFrontend($post_id);
        }

        if (!$document) {
            return null;
        }

        $model = $this->getModel($document->getPost()->ID);

        if ($document->isAutosave()) {
            $model->setSettings('post_status', $document->getMainPost()->post_status);
        }

        return $model;
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
     *
     * @throws \Exception If invalid post returned using the `$id`.
     * @throws \Exception If current user don't have permissions to edit the post.
     */
    public function ajaxBeforeSaveSettings(array $data, $id)
    {
        $post = get_post($id);

        if (empty($post)) {
            throw new \Exception('Invalid post.', Exceptions::NOT_FOUND);
        }

        if (!current_user_can('edit', $id)) {
            throw new \Exception('Access denied.', Exceptions::FORBIDDEN);
        }

        // Avoid save empty post title.
        if (!empty($data['post_title'])) {
            $post->post_title = $data['post_title'];
        }

        // if (isset($data['post_excerpt']) && post_type_supports($post->post_type, 'excerpt')) {
        //     $post->post_excerpt = $data['post_excerpt'];
        // }

        if (isset($data['post_status'])) {
            // $this->savePostStatus($id, $data['post_status']);
            // unset($post->post_status);
            $post->post_status = $data['post_status'];
        }

        wp_update_post($post);

        // Check updated status
        // if (DB::STATUS_PUBLISH === get_post_status($id))
        $autosave = wp_get_post_autosave($post->ID);

        if ($autosave) {
            wp_delete_post_revision($autosave->ID);
        }

        if (isset($data['post_featured_image'])) {
            if (empty($data['post_featured_image']['url'])) {
                delete_post_meta($post->ID, '_og_image');
            } else {
                update_post_meta($post->ID, '_og_image', $data['post_featured_image']['url']);
            }
        }

        if (Utils::isCptCustomTemplatesSupported($post->uid)) {
            $template = get_post_meta($post->ID, '_wp_page_template', true);

            if (isset($data['template'])) {
                $template = $data['template'];
            }

            if (empty($template)) {
                $template = 'default';
            }

            update_post_meta($post->ID, '_wp_page_template', $template);
        }
    }

    /**
     * Save settings to DB.
     *
     * Save page settings to the database, as post meta data.
     *
     * @since 1.6.0
     * @access protected
     *
     * @param array $settings Settings.
     * @param int   $id       Post ID.
     */
    protected function saveSettingsToDb(array $settings, $id)
    {
        if (!empty($settings)) {
            update_post_meta($id, self::META_KEY, $settings);
        } else {
            delete_post_meta($id, self::META_KEY);
        }
    }

    /**
     * Get CSS file for update.
     *
     * Retrieve the CSS file before updating it.
     *
     * This method overrides the parent method to disallow updating CSS files for pages.
     *
     * @since 1.6.0
     * @access protected
     *
     * @param int $id Post ID.
     *
     * @return false Disallow The updating CSS files for pages.
     */
    protected function getCssFileForUpdate($id)
    {
        return false;
    }

    /**
     * Get saved settings.
     *
     * Retrieve the saved settings from the post meta.
     *
     * @since 1.6.0
     * @access protected
     *
     * @param int $id Post ID.
     *
     * @return array Saved settings.
     */
    protected function getSavedSettings($id)
    {
        $settings = get_post_meta($id, self::META_KEY, true);

        if (!$settings) {
            $settings = [];
        }

        if (Utils::isCptCustomTemplatesSupported(uidval($id))) {
            $saved_template = get_post_meta($id, '_wp_page_template', true);

            if ($saved_template) {
                $settings['template'] = $saved_template;
            }
        }

        return $settings;
    }

    /**
     * Get CSS file name.
     *
     * Retrieve CSS file name for the page settings manager.
     *
     * @since 1.6.0
     * @access protected
     *
     * @return string CSS file name.
     */
    protected function getCssFileName()
    {
        return 'post';
    }

    /**
     * Get model for CSS file.
     *
     * Retrieve the model for the CSS file.
     *
     * @since 1.6.0
     * @access protected
     *
     * @param Base $css_file The requested CSS file.
     *
     * @return BaseModel The model object.
     */
    protected function getModelForCssFile(Base $css_file)
    {
        if (!$css_file instanceof Post) {
            return null;
        }

        $post_id = $css_file->getPostId();

        if ($css_file instanceof PostPreview) {
            $autosave = Utils::getPostAutosave($post_id);
            if ($autosave) {
                $post_id = $autosave->ID;
            }
        }

        return $this->getModel($post_id);
    }

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
        return [
            'id',
            'post_title',
            'post_status',
            'template',
            'post_excerpt',
            'post_featured_image',
            // Do not save:
            'action',
            '_nonce',
            'section_page_settings',
            'section_page_style',
            'section_custom_css',
            'template_default_description',
            'template_canvas_description',
        ];
    }

    // public function savePostStatus($post_id, $status)
}
