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
use VEC\CoreXResponsiveXResponsive as Responsive;
use VEC\CoreXSettingsXManager as SettingsManager;
use VEC\TemplateLibraryXSourceLocal as SourceLocal;

/**
 * Elementor editor.
 *
 * Elementor editor handler class is responsible for initializing Elementor
 * editor and register all the actions needed to display the editor.
 *
 * @since 1.0.0
 */
class Editor
{
    /**
     * User capability required to access Elementor editor.
     */
    const EDITING_CAPABILITY = 'edit_posts';

    /**
     * Post ID.
     *
     * Holds the ID of the current post being edited.
     *
     * @since 1.0.0
     * @access private
     *
     * @var int Post ID.
     */
    private $_post_id;

    /**
     * Whether the edit mode is active.
     *
     * Used to determine whether we are in edit mode.
     *
     * @since 1.0.0
     * @access private
     *
     * @var bool Whether the edit mode is active.
     */
    private $_is_edit_mode;

    /**
     * Init.
     *
     * Initialize Elementor editor. Registers all needed actions to run Elementor,
     * removes conflicting actions etc.
     *
     * Fired by `admin_action_elementor` action.
     *
     * @since 1.0.0
     * @access public
     *
     * @param bool $die Optional. Whether to die at the end. Default is `true`.
     */
    public function init($die = true)
    {
        if (empty($_REQUEST['uid'])) {
            // WPCS: CSRF ok.
            return;
        }

        $this->_post_id = UId::parse($_REQUEST['uid']);

        if (!$this->isEditMode($this->_post_id)) {
            return;
        }

        /*
        // Send MIME Type header like WP admin-header.
        @header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

        // Use requested id and not the global in order to avoid conflicts with plugins that changes the global post.
        query_posts([
            'p' => $this->_post_id,
            'post_type' => get_post_type($this->_post_id),
        ]);
        */

        Plugin::$instance->db->switchToPost($this->_post_id);

        $document = Plugin::$instance->documents->get($this->_post_id);

        Plugin::$instance->documents->switchToDocument($document);

        /*
        add_filter('show_admin_bar', '__return_false');

        // Remove all actions
        remove_all_actions('wp_head');
        remove_all_actions('wp_print_styles');
        remove_all_actions('wp_print_head_scripts');
        remove_all_actions('wp_footer');
        */

        // Handle `wp_head`
        add_action('wp_head', 'wp_enqueue_scripts', 1);
        add_action('wp_head', 'wp_print_styles', 8);
        add_action('wp_head', 'wp_print_head_scripts', 9);
        // add_action('wp_head', 'wp_site_icon');
        add_action('wp_head', [$this, 'editor_head_trigger'], 30);

        // Handle `wp_footer`
        add_action('wp_footer', 'wp_print_footer_scripts', 20);
        // add_action('wp_footer', 'wp_auth_check_html', 30);
        add_action('wp_footer', [$this, 'wp_footer']);

        /*
        // Handle `wp_enqueue_scripts`
        remove_all_actions('wp_enqueue_scripts');

        // Also remove all scripts hooked into after_wp_tiny_mce.
        remove_all_actions('after_wp_tiny_mce');
        */

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 999999);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles'], 999999);

        // Change mode to Builder only on update
        // Plugin::$instance->db->setIsElementorPage($this->_post_id);

        // Post Lock
        if (!$this->getLockedUser($this->_post_id)) {
            $this->lockPost($this->_post_id);
        }

        // Setup default heartbeat options
        add_filter('heartbeat_settings', function ($settings) {
            $settings['interval'] = 15;
            return $settings;
        });

        // Tell to Cache plugins do not cache this request.
        // Utils::doNotCache();

        do_action('elementor/editor/init');

        $this->printEditorTemplate();

        // From the action it's an empty string, from tests its `false`
        if (false !== $die) {
            die;
        }
    }

    /**
     * Retrieve post ID.
     *
     * Get the ID of the current post.
     *
     * @since 1.8.0
     * @access public
     *
     * @return int Post ID.
     */
    public function getPostId()
    {
        return $this->_post_id;
    }

    // public function redirectToNewUrl()

    /**
     * Whether the edit mode is active.
     *
     * Used to determine whether we are in the edit mode.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $post_id Optional. Post ID. Default is `null`, the current
     *                     post ID.
     *
     * @return bool Whether the edit mode is active.
     */
    public function isEditMode($post_id = null)
    {
        if (null !== $this->_is_edit_mode) {
            return $this->_is_edit_mode;
        }

        if (empty($post_id)) {
            $post_id = $this->_post_id;
        }

        $document = Plugin::$instance->documents->get($post_id);

        if (!$document || !$document->isEditableByCurrentUser()) {
            return false;
        }

        if ('AdminVECEditor' === \Tools::getValue('controller') && !\Tools::getIsset('ajax')) {
            return true;
        }

        // Ajax request as Editor mode
        $actions = [
            'elementor',

            // Templates
            'elementor_get_templates',
            'elementor_save_template',
            'elementor_get_template',
            'elementor_delete_template',
            'elementor_import_template',
            'elementor_library_direct_actions',
        ];

        if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $actions)) {
            return true;
        }

        return false;
    }

    /**
     * Lock post.
     *
     * Mark the post as currently being edited by the current user.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $post_id The ID of the post being edited.
     */
    public function lockPost($post_id)
    {
        // if (!function_exists('wp_set_post_lock')) {
        //     require_once ABSPATH . 'wp-admin/includes/post.php';
        // }

        wp_set_post_lock($post_id);
    }

    /**
     * Get locked user.
     *
     * Check what user is currently editing the post.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $post_id The ID of the post being edited.
     *
     * @return \WP_User|false User information or false if the post is not locked.
     */
    public function getLockedUser($post_id)
    {
        // if (!function_exists('wp_check_post_lock')) {
        //     require_once ABSPATH . 'wp-admin/includes/post.php';
        // }

        $locked_user = wp_check_post_lock($post_id);
        if (!$locked_user) {
            return false;
        }

        return get_user_by('id', $locked_user);
    }

    /**
     * Print Editor Template.
     *
     * Include the wrapper template of the editor.
     *
     * @since 2.2.0
     * @access public
     */
    public function printEditorTemplate()
    {
        include 'editor-templates/editor-wrapper.php';
    }

    /**
     * Enqueue scripts.
     *
     * Registers all the editor scripts and enqueues them.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueScripts()
    {
        // remove_action('wp_enqueue_scripts', [$this, __FUNCTION__], 999999);

        // Set the global data like $post, $authordata and etc
        setup_postdata($this->_post_id);

        // global $wp_styles, $wp_scripts;

        $plugin = Plugin::$instance;

        // Reset global variable
        // $wp_styles = new \WPStyles(); // WPCS: override ok.
        // $wp_scripts = new \WPScripts(); // WPCS: override ok.

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        wp_register_script(
            'elementor-editor-modules',
            _VEC_ASSETS_URL_ . 'js/editor-modules' . $suffix . '.js',
            [
                'elementor-common-modules',
            ],
            _VEC_VERSION_,
            true
        );
        // Hack for waypoint with editor mode.
        wp_register_script(
            'elementor-waypoints',
            _VEC_ASSETS_URL_ . 'lib/waypoints/waypoints-for-editor.js',
            [
                'jquery',
            ],
            '4.0.2',
            true
        );

        wp_register_script(
            'perfect-scrollbar',
            _VEC_ASSETS_URL_ . 'lib/perfect-scrollbar/js/perfect-scrollbar' . $suffix . '.js',
            [],
            '1.4.0',
            true
        );

        wp_register_script(
            'jquery-easing',
            _VEC_ASSETS_URL_ . 'lib/jquery-easing/jquery-easing' . $suffix . '.js',
            [
                'jquery',
            ],
            '1.3.2',
            true
        );

        wp_register_script(
            'nprogress',
            _VEC_ASSETS_URL_ . 'lib/nprogress/nprogress' . $suffix . '.js',
            [],
            '0.2.0',
            true
        );

        wp_register_script(
            'tipsy',
            _VEC_ASSETS_URL_ . 'lib/tipsy/tipsy' . $suffix . '.js',
            [
                'jquery',
            ],
            '1.0.0',
            true
        );

        wp_register_script(
            'imagesloaded',
            _VEC_ASSETS_URL_ . 'lib/imagesloaded/imagesloaded' . $suffix . '.js',
            [
                'jquery',
            ],
            '4.1.0',
            true
        );

        wp_register_script(
            'heartbeat',
            _VEC_ASSETS_URL_ . 'lib/heartbeat/heartbeat' . $suffix . '.js',
            [
                'jquery',
            ],
            '5.5',
            true
        );
        wp_localize_script(
            'heartbeat',
            'heartbeatSettings',
            apply_filters('heartbeat_settings', [
                'ajaxurl' => Helper::getAjaxLink(),
            ])
        );

        wp_register_script(
            'jquery-elementor-select2',
            _VEC_ASSETS_URL_ . 'lib/e-select2/js/e-select2.full' . $suffix . '.js',
            [
                'jquery',
            ],
            '4.0.6-rc.1',
            true
        );

        wp_register_script(
            'flatpickr',
            _VEC_ASSETS_URL_ . 'lib/flatpickr/flatpickr' . $suffix . '.js',
            [
                'jquery',
            ],
            '1.12.0',
            true
        );

        wp_register_script(
            'ace',
            'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ace.js',
            [],
            '1.2.5',
            true
        );

        wp_register_script(
            'ace-language-tools',
            'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ext-language_tools.js',
            [
                'ace',
            ],
            '1.2.5',
            true
        );

        wp_register_script(
            'jquery-hover-intent',
            _VEC_ASSETS_URL_ . 'lib/jquery-hover-intent/jquery-hover-intent' . $suffix . '.js',
            [],
            '1.0.0',
            true
        );

        wp_register_script(
            'nouislider',
            _VEC_ASSETS_URL_ . 'lib/nouislider/nouislider' . $suffix . '.js',
            [],
            '13.0.0',
            true
        );

        wp_register_script(
            'elementor-editor',
            _VEC_ASSETS_URL_ . 'js/editor' . $suffix . '.js',
            [
                'elementor-common',
                'elementor-editor-modules',
                // 'wp-auth-check',
                'jquery-ui-sortable',
                'jquery-ui-resizable',
                'perfect-scrollbar',
                'nprogress',
                'tipsy',
                'imagesloaded',
                'heartbeat',
                'jquery-elementor-select2',
                'flatpickr',
                'ace',
                'ace-language-tools',
                'jquery-hover-intent',
                'nouislider',
            ],
            _VEC_VERSION_,
            true
        );

        /**
         * Before editor enqueue scripts.
         *
         * Fires before Elementor editor scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/before_enqueue_scripts');

        $document = Plugin::$instance->documents->getDocOrAutoSave($this->_post_id);

        // Get document data *after* the scripts hook - so plugins can run compatibility before get data, but *before* enqueue the editor script - so elements can enqueue their own scripts that depended in editor script.
        $editor_data = $document->getElementsRawData(null, true);

        // Tweak for WP Admin menu icons
        // wp_print_styles('editor-buttons');

        $locked_user = $this->getLockedUser($this->_post_id);

        if ($locked_user) {
            $locked_user = $locked_user->display_name;
        }

        $page_title_selector = get_option('elementor_page_title_selector');

        if (empty($page_title_selector)) {
            $page_title_selector = 'h1.entry-title';
        }

        $post_type_object = get_post_type_object($document->getMainPost()->post_type);
        $current_user_can_publish = current_user_can($post_type_object->cap->publish_posts);

        $config = [
            'version' => _VEC_VERSION_,
            'home_url' => home_url(),
            'data' => $editor_data,
            // @TODO: `post_id` is bc since 2.0.0
            'post_id' => "{$this->_post_id}",
            'document' => $document->getConfig(),
            'autosave_interval' => AUTOSAVE_INTERVAL,
            'current_user_can_publish' => $current_user_can_publish,
            'controls' => $plugin->controls_manager->getControlsData(),
            'elements' => $plugin->elements_manager->getElementTypesConfig(),
            'widgets' => $plugin->widgets_manager->getWidgetTypesConfig(),
            'schemes' => [
                'items' => $plugin->schemes_manager->getRegisteredSchemesData(),
                'enabled_schemes' => SchemesManager::getEnabledSchemes(),
            ],
            'default_schemes' => $plugin->schemes_manager->getSchemesDefaults(),
            'settings' => SettingsManager::getSettingsManagersConfig(),
            'system_schemes' => $plugin->schemes_manager->getSystemSchemes(),
            'wp_editor' => $this->getWpEditorConfig(),
            'settings_page_link' => Helper::getSettingsLink(),
            'is_active' => true,
            'elementor_site' => __('https://themevec.com'),
            'help_the_content_url' => 'https://elementor.com/help/the-content-area-was-not-found-error/?utm_source=editor-panel&utm_medium=wp-dash&utm_campaign=learn',
            'help_preview_error_url' => 'https://elementor.com/help/how-to-fix-the-preview-could-not-be-loaded-error/?utm_source=editor-panel&utm_medium=wp-dash&utm_campaign=learn',
            'help_right_click_url' => 'https://elementor.com/help/right-click/?utm_source=right-click&utm_medium=wp-dash&utm_campaign=learn',
            'help_flexbox_bc_url' => 'https://elementor.com/help/elementors-new-flexbox-features/?utm_source=flexbox-widget-bc&utm_medium=wp-dash&utm_campaign=learn',
            'additional_shapes' => Shapes::getAdditionalShapesForConfig(),
            'locked_user' => $locked_user,
            'user' => [
                // 'restrictions' => $plugin->role_manager->getUserRestrictionsArray(),
                'restrictions' => [],
                'is_administrator' => current_user_can('manage_options'),
                'introduction' => User::getIntroductionMeta(),
            ],
            // @deprecated since 2.3.0 - Use `elementorCommon.config.isRTL` instead
            'is_rtl' => is_rtl(),
            'locale' => get_locale(),
            'rich_editing_enabled' => filter_var(get_user_meta(get_current_user_id(), 'rich_editing', true), FILTER_VALIDATE_BOOLEAN),
            'page_title_selector' => $page_title_selector,
            // 'tinymceHasCustomConfig' => class_exists('Tinymce_Advanced'),
            'inlineEditing' => Plugin::$instance->widgets_manager->getInlineEditingConfig(),
            // 'dynamicTags' => Plugin::$instance->dynamic_tags->getConfig(),
            'i18n' => [
                'elementor' => __('Elementor'),
                'delete' => __('Delete'),
                'cancel' => __('Cancel'),
                'got_it' => __('Got It'),

                /* translators: %s: Element name. */
                'edit_element' => __('Edit %s'),
                'flexbox_attention_header' => __('Note: Flexbox Changes'),
                'flexbox_attention_message' => __('V-Elements 2.5 introduces key changes to the layout using CSS Flexbox. Your existing pages might have been affected, please review your page before publishing.'),

                // Menu.
                'about_elementor' => __('About V-Elements'),
                'color_picker' => __('Color Picker'),
                'elementor_settings' => __('Module Settings'),
                'global_colors' => __('Default Colors'),
                'global_fonts' => __('Default Fonts'),
                'global_style' => __('Style'),
                'settings' => __('Settings'),
                'go_to' => __('Go To'),
                'view_page' => __('View Page'),
                'exit_to_dashboard' => __('Exit to Back-office'),

                // Elements.
                'inner_section' => __('Inner Section'),

                // Control Order.
                'asc' => __('Ascending order'),
                'desc' => __('Descending order'),

                // Clear Page.
                'clear_page' => __('Delete All Content'),
                'dialog_confirm_clear_page' => __('Attention: We are going to DELETE ALL CONTENT from this page. Are you sure you want to do that?'),

                // Panel Preview Mode.
                'back_to_editor' => __('Show Panel'),
                'preview' => __('Hide Panel'),

                // Inline Editing.
                'type_here' => __('Type Here'),

                // Library.
                'an_error_occurred' => __('An error occurred'),
                'category' => __('Category'),
                'delete_template' => __('Delete Template'),
                'delete_template_confirm' => __('Are you sure you want to delete this template?'),
                'import_template_dialog_header' => __('Import Document Settings'),
                'import_template_dialog_message' => __('Do you want to also import the document settings of the template?'),
                'import_template_dialog_message_attention' => __('Attention: Importing may override previous settings.'),
                'library' => __('Library'),
                'no' => __('No'),
                'page' => __('Page'),
                /* translators: %s: Template type. */
                'save_your_template' => __('Save Your %s to Library'),
                'save_your_template_description' => __('Your designs will be available for export and reuse on any page or website'),
                'section' => __('Section'),
                'templates_empty_message' => __('This is where your templates should be. Design it. Save it. Reuse it.'),
                'templates_empty_title' => __('Haven’t Saved Templates Yet?'),
                'templates_no_favorites_message' => __('You can mark any pre-designed template as a favorite.'),
                'templates_no_favorites_title' => __('No Favorite Templates'),
                'templates_no_results_message' => __('Please make sure your search is spelled correctly or try a different words.'),
                'templates_no_results_title' => __('No Results Found'),
                'templates_request_error' => __('The following error(s) occurred while processing the request:'),
                'yes' => __('Yes'),
                'my_templates' => __('Saved Templates'),

                // Incompatible Device.
                'device_incompatible_header' => __('Your browser isn\'t compatible'),
                'device_incompatible_message' => __('Your browser isn\'t compatible with all of V-Elements\' editing features. We recommend you switch to another browser like Chrome or Firefox.'),
                'proceed_anyway' => __('Proceed Anyway'),

                // Preview not loaded.
                'learn_more' => __('Learn More'),
                'preview_el_not_found_header' => __('Sorry, the content area was not found in your page.'),
                'preview_el_not_found_message' => __('You must insert the selected module position (hook) in the current template, in order for VecElements to work on this page.'),
                'preview_not_loading_header' => __('The preview could not be loaded'),
                'preview_not_loading_message' => __('We\'re sorry, but something went wrong. Click on \'Learn more\' and follow each of the steps to quickly solve it.'),

                // Take Over.
                /* translators: %s: User name. */
                'dialog_user_taken_over' => __('%s has taken over and is currently editing. Do you want to take over this page editing?'),
                'go_back' => __('Go Back'),
                'take_over' => __('Take Over'),

                // Revisions.
                /* translators: %s: Element type. */
                'delete_element' => __('Delete %s'),
                /* translators: %s: Template type. */
                'dialog_confirm_delete' => __('Are you sure you want to remove this %s?'),

                // Saver.
                'before_unload_alert' => __('Please note: All unsaved changes will be lost.'),
                'published' => __('Published'),
                'publish' => __('Publish'),
                'save' => __('Save'),
                'saved' => __('Saved'),
                'update' => __('Update'),
                'submit' => __('Submit'),
                'working_on_draft_notification' => __('This is just a draft. Play around and when you\'re done - click update.'),
                'keep_editing' => __('Keep Editing'),
                'have_a_look' => __('Have a look'),
                'view_all_revisions' => __('View All Revisions'),
                'dismiss' => __('Dismiss'),
                'saving_disabled' => __('Saving has been disabled until you’re reconnected.'),

                // Ajax
                'server_error' => __('Server Error'),
                'server_connection_lost' => __('Connection Lost'),
                'unknown_error' => __('Unknown Error'),

                // Context Menu
                'duplicate' => __('Duplicate'),
                'copy' => __('Copy'),
                'paste' => __('Paste'),
                'copy_style' => __('Copy Style'),
                'paste_style' => __('Paste Style'),
                'reset_style' => __('Reset Style'),
                // 'save_as_global' => __('Save as a Global'),
                'save_as_block' => __('Save as Template'),
                'new_column' => __('Add New Column'),
                'copy_all_content' => __('Copy All Content'),
                'delete_all_content' => __('Delete All Content'),
                'navigator' => __('Navigator'),

                // Right Click Introduction
                'meet_right_click_header' => __('Meet Right Click'),
                'meet_right_click_message' => __('Now you can access all editing actions using right click.'),

                // Hotkeys screen
                'keyboard_shortcuts' => __('Keyboard Shortcuts'),

                // Misc
                'autosave' => __('Autosave'),
                'elementor_docs' => __('Documentation'),
                'reload_page' => __('Reload Page'),
                'session_expired_header' => __('Timeout'),
                'session_expired_message' => __('Your session has expired. Please reload the page to continue editing.'),
                'unknown_value' => __('Unknown Value'),
                'multistore' => __('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.'),
            ],
        ];

        $localized_settings = [];

        /**
         * Localize editor settings.
         *
         * Filters the editor localized settings.
         *
         * @since 1.0.0
         *
         * @param array $localized_settings Localized settings.
         * @param int   $post_id            The ID of the current post being edited.
         */
        $localized_settings = apply_filters('elementor/editor/localize_settings', $localized_settings, $this->_post_id);

        if (!empty($localized_settings)) {
            $config = array_replace_recursive($config, $localized_settings);
        }

        wp_localize_script('elementor-editor', 'ElementorConfig', $config);

        wp_enqueue_script('elementor-editor');

        $plugin->controls_manager->enqueueControlScripts();

        /**
         * After editor enqueue scripts.
         *
         * Fires after Elementor editor scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/after_enqueue_scripts');
    }

    /**
     * Enqueue styles.
     *
     * Registers all the editor styles and enqueues them.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueStyles()
    {
        /**
         * Before editor enqueue styles.
         *
         * Fires before Elementor editor styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/before_enqueue_styles');

        $suffix = Utils::isScriptDebug() ? '' : '.min';

        $direction_suffix = is_rtl() ? '-rtl' : '';

        wp_register_style(
            'font-awesome',
            _VEC_ASSETS_URL_ . 'lib/font-awesome/css/font-awesome' . $suffix . '.css',
            [],
            '4.7.0'
        );

        wp_register_style(
            'font-themevec',
            _VEC_ASSETS_URL_ . 'lib/font-themevec/css/vecthemes-icon' . $suffix . '.css',
            [],
            '1.0.0'
        );

        wp_register_style(
            'elementor-select2',
            _VEC_ASSETS_URL_ . 'lib/e-select2/css/e-select2' . $suffix . '.css',
            [],
            '4.0.6-rc.1'
        );

        wp_register_style(
            'google-font-roboto',
            'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap',
            [],
            _VEC_VERSION_
        );

        wp_register_style(
            'flatpickr',
            _VEC_ASSETS_URL_ . 'lib/flatpickr/flatpickr' . $suffix . '.css',
            [],
            '1.12.0'
        );

        wp_register_style(
            'elementor-editor',
            _VEC_ASSETS_URL_ . 'css/editor' . $direction_suffix . $suffix . '.css',
            [
                'elementor-common',
                'font-awesome',
                'font-themevec',
                'elementor-select2',
                'elementor-icons',
                'ce-icons',
                // 'wp-auth-check',
                'google-font-roboto',
                'flatpickr',
            ],
            _VEC_VERSION_
        );

        wp_enqueue_style('elementor-editor');

        if (Responsive::hasCustomBreakpoints()) {
            $breakpoints = Responsive::getBreakpoints();

            wp_add_inline_style('elementor-editor', '.elementor-device-tablet #elementor-preview-responsive-wrapper { width: ' . $breakpoints['md'] . 'px; }');
        }

        /**
         * After editor enqueue styles.
         *
         * Fires after Elementor editor styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/after_enqueue_styles');
    }

    /**
     * Get PrestaShop editor config.
     *
     * Config the default PrestaShop editor with custom settings for Elementor use.
     *
     * @since 1.9.0
     * @access private
     */
    private function getWpEditorConfig()
    {
        return
            '<div class="html-active" id="tinymce-editor-wrap">' .
            '<div class="wp-editor-container" id="tinymce-editor-container">' .
            '<textarea class="elementor-wp-editor" cols="40" id="tinymce-editor" name="tinymce-editor" rows="15">' .
            '%%EDITORCONTENT%%</textarea></div></div>';
    }

    /**
     * Editor head trigger.
     *
     * Fires the 'elementor/editor/wp_head' action in the head tag in Elementor
     * editor.
     *
     * @since 1.0.0
     * @access public
     */
    public function editorHeadTrigger()
    {
        /**
         * Elementor editor head.
         *
         * Fires on Elementor editor head tag.
         *
         * Used to prints scripts or any other data in the head tag.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/wp_head');
    }

    /**
     * WP footer.
     *
     * Prints Elementor editor with all the editor templates, and render controls,
     * widgets and content elements.
     *
     * Fired by `wp_footer` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function wpFooter()
    {
        $plugin = Plugin::$instance;

        $plugin->controls_manager->renderControls();
        $plugin->widgets_manager->renderWidgetsContent();
        $plugin->elements_manager->renderElementsContent();

        $plugin->schemes_manager->printSchemesTemplates();

        // $plugin->dynamic_tags->printTemplates();

        $this->initEditorTemplates();

        /**
         * Elementor editor footer.
         *
         * Fires on Elementor editor before closing the body tag.
         *
         * Used to prints scripts or any other HTML before closing the body tag.
         *
         * @since 1.0.0
         */
        do_action('elementor/editor/footer');
    }

    /**
     * Set edit mode.
     *
     * Used to update the edit mode.
     *
     * @since 1.0.0
     * @access public
     *
     * @param bool $edit_mode Whether the edit mode is active.
     */
    public function setEditMode($edit_mode)
    {
        $this->_is_edit_mode = $edit_mode;
    }

    // public function __construct()

    // public function filterWpLinkQueryArgs($query)

    // public function filterWpLinkQuery($results)

    /**
     * Init editor templates.
     *
     * Initialize default elementor templates used in the editor panel.
     *
     * @since 1.7.0
     * @access private
     */
    private function initEditorTemplates()
    {
        $template_names = [
            'global',
            'panel',
            'panel-elements',
            'repeater',
            'templates',
            'navigator',
            'hotkeys',
        ];

        foreach ($template_names as $template_name) {
            Plugin::$instance->common->addTemplate(_VEC_PATH_ . "includes/editor-templates/$template_name.php");
        }
    }
}
