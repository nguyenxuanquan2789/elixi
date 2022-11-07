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

use VEC\CoreXFilesXCSSXPost as PostCSS;
use VEC\CoreXSettingsXManager as SettingsManager;
use VEC\CoreXUtilsXExceptions as Exceptions;

/**
 * Elementor document.
 *
 * An abstract class that provides the needed properties and methods to
 * manage and handle documents in inheriting classes.
 *
 * @since 2.0.0
 * @abstract
 */
abstract class CoreXBaseXDocument extends ControlsStack
{
    // const TYPE_META_KEY = '_elementor_template_type';
    const PAGE_META_KEY = '_elementor_page_settings';

    private $main_id;

    private static $properties = [];

    /**
     * Document post data.
     *
     * Holds the document post data.
     *
     * @since 2.0.0
     * @access protected
     *
     * @var WPPost Wrapper post data.
     */
    protected $post;

    /**
     * @since 2.1.0
     * @access protected
     * @static
     */
    protected static function getEditorPanelCategories()
    {
        return Plugin::$instance->elements_manager->getCategories();
    }

    /**
     * Get properties.
     *
     * Retrieve the document properties.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return array Document properties.
     */
    public static function getProperties()
    {
        return [
            'is_editable' => true,
        ];
    }

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function getEditorPanelConfig()
    {
        return [
            'widgets_settings' => [],
            'elements_categories' => static::getEditorPanelCategories(),
            'messages' => [
                /* translators: %s: the document title. */
                'publish_notification' => sprintf(__('Hurray! Your %s is live.'), static::getTitle()),
            ],
        ];
    }

    /**
     * Get element title.
     *
     * Retrieve the element title.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return string Element title.
     */
    public static function getTitle()
    {
        return __('Document');
    }

    /**
     * Get property.
     *
     * Retrieve the document property.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @param string $key The property key.
     *
     * @return mixed The property value.
     */
    public static function getProperty($key)
    {
        $id = static::getClassFullName();

        if (!isset(self::$properties[$id])) {
            self::$properties[$id] = static::getProperties();
        }

        return self::getItems(self::$properties[$id], $key);
    }

    /**
     * @since 2.0.0
     * @access public
     * @static
     */
    public static function getClassFullName()
    {
        return get_called_class();
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getUniqueName()
    {
        return $this->getName() . '-' . $this->post->ID;
    }

    // public function getPostTypeTitle()

    /**
     * @since 2.0.0
     * @access public
     */
    public function getMainId()
    {
        if (!$this->main_id) {
            $post_id = $this->post->ID;

            $parent_post_id = wp_is_post_revision($post_id);

            if ($parent_post_id) {
                $post_id = $parent_post_id;
            }

            $this->main_id = $post_id;
        }

        return $this->main_id;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param $data
     *
     * @throws \Exception If the widget was not found.
     *
     * @return string
     */
    public function renderElement($data)
    {
        // Start buffering
        ob_start();

        /** @var WidgetBase $widget */
        $widget = Plugin::$instance->elements_manager->createElementInstance($data);

        if (!$widget) {
            throw new \Exception('Widget not found.');
        }

        $widget->renderContent();

        $render_html = ob_get_clean();

        return $render_html;
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getMainPost()
    {
        return get_post($this->getMainId());
    }

    public function getContainerAttributes()
    {
        $id = $this->getMainId();

        $attributes = [
            'data-elementor-type' => $this->getName(),
            'data-elementor-id' => $id,
            'class' => 'elementor elementor-' . $id,
        ];

        if (!Plugin::$instance->preview->isPreviewMode($id)) {
            $attributes['data-elementor-settings'] = json_encode($this->getFrontendSettings());
        }

        return $attributes;
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getWpPreviewUrl()
    {
        $main_post_id = $this->getMainId();

        $url = get_preview_post_link(
            $main_post_id,
            [
                // 'preview_id' => $main_post_id,
                // 'preview_nonce' => wp_create_nonce('post_preview_' . $main_post_id),
                'preview' => 'true',
            ]
        );

        /**
         * Document "PrestaShop preview" URL.
         *
         * Filters the PrestaShop preview URL.
         *
         * @since 2.0.0
         *
         * @param string   $url  PrestaShop preview URL.
         * @param Document $this The document instance.
         */
        $url = apply_filters('elementor/document/urls/wp_preview', $url, $this);

        return $url;
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getExitToDashboardUrl()
    {
        $url = get_edit_post_link($this->getMainId(), 'raw');

        /**
         * Document "exit to dashboard" URL.
         *
         * Filters the "Exit To Dashboard" URL.
         *
         * @since 2.0.0
         *
         * @param string   $url  The exit URL
         * @param Document $this The document instance.
         */
        $url = apply_filters('elementor/document/urls/exit_to_dashboard', $url, $this);

        return $url;
    }

    /**
     * Get auto-saved post revision.
     *
     * Retrieve the auto-saved post revision that is newer than current post.
     *
     * @since 2.0.0
     * @access public
     *
     *
     * @return bool|Document
     */

    public function getNewerAutosave()
    {
        $autosave = $this->getAutosave();

        // Detect if there exists an autosave newer than the post.
        if ($autosave && strtotime($autosave->getPost()->post_modified) > strtotime($this->post->post_modified)) {
            return $autosave;
        }

        return false;
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function isAutosave()
    {
        return wp_is_post_autosave($this->post->ID);
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param int  $user_id
     * @param bool $create
     *
     * @return bool|Document
     */
    public function getAutosave($user_id = 0, $create = false)
    {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $autosave_id = $this->getAutosaveId($user_id);

        if ($autosave_id) {
            $document = Plugin::$instance->documents->get($autosave_id);
        } elseif ($create) {
            $autosave_id = wp_create_post_autosave([
                'post_ID' => $this->post->uid->toDefault(),
                'post_type' => $this->post->post_type,
                'post_title' => $this->post->post_title,
                'post_excerpt' => $this->post->post_excerpt,
                // Hack to cause $autosave_is_different=true in `wp_create_post_autosave`.
                'post_content' => '<!-- Created with V-Elements -->',
                'post_modified' => date('Y-m-d H:i:s'),
                'template_type' => $this->post->template_type,
            ]);

            Plugin::$instance->db->copyElementorMeta($this->post->ID, $autosave_id);

            $document = Plugin::$instance->documents->get($autosave_id);
            $document->saveTemplateType();
        } else {
            $document = false;
        }

        return $document;
    }

    // public function filterAdminRowActions($actions)

    /**
     * @since 2.0.0
     * @access public
     */
    public function isEditableByCurrentUser()
    {
        return self::getProperty('is_editable') && User::isCurrentUserCanEdit($this->getMainId());
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function _getInitialConfig()
    {
        return [
            'id' => $this->getMainId(),
            'type' => $this->getName(),
            'version' => $this->getMainMeta('_elementor_version'),
            'remoteLibrary' => $this->getRemoteLibraryConfig(),
            'last_edited' => $this->getLastEdited(),
            'panel' => static::getEditorPanelConfig(),
            'container' => 'body',
            'urls' => [
                'exit_to_dashboard' => $this->getExitToDashboardUrl(),
                'preview' => $this->getPreviewUrl(),
                'wp_preview' => $this->getWpPreviewUrl(),
                'permalink' => $this->getPermalink(),
            ],
        ];
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'document_settings',
            [
                'label' => __('General Settings'),
                'tab' => ControlsManager::TAB_SETTINGS,
            ]
        );

        $statuses = get_post_statuses();

        $this->addControl(
            'post_status',
            [
                'label' => __('Status'),
                'type' => ControlsManager::SELECT,
                'default' => $this->getMainPost()->post_status,
                'options' => $statuses,
            ]
        );

        $this->addControl(
            'post_title',
            [
                'label' => __('Title'),
                'type' => ControlsManager::TEXT,
                'default' => $this->post->post_title,
                'label_block' => true,
                'separator' => 'none',
            ]
        );

        $this->endControlsSection();

        /**
         * Register document controls.
         *
         * Fires after Elementor registers the document controls.
         *
         * @since 2.0.0
         *
         * @param Document $this The document instance.
         */
        do_action('elementor/documents/register_controls', $this);
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param $data
     *
     * @return bool
     */
    public function save($data)
    {
        if (!$this->isEditableByCurrentUser()) {
            return false;
        }

        /**
         * Before document save.
         *
         * Fires when document save starts on Elementor.
         *
         * @since 2.5.12
         *
         * @param \Elementor\Core\Base\Document $this The current document.
         * @param $data.
         */
        do_action('elementor/document/before_save', $this, $data);

        if (isset($data['settings'])) {
            if (DB::STATUS_AUTOSAVE === $data['settings']['post_status']) {
                if (!defined('DOING_AUTOSAVE')) {
                    define('DOING_AUTOSAVE', true);
                }
            }

            $this->saveSettings($data['settings']);

            // Refresh post after save settings.
            $this->post = get_post($this->post->ID);
        }

        if (isset($data['elements'])) {
            $this->saveElements($data['elements']);
        }

        $this->saveTemplateType();

        $this->saveVersion();

        // Create revision
        wp_save_post_revision($this->post);

        // Remove Post CSS
        $post_css = new PostCSS($this->post->ID);

        $post_css->delete();

        /**
         * After document save.
         *
         * Fires when document save is complete.
         *
         * @since 2.5.12
         *
         * @param \Elementor\Core\Base\Document $this The current document.
         * @param $data.
         */
        do_action('elementor/document/after_save', $this, $data);

        return true;
    }

    /**
     * Is built with Elementor.
     *
     * Check whether the post was built with Elementor.
     *
     * @since 2.0.0
     * @access public
     *
     * @return bool Whether the post was built with Elementor.
     */
    public function isBuiltWithElementor()
    {
        return !!get_post_meta($this->post->ID, '_elementor_edit_mode', true);
    }

    // public function getEditUrl()

    /**
     * @since 2.0.0
     * @access public
     */
    public function getPreviewUrl()
    {
        /**
         * Use a static var - to avoid change the `ver` parameter on every call.
         */
        static $url;

        if (empty($url)) {
            // add_filter('pre_option_permalink_structure', '__return_empty_string');

            $url = add_query_arg([
                // 'elementor-preview' => $this->getMainId(),
                'ctx' => \Shop::getContext(),
                'ver' => time(),
            ], $this->getPermalink());

            // remove_filter('pre_option_permalink_structure', '__return_empty_string');

            /**
             * Document preview URL.
             *
             * Filters the document preview URL.
             *
             * @since 2.0.0
             *
             * @param string   $url  The preview URL.
             * @param Document $this The document instance.
             */
            $url = apply_filters('elementor/document/urls/preview', $url, $this);
        }

        return $url;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param string $key
     *
     * @return array
     */
    public function getJsonMeta($key)
    {
        $meta = get_post_meta($this->post->ID, $key, true);

        if (is_string($meta) && !empty($meta)) {
            $meta = json_decode($meta, true);
        }

        if (empty($meta)) {
            $meta = [];
        }

        return $meta;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param null $data
     * @param bool $with_html_content
     *
     * @return array
     */
    public function getElementsRawData($data = null, $with_html_content = false)
    {
        if (is_null($data)) {
            $data = $this->getElementsData();
        }

        // Change the current documents, so widgets can use `documents->get_current` and other post data
        Plugin::$instance->documents->switchToDocument($this);

        $editor_data = [];

        foreach ($data as $element_data) {
            $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

            if (!$element) {
                continue;
            }

            $editor_data[] = $element->getRawData($with_html_content);
        }

        Plugin::$instance->documents->restoreDocument();

        return $editor_data;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param string $status
     *
     * @return array
     */
    public function getElementsData($status = DB::STATUS_PUBLISH)
    {
        $elements = $this->getJsonMeta('_elementor_data');

        if (DB::STATUS_DRAFT === $status) {
            $autosave = $this->getNewerAutosave();

            if (is_object($autosave)) {
                $autosave_elements = Plugin::$instance->documents
                    ->get($autosave->getPost()->ID)
                    ->getJsonMeta('_elementor_data');
            }
        }

        if (Plugin::$instance->editor->isEditMode()) {
            if (empty($elements) && empty($autosave_elements)) {
                // Convert to Elementor.
                $elements = $this->convertToElementor();
                if ($this->isAutosave()) {
                    Plugin::$instance->db->copyElementorMeta($this->post->post_parent, $this->post->ID);
                }
            }
        }

        if (!empty($autosave_elements)) {
            $elements = $autosave_elements;
        }

        return $elements;
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function convertToElementor()
    {
        $this->save([]);

        if (empty($this->post->post_content)) {
            return [];
        }

        $widget_type = Plugin::$instance->widgets_manager->getWidgetTypes('text-editor');
        $settings = [
            'editor' => $this->post->post_content,
        ];

        // TODO: Better coding to start template for editor
        return [
            [
                'id' => Utils::generateRandomString(),
                'elType' => 'section',
                'elements' => [
                    [
                        'id' => Utils::generateRandomString(),
                        'elType' => 'column',
                        'elements' => [
                            [
                                'id' => Utils::generateRandomString(),
                                'elType' => $widget_type::getType(),
                                'widgetType' => $widget_type->getName(),
                                'settings' => $settings,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @since 2.1.3
     * @access public
     */
    public function printElementsWithWrapper($elements_data = null)
    {
        if (!$elements_data) {
            $elements_data = $this->getElementsData();
        }
        ?>
        <div <?= Utils::renderHtmlAttributes($this->getContainerAttributes()) ?>>
            <div class="elementor-inner">
                <div class="elementor-section-wrap">
                    <?php $this->printElements($elements_data) ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getCssWrapperSelector()
    {
        return '';
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getPanelPageSettings()
    {
        return [
            /* translators: %s: Document title */
            'title' => sprintf(__('%s Settings'), static::getTitle()),
        ];
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getPermalink()
    {
        return get_preview_post_link($this->getMainId());
    }

    /**
     * @since 2.0.8
     * @access public
     */
    public function getContent($with_css = false)
    {
        return Plugin::$instance->frontend->getBuilderContent($this->post->ID, $with_css);
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function delete()
    {
        if ('revision' === $this->post->post_type) {
            $deleted = wp_delete_post_revision($this->post);
        } else {
            $deleted = wp_delete_post($this->post->ID);
        }

        return $deleted && !is_wp_error($deleted);
    }

    /**
     * Save editor elements.
     *
     * Save data from the editor to the database.
     *
     * @since 2.0.0
     * @access protected
     *
     * @param array $elements
     */
    protected function saveElements($elements)
    {
        $editor_data = $this->getElementsRawData($elements);

        // We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
        // $json_value = wp_slash(wp_json_encode($editor_data));

        $is_meta_updated = update_post_meta($this->post->ID, '_elementor_data', $editor_data);

        /**
         * Before saving data.
         *
         * Fires before Elementor saves data to the database.
         *
         * @since 1.0.0
         *
         * @param string   $status          Post status.
         * @param int|bool $is_meta_updated Meta ID if the key didn't exist, true on successful update, false on failure.
         */
        do_action('elementor/db/before_save', $this->post->post_status, $is_meta_updated);

        Plugin::$instance->db->savePlainText($this->post->ID);

        /**
         * After saving data.
         *
         * Fires after Elementor saves data to the database.
         *
         * @since 1.0.0
         *
         * @param int   $post_id     The ID of the post.
         * @param array $editor_data Sanitize posted data.
         */
        do_action('elementor/editor/after_save', $this->post->ID, $editor_data);
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param int $user_id Optional. User ID. Default value is `0`.
     *
     * @return bool|int
     */
    public function getAutosaveId($user_id = 0)
    {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }

        $autosave = Utils::getPostAutosave($this->post->ID, $user_id);
        if ($autosave) {
            return $autosave->ID;
        }

        return false;
    }

    public function saveVersion()
    {
        if (!defined('IS_ELEMENTOR_UPGRADE')) {
            // Save per revision.
            $this->updateMeta('_elementor_version', _VEC_VERSION_);

            // Save update date if needed
            if (!$this->post->post_modified) {
                $this->updateMeta('_ce_date_upd', date('Y-m-d H:i:s'));
            }

            /**
             * Document version save.
             *
             * Fires when document version is saved on Elementor.
             * Will not fire during Elementor Upgrade.
             *
             * @since 2.5.12
             *
             * @param \Elementor\Core\Base\Document $this The current document.
             *
             */
            do_action('elementor/document/save_version', $this);
        }
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function saveTemplateType()
    {
        $template_type = $this->getName();

        if ($template_type !== $this->post->template_type && (UId::TEMPLATE >= $this->post->uid->id_type || UId::THEME === $this->post->uid->id_type)) {
            $this->post->template_type = $template_type;

            $this->post->_obj->setFieldsToUpdate([
                'type' => true,
            ]);
            return $this->post->_obj->update();
        }
        // return $this->updateMainMeta(self::TYPE_META_KEY, $this->getName());
        return true;
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function getTemplateType()
    {
        // return $this->getMainMeta(self::TYPE_META_KEY);
        return $this->post->template_type;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param string $key Meta data key.
     *
     * @return mixed
     */
    public function getMainMeta($key)
    {
        return get_post_meta($this->getMainId(), $key, true);
    }

    /**
     * @since 2.0.4
     * @access public
     *
     * @param string $key   Meta data key.
     * @param string $value Meta data value.
     *
     * @return bool|int
     */
    public function updateMainMeta($key, $value)
    {
        return update_post_meta($this->getMainId(), $key, $value);
    }

    /**
     * @since 2.0.4
     * @access public
     *
     * @param string $key   Meta data key.
     * @param string $value Optional. Meta data value. Default is an empty string.
     *
     * @return bool
     */
    public function deleteMainMeta($key, $value = '')
    {
        return delete_post_meta($this->getMainId(), $key, $value);
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param string $key Meta data key.
     *
     * @return mixed
     */
    public function getMeta($key)
    {
        return get_post_meta($this->post->ID, $key, true);
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param string $key   Meta data key.
     * @param mixed  $value Meta data value.
     *
     * @return bool|int
     */
    public function updateMeta($key, $value)
    {
        return update_post_meta($this->post->ID, $key, $value);
    }

    /**
     * @since 2.0.3
     * @access public
     *
     * @param string $key   Meta data key.
     * @param string $value Meta data value.
     *
     * @return bool
     */
    public function deleteMeta($key, $value = '')
    {
        return delete_post_meta($this->post->ID, $key, $value);
    }

    /**
     * @since 2.0.0
     * @access public
     */
    public function getLastEdited()
    {
        $post = $this->post;
        $autosave_post = $this->getAutosave();

        if ($autosave_post) {
            $post = $autosave_post->getPost();
        }

        $date = \Tools::displayDate($post->post_modified, null, true);
        $author = get_user_by('id', $post->post_author);
        $display_name = isset($author->display_name) ? $author->display_name : __('Unknown');

        if ($autosave_post || 'revision' === $post->post_type) {
            /* translators: 1: Saving date, 2: Author display name */
            $last_edited = sprintf(__('Draft saved on %1$s by %2$s'), '<time>' . $date . '</time>', $display_name);
        } else {
            /* translators: 1: Editing date, 2: Author display name */
            $last_edited = sprintf(__('Last edited on %1$s by %2$s'), '<time>' . $date . '</time>', $display_name);
        }

        return $last_edited;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $data
     *
     * @throws \Exception If the post does not exist.
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            if (empty($data['post_id'])) {
                $this->post = new WPPost((object) []);
            } else {
                $this->post = get_post($data['post_id']);

                if (!$this->post) {
                    throw new \Exception(sprintf('Post ID #%s does not exist.', $data['post_id']), Exceptions::NOT_FOUND);
                }
            }

            // Each Control_Stack is based on a unique ID.
            $data['id'] = $data['post_id'];

            if (!isset($data['settings'])) {
                $data['settings'] = [];
            }

            $saved_settings = get_post_meta($this->post->ID, '_elementor_page_settings', true);
            if (!empty($saved_settings) && is_array($saved_settings)) {
                $data['settings'] += $saved_settings;
            }
        }

        parent::__construct($data);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = [
            'type' => 'block',
            'category' => $this->getName(),
            'autoImportSettings' => false,
        ];

        return $config;
    }

    /**
     * @since 2.0.4
     * @access protected
     *
     * @param $settings
     */
    protected function saveSettings($settings)
    {
        $page_settings_manager = SettingsManager::getSettingsManagers('page');
        $page_settings_manager->ajaxBeforeSaveSettings($settings, $this->post->ID);
        $page_settings_manager->saveSettings($settings, $this->post->ID);
    }

    /**
     * @since 2.1.3
     * @access protected
     */
    protected function printElements($elements_data)
    {
        foreach ($elements_data as $element_data) {
            $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

            if (!$element) {
                continue;
            }

            $element->printElement();
        }
    }
}
