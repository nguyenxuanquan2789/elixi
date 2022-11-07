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
use VEC\CoreXUtilsXExceptions as Exceptions;

/**
 * Elementor widgets manager.
 *
 * Elementor widgets manager handler class is responsible for registering and
 * initializing all the supported Elementor widgets.
 *
 * @since 1.0.0
 */
class WidgetsManager
{
    /**
     * Widget types.
     *
     * Holds the list of all the widget types.
     *
     * @since 1.0.0
     * @access private
     *
     * @var WidgetBase[]
     */
    private $_widget_types = null;

    /**
     * Init widgets.
     *
     * Initialize Elementor widgets manager. Include all the the widgets files
     * and register each Elementor widget.
     *
     * @since 2.0.0
     * @access private
     */
    private function initWidgets()
    {
        $build_widgets_filename = [
            'common',
            'heading',
            'image',
            'text-editor',
            'video',
            'button',
            'divider',
            'spacer',
            'image-box',
            'google-maps',
            'icon',

            'icon-box',
            'image-gallery',
            'image-carousel',
            'banner',
            'star-rating',
            'icon-list',
            'counter',
            'progress',
            'testimonial',
            'tabs',
            'accordion',
            'toggle',
            'social-icons',
            'alert',
            'shortcode',
            'html',
            'menu-anchor',
            

            'slideshow',
            'product-carousel',
            'product-sale',
            'product-tab',
            'categories',
            'links-list',
            'brand-carousel',
            'call-to-action',
            'flip-box',
            'animated-headline',
            'image-hotspot',
            'contact-form',
            'email-subscription',
            'countdown',
            'testimonial-carousel',
            'facebook-page',
            'facebook-button',

            'site-logo',
            'megamenu',
            'shopping-cart',
            'ajax-search',
            'sign-in',
            'language-selector',
            'currency-selector',
            'v-smartblog',
            'promo',
            'wishlist',
            'compare',
        ];
        
        $build_widgets_filename[] = 'category-tree';
        
        $build_widgets_filename[] = 'module';

        $this->_widget_types = [];

        foreach ($build_widgets_filename as $widget_filename) {
            include _VEC_PATH_ . 'includes/widgets/' . $widget_filename . '.php';

            $class_name = str_replace('-', '', $widget_filename);

            $class_name = __NAMESPACE__ . '\Widget' . $class_name;

            $this->registerWidgetType(new $class_name());
        }

        // $this->registerWpWidgets();

        /**
         * After widgets registered.
         *
         * Fires after Elementor widgets are registered.
         *
         * @since 1.0.0
         *
         * @param WidgetsManager $this The widgets manager.
         */
        do_action('elementor/widgets/widgets_registered', $this);
    }

    // private function registerWpWidgets();

    /**
     * Require files.
     *
     * Require Elementor widget base class.
     *
     * @since 2.0.0
     * @access private
     */
    private function requireFiles()
    {
        require _VEC_PATH_ . 'includes/base/widget-base.php';
        require _VEC_PATH_ . 'includes/base/widget-product-base.php';
        require _VEC_PATH_ . 'includes/base/widget-category-base.php';
        require _VEC_PATH_ . 'includes/traits/carousel.php';
        require _VEC_PATH_ . 'includes/traits/nav.php';
    }

    /**
     * Register widget type.
     *
     * Add a new widget type to the list of registered widget types.
     *
     * @since 1.0.0
     * @access public
     *
     * @param WidgetBase $widget Elementor widget.
     *
     * @return true True if the widget was registered.
     */
    public function registerWidgetType(WidgetBase $widget)
    {
        if (is_null($this->_widget_types)) {
            $this->initWidgets();
        }

        $this->_widget_types[$widget->getName()] = $widget;

        return true;
    }

    /**
     * Unregister widget type.
     *
     * Removes widget type from the list of registered widget types.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $name Widget name.
     *
     * @return true True if the widget was unregistered, False otherwise.
     */
    public function unregisterWidgetType($name)
    {
        if (!isset($this->_widget_types[$name])) {
            return false;
        }

        unset($this->_widget_types[$name]);

        return true;
    }

    /**
     * Get widget types.
     *
     * Retrieve the registered widget types list.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $widget_name Optional. Widget name. Default is null.
     *
     * @return WidgetBase|Widget_Base[]|null Registered widget types.
     */
    public function getWidgetTypes($widget_name = null)
    {
        if (is_null($this->_widget_types)) {
            $this->initWidgets();
        }

        if (null !== $widget_name) {
            return isset($this->_widget_types[$widget_name]) ? $this->_widget_types[$widget_name] : null;
        }

        return $this->_widget_types;
    }

    /**
     * Get widget types config.
     *
     * Retrieve all the registered widgets with config for each widgets.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Registered widget types with each widget config.
     */
    public function getWidgetTypesConfig()
    {
        $config = [];

        foreach ($this->getWidgetTypes() as $widget_key => $widget) {
            $config[$widget_key] = $widget->getConfig();
        }

        return $config;
    }

    public function ajaxGetWidgetTypesControlsConfig(array $data)
    {
        $config = [];

        foreach ($this->getWidgetTypes() as $widget_key => $widget) {
            if (isset($data['exclude'][$widget_key])) {
                continue;
            }

            $config[$widget_key] = [
                'controls' => $widget->getStack(false)['controls'],
                'tabs_controls' => $widget->getTabsControls(),
            ];
        }

        return $config;
    }

    /**
     * Ajax render widget.
     *
     * Ajax handler for Elementor render_widget.
     *
     * @since 1.0.0
     * @access public
     *
     * @throws \Exception If current user don't have permissions to edit the post.
     *
     * @param array $request Ajax request.
     *
     * @return array {
     *     Rendered widget.
     *
     *     @type string $render The rendered HTML.
     * }
     */
    public function ajaxRenderWidget($request)
    {
        $document = Plugin::$instance->documents->get($request['editor_post_id']);

        if (!$document->isEditableByCurrentUser()) {
            throw new \Exception('Access denied.', Exceptions::FORBIDDEN);
        }

        // Override the global $post for the render.
        // query_posts(
        //     [
        //         'p' => $request['editor_post_id'],
        //         'post_type' => 'any',
        //     ]
        // );

        $editor = Plugin::$instance->editor;
        $is_edit_mode = $editor->isEditMode();
        $editor->setEditMode(true);

        Plugin::$instance->documents->switchToDocument($document);

        $render_html = $document->renderElement($request['data']);

        $editor->setEditMode($is_edit_mode);

        return [
            'render' => $render_html,
        ];
    }

    // public function ajaxGetWpWidgetForm($request);

    /**
     * Render widgets content.
     *
     * Used to generate the widget templates on the editor using Underscore JS
     * template, for all the registered widget types.
     *
     * @since 1.0.0
     * @access public
     */
    public function renderWidgetsContent()
    {
        foreach ($this->getWidgetTypes() as $widget) {
            $widget->printTemplate();
        }
    }

    /**
     * Get widgets frontend settings keys.
     *
     * Retrieve frontend controls settings keys for all the registered widget
     * types.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Registered widget types with settings keys for each widget.
     */
    public function getWidgetsFrontendSettingsKeys()
    {
        $keys = [];

        foreach ($this->getWidgetTypes() as $widget_type_name => $widget_type) {
            $widget_type_keys = $widget_type->getFrontendSettingsKeys();

            if ($widget_type_keys) {
                $keys[$widget_type_name] = $widget_type_keys;
            }
        }

        return $keys;
    }

    /**
     * Enqueue widgets scripts.
     *
     * Enqueue all the scripts defined as a dependency for each widget.
     *
     * @since 1.3.0
     * @access public
     */
    public function enqueueWidgetsScripts()
    {
        foreach ($this->getWidgetTypes() as $widget) {
            $widget->enqueueScripts();
        }
    }

    /**
     * Retrieve inline editing configuration.
     *
     * Returns general inline editing configurations like toolbar types etc.
     *
     * @access public
     * @since 1.8.0
     *
     * @return array {
     *     Inline editing configuration.
     *
     *     @type array $toolbar {
     *         Toolbar types and the actions each toolbar includes.
     *         Note: Wysiwyg controls uses the advanced toolbar, textarea controls
     *         uses the basic toolbar and text controls has no toolbar.
     *
     *         @type array $basic    Basic actions included in the edit tool.
     *         @type array $advanced Advanced actions included in the edit tool.
     *     }
     * }
     */
    public function getInlineEditingConfig()
    {
        $basic_tools = [
            'bold',
            'underline',
            'italic',
        ];

        $advanced_tools = array_merge($basic_tools, [
            'createlink',
            'unlink',
            'h1' => [
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'p',
                'blockquote',
                'pre',
            ],
            'list' => [
                'insertOrderedList',
                'insertUnorderedList',
            ],
        ]);

        return [
            'toolbar' => [
                'basic' => $basic_tools,
                'advanced' => $advanced_tools,
            ],
        ];
    }

    /**
     * Widgets manager constructor.
     *
     * Initializing Elementor widgets manager.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        $this->requireFiles();

        add_action('elementor/ajax/register_actions', [$this, 'register_ajax_actions']);
    }

    /**
     * Register ajax actions.
     *
     * Add new actions to handle data after an ajax requests returned.
     *
     * @since 2.0.0
     * @access public
     *
     * @param Ajax $ajax_manager
     */
    public function registerAjaxActions(Ajax $ajax_manager)
    {
        // $ajax_manager->registerAjaxAction('render_widget', [$this, 'ajax_render_widget']);
        // $ajax_manager->registerAjaxAction('editor_get_wp_widget_form', [$this, 'ajax_get_wp_widget_form']);
        $ajax_manager->registerAjaxAction('get_widgets_config', [$this, 'ajax_get_widget_types_controls_config']);
    }
}
