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

use VEC\CoreXBaseXApp as App;
use VEC\CoreXBaseXDocument as Document;
use VEC\CoreXFilesXCSSXGlobalCSS as GlobalCSS;
use VEC\CoreXFilesXCSSXPost as PostCSS;
use VEC\CoreXFilesXCSSXPostPreview as PostPreview;
use VEC\CoreXResponsiveXFilesXFrontend as FrontendFile;
use VEC\CoreXResponsiveXResponsive as Responsive;
use VEC\CoreXSettingsXManager as SettingsManager;

/**
 * Elementor frontend.
 *
 * Elementor frontend handler class is responsible for initializing Elementor in
 * the frontend.
 *
 * @since 1.0.0
 */
class Frontend extends App
{
    /**
     * The priority of the content filter.
     */
    const THE_CONTENT_FILTER_PRIORITY = 9;

    /**
     * Post ID.
     *
     * Holds the ID of the current post.
     *
     * @access private
     *
     * @var int Post ID.
     */
    private $post_id;

    /**
     * Fonts to enqueue
     *
     * Holds the list of fonts that are being used in the current page.
     *
     * @since 1.9.4
     * @access private
     *
     * @var array Used fonts. Default is an empty array.
     */
    private $fonts_to_enqueue = [];

    /**
     * Registered fonts.
     *
     * Holds the list of enqueued fonts in the current page.
     *
     * @since 1.0.0
     * @access private
     *
     * @var array Registered fonts. Default is an empty array.
     */
    private $registered_fonts = [];

    /**
     * Whether the page is using Elementor.
     *
     * Used to determine whether the current page is using Elementor.
     *
     * @since 1.0.0
     * @access private
     *
     * @var bool Whether Elementor is being used. Default is false.
     */
    private $_has_elementor_in_page = false;

    /**
     * Whether the excerpt is being called.
     *
     * Used to determine whether the call to `the_content()` came from `get_the_excerpt()`.
     *
     * @since 1.0.0
     * @access private
     *
     * @var bool Whether the excerpt is being used. Default is false.
     */
    private $_is_excerpt = false;

    /**
     * Filters removed from the content.
     *
     * Hold the list of filters removed from `the_content()`. Used to hold the filters that
     * conflicted with Elementor while Elementor process the content.
     *
     * @since 1.0.0
     * @access private
     *
     * @var array Filters removed from the content. Default is an empty array.
     */
    private $content_removed_filters = [];

    // private $admin_bar_edit_documents = [];

    /**
     * @var string[]
     */
    private $body_classes = [
        'elementor-default',
    ];

    /**
     * Front End constructor.
     *
     * Initializing Elementor front end. Make sure we are not in admin, not and
     * redirect from old URL structure of Elementor editor.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        // We don't need this class in admin side, but in AJAX requests.
        if (is_admin() && !Utils::isAjax()) {
            return;
        }

        add_action('template_redirect', [$this, 'init']);
        add_action('wp_register_scripts', [$this, 'register_scripts'], 5);
        add_action('wp_register_scripts', [$this, 'register_styles'], 5);

        $this->addContentFilter();

        // Hack to avoid enqueue post CSS while it's a `the_excerpt` call.
        // add_filter('get_the_excerpt', [$this, 'start_excerpt_flag'], 1);
        // add_filter('get_the_excerpt', [$this, 'end_excerpt_flag'], 20);
    }

    /**
     * Get module name.
     *
     * Retrieve the module name.
     *
     * @since 2.3.0
     * @access public
     *
     * @return string Module name.
     */
    public function getName()
    {
        return 'frontend';
    }

    /**
     * Init.
     *
     * Initialize Elementor front end. Hooks the needed actions to run Elementor
     * in the front end, including script and style registration.
     *
     * Fired by `template_redirect` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        if (Plugin::$instance->editor->isEditMode()) {
            return;
        }

        add_filter('body_class', [$this, 'body_class']);

        if (Plugin::$instance->preview->isPreviewMode()) {
            return;
        }

        // if (current_user_can('manage_options')) {
        //     Plugin::$instance->initCommon();
        // }

        $this->post_id = get_the_ID();

        if (is_singular() && Plugin::$instance->db->isBuiltWithElementor($this->post_id)) {
            add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        }

        // Priority 7 to allow google fonts in header template to load in <head> tag
        add_action('wp_head', [$this, 'print_fonts_links'], 7);
        add_action('wp_footer', [$this, 'wp_footer']);

        // Add Edit with the Elementor in Admin Bar.
        // add_action('admin_bar_menu', [$this, 'add_menu_in_admin_bar'], 200);
    }

    // public function addBodyClass($class)

    // public function bodyClass($classes = [])

    /**
     * Add content filter.
     *
     * Remove plain content and render the content generated by Elementor.
     *
     * @since 1.8.0
     * @access public
     */
    public function addContentFilter()
    {
        add_filter('the_content', [$this, 'apply_builder_in_content'], self::THE_CONTENT_FILTER_PRIORITY);
    }

    // public function removeContentFilter()

    /**
     * Registers scripts.
     *
     * Registers all the frontend scripts.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.2.1
     * @access public
     */
    public function registerScripts()
    {
        /**
         * Before frontend register scripts.
         *
         * Fires before Elementor frontend scripts are registered.
         *
         * @since 1.2.1
         */
        do_action('elementor/frontend/before_register_scripts');

        wp_register_script(
            'elementor-frontend-modules',
            $this->getJsAssetsUrl('frontend-modules'),
            [
                'jquery',
            ],
            _VEC_VERSION_,
            true
        );

        wp_register_script(
            'elementor-waypoints',
            $this->getJsAssetsUrl('waypoints', 'views/lib/waypoints/'),
            [
                'jquery',
            ],
            '4.0.2',
            true
        );

        wp_register_script(
            'flatpickr',
            $this->getJsAssetsUrl('flatpickr', 'views/lib/flatpickr/'),
            [
                'jquery',
            ],
            '4.1.4',
            true
        );

        // wp_register_script(
        //     'imagesloaded',
        //     $this->getJsAssetsUrl('imagesloaded', 'views/lib/imagesloaded/'),
        //     [
        //         'jquery',
        //     ],
        //     '4.1.0',
        //     true
        // );

        wp_register_script(
            'jquery-numerator',
            $this->getJsAssetsUrl('jquery-numerator', 'views/lib/jquery-numerator/'),
            [
                'jquery',
            ],
            '0.2.1',
            true
        );

        wp_register_script(
            'swiper',
            $this->getJsAssetsUrl('swiper', 'views/lib/swiper/'),
            [],
            '4.4.6',
            true
        );

        // wp_register_script(
        //     'jquery-slick',
        //     $this->getJsAssetsUrl('slick', 'views/lib/slick/'),
        //     [
        //         'jquery',
        //     ],
        //     '1.8.1',
        //     true
        // );

        wp_register_script(
            'elementor-dialog',
            $this->getJsAssetsUrl('dialog', 'views/lib/dialog/'),
            [
                'jquery-ui-position',
            ],
            '4.7.1',
            true
        );

        wp_register_script(
            'smartmenus',
            $this->getJsAssetsUrl('jquery.smartmenus', 'views/lib/smartmenus/'),
            [
                'jquery',
            ],
            '1.0.1',
            true
        );

        wp_register_script(
            'elementor-frontend',
            $this->getJsAssetsUrl('frontend'),
            [
                'elementor-frontend-modules',
                'elementor-dialog',
                'elementor-waypoints',
                'swiper',
            ],
            _VEC_VERSION_,
            true
        );

        /**
         * After frontend register scripts.
         *
         * Fires after Elementor frontend scripts are registered.
         *
         * @since 1.2.1
         */
        do_action('elementor/frontend/after_register_scripts');
    }

    /**
     * Registers styles.
     *
     * Registers all the frontend styles.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.2.0
     * @access public
     */
    public function registerStyles()
    {
        /**
         * Before frontend register styles.
         *
         * Fires before Elementor frontend styles are registered.
         *
         * @since 1.2.0
         */
        do_action('elementor/frontend/before_register_styles');

        wp_register_style(
            'elementor-icons',
            $this->getCssAssetsUrl('elementor-icons', 'views/lib/eicons/css/'),
            [],
            '4.3.0'
        );

        wp_register_style(
            'ce-icons',
            $this->getCssAssetsUrl('ceicons', 'views/lib/ceicons/'),
            [],
            '1.0.0'
        );

        wp_register_style(
            'font-awesome',
            $this->getCssAssetsUrl('font-awesome', 'views/lib/font-awesome/css/'),
            [],
            '4.7.0'
        );

        wp_register_style(
            'elementor-animations',
            $this->getCssAssetsUrl('animations', 'views/lib/animations/', true),
            [],
            _VEC_VERSION_
        );

        wp_register_style(
            'flatpickr',
            $this->getCssAssetsUrl('flatpickr', 'views/lib/flatpickr/'),
            [],
            '4.1.4'
        );

        $min_suffix = Utils::isScriptDebug() ? '' : '.min';

        $direction_suffix = is_rtl() ? '-rtl' : '';

        $frontend_file_name = 'frontend' . $direction_suffix . $min_suffix . '.css';

        $has_custom_file = Responsive::hasCustomBreakpoints();

        if ($has_custom_file) {
            $id_shop = (int) \Context::getContext()->shop->id;

            $frontend_file = new FrontendFile("$id_shop-" . $frontend_file_name, Responsive::getStylesheetTemplatesPath() . $frontend_file_name);

            $time = $frontend_file->getMeta('time');

            if (!$time) {
                $frontend_file->update();
            }

            $frontend_file_url = $frontend_file->getUrl();
        } else {
            $frontend_file_url = _VEC_ASSETS_URL_ . 'css/' . $frontend_file_name;
        }

        wp_register_style(
            'elementor-frontend',
            $frontend_file_url,
            [],
            $has_custom_file ? null : _VEC_VERSION_
        );

        /**
         * After frontend register styles.
         *
         * Fires after Elementor frontend styles are registered.
         *
         * @since 1.2.0
         */
        do_action('elementor/frontend/after_register_styles');
    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the frontend scripts.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueScripts()
    {
        /**
         * Before frontend enqueue scripts.
         *
         * Fires before Elementor frontend scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/before_enqueue_scripts');

        $this->printConfig();

        wp_enqueue_script('elementor-frontend');

        /**
         * After frontend enqueue scripts.
         *
         * Fires after Elementor frontend scripts are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/after_enqueue_scripts');
    }

    /**
     * Enqueue styles.
     *
     * Enqueue all the frontend styles.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function enqueueStyles()
    {
        /**
         * Before frontend styles enqueued.
         *
         * Fires before Elementor frontend styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/before_enqueue_styles');

        wp_enqueue_style('font-awesome');
        wp_enqueue_style('elementor-animations');
        wp_enqueue_style('elementor-frontend');
        wp_enqueue_style('ce-icons');

        /**
         * After frontend styles enqueued.
         *
         * Fires after Elementor frontend styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/frontend/after_enqueue_styles');

        if (Plugin::$instance->preview->isPreviewMode()) {
            // Load only in preview mode
            wp_enqueue_style('elementor-icons');
        } else {
            $this->parseGlobalCssCode();

            $css_file = new PostCSS(get_the_ID());
            $css_file->enqueue();
        }
    }

    /**
     * Elementor footer scripts and styles.
     *
     * Handle styles and scripts that are not printed in the header.
     *
     * Fired by `wp_footer` action.
     *
     * @since 1.0.11
     * @access public
     */
    public function wpFooter()
    {
        if (!$this->_has_elementor_in_page) {
            return;
        }

        $this->enqueueStyles();
        $this->enqueueScripts();

        $this->printFontsLinks();
    }

    /**
     * Print fonts links.
     *
     * Enqueue all the frontend fonts by url.
     *
     * Fired by `wp_head` action.
     *
     * @since 1.9.4
     * @access public
     */
    public function printFontsLinks()
    {
        $google_fonts = [
            'google' => [],
            'early' => [],
        ];

        foreach ($this->fonts_to_enqueue as $font) {
            $font_type = Fonts::getFontType($font);

            switch ($font_type) {
                case Fonts::GOOGLE:
                    $google_fonts['google'][] = $font;
                    break;

                case Fonts::EARLYACCESS:
                    $google_fonts['early'][] = $font;
                    break;

                default:
                    /**
                     * Print font links.
                     *
                     * Fires when Elementor frontend fonts are printed on the HEAD tag.
                     *
                     * The dynamic portion of the hook name, `$font_type`, refers to the font type.
                     *
                     * @since 2.0.0
                     *
                     * @param string $font Font name.
                     */
                    do_action("elementor/fonts/print_font_links/{$font_type}", $font);
            }
        }
        $this->fonts_to_enqueue = [];

        $this->enqueueGoogleFonts($google_fonts);
    }

    /**
     * Print Google fonts.
     *
     * Enqueue all the frontend Google fonts.
     *
     * Fired by `wp_head` action.
     *
     * @since 1.0.0
     * @access private
     *
     * @param array $google_fonts Optional. Google fonts to print in the frontend.
     *                            Default is an empty array.
     */
    private function enqueueGoogleFonts($google_fonts = [])
    {
        static $google_fonts_index = 0;

        $print_google_fonts = true;

        /**
         * Print frontend google fonts.
         *
         * Filters whether to enqueue Google fonts in the frontend.
         *
         * @since 1.0.0
         *
         * @param bool $print_google_fonts Whether to enqueue Google fonts. Default is true.
         */
        $print_google_fonts = apply_filters('elementor/frontend/print_google_fonts', $print_google_fonts);

        if (!$print_google_fonts) {
            return;
        }

        // Print used fonts
        if (!empty($google_fonts['google'])) {
            $google_fonts_index++;

            foreach ($google_fonts['google'] as &$font) {
                $font = str_replace(' ', '+', $font) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
            }

            $fonts_url = sprintf('https://fonts.googleapis.com/css?family=%s&display=swap', implode(rawurlencode('|'), $google_fonts['google']));

            $subsets = [
                'ru' => 'cyrillic',
                'bg' => 'cyrillic',
                'he' => 'hebrew',
                'el' => 'greek',
                'vi' => 'vietnamese',
                'uk' => 'cyrillic',
                'cs' => 'latin-ext',
                'ro' => 'latin-ext',
                'pl' => 'latin-ext',
            ];
            $locale = get_locale();

            if (isset($subsets[$locale])) {
                $fonts_url .= '&subset=' . $subsets[$locale];
            }

            wp_enqueue_style('google-fonts-' . $google_fonts_index, $fonts_url);
        }

        if (!empty($google_fonts['early'])) {
            foreach ($google_fonts['early'] as $current_font) {
                $google_fonts_index++;

                $font_url = sprintf('https://fonts.googleapis.com/earlyaccess/%s.css', call_user_func('strtolower', str_replace(' ', '', $current_font)));

                wp_enqueue_style('google-earlyaccess-' . $google_fonts_index, $font_url);
            }
        }
    }

    /**
     * Enqueue fonts.
     *
     * Enqueue all the frontend fonts.
     *
     * @since 1.2.0
     * @access public
     *
     * @param array $font Fonts to enqueue in the frontend.
     */
    public function enqueueFont($font)
    {
        if (in_array($font, $this->registered_fonts)) {
            return;
        }

        $this->fonts_to_enqueue[] = $font;
        $this->registered_fonts[] = $font;
    }

    /**
     * Parse global CSS.
     *
     * Enqueue the global CSS file.
     *
     * @since 1.2.0
     * @access protected
     */
    protected function parseGlobalCssCode()
    {
        $id_shop = (int) \Context::getContext()->shop->id;

        $scheme_css_file = new GlobalCSS("$id_shop-global.css");
        $scheme_css_file->enqueue();
    }

    /**
     * Apply builder in content.
     *
     * Used to apply the Elementor page editor on the post content.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $content The post content.
     *
     * @return string The post content.
     */
    public function applyBuilderInContent($content)
    {
        // $this->restoreContentFilters();
        $post_id = UId::$_ID;

        if (\VecElements::getPreviewUId() == $post_id || $this->_is_excerpt) {
            return $content;
        }

        // Remove the filter itself in order to allow other `the_content` in the elements
        // $this->removeContentFilter();

        $builder_content = $this->getBuilderContent($post_id);

        if (!empty($builder_content)) {
            $content = $builder_content;
            // $this->removeContentFilters();
        }

        // Add the filter again for other `the_content` calls
        // $this->addContentFilter();

        return $content;
    }

    /**
     * Retrieve builder content.
     *
     * Used to render and return the post content with all the Elementor elements.
     *
     * Note that this method is an internal method, please use `get_builder_content_for_display()`.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int  $post_id  The post ID.
     * @param bool $with_css Optional. Whether to retrieve the content with CSS or not. Default is false.
     *
     * @return string The post content.
     */
    public function getBuilderContent($post_id, $with_css = false)
    {
        // if (post_password_required($post_id)) {
        //     return '';
        // }

        if (!Plugin::$instance->db->isBuiltWithElementor($post_id)) {
            return '';
        }

        $document = Plugin::$instance->documents->getDocForFrontend($post_id);

        // Change the current post, so widgets can use `documents->get_current`.
        Plugin::$instance->documents->switchToDocument($document);

        // if ($document->isEditableByCurrentUser()) {
        //     $this->admin_bar_edit_documents[$document->getMainId()] = $document;
        // }

        $data = $document->getElementsData();

        /**
         * Frontend builder content data.
         *
         * Filters the builder content in the frontend.
         *
         * @since 1.0.0
         *
         * @param array $data    The builder content.
         * @param int   $post_id The post ID.
         */
        $data = apply_filters('elementor/frontend/builder_content_data', $data, $post_id);

        if (empty($data)) {
            return '';
        }

        if (!$this->_is_excerpt) {
            if ($document->isAutosave()) {
                $css_file = new PostPreview($document->getPost()->ID);
            } else {
                $css_file = new PostCSS($post_id);
            }

            $css_file->enqueue();
        }

        ob_start();

        // Handle JS and Customizer requests, with CSS inline.
        if (is_customize_preview() || Utils::isAjax()) {
            $with_css = true;
        }

        if (!empty($css_file) && $with_css) {
            $css_file->printCss();
        }

        $document->printElementsWithWrapper($data);

        $content = ob_get_clean();

        // $content = $this->processMoreTag($content);

        /**
         * Frontend content.
         *
         * Filters the content in the frontend.
         *
         * @since 1.0.0
         *
         * @param string $content The content.
         */
        $content = apply_filters('elementor/frontend/the_content', $content);

        if (!empty($content)) {
            $this->_has_elementor_in_page = true;
        }

        Plugin::$instance->documents->restoreDocument();

        return $content;
    }

    // public function addMenuInAdminBar(\WPAdminBar $wp_admin_bar)

    /**
     * Retrieve builder content for display.
     *
     * Used to render and return the post content with all the Elementor elements.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $post_id The post ID.
     *
     * @param bool $with_css Optional. Whether to retrieve the content with CSS
     *                       or not. Default is false.
     *
     * @return string The post content.
     */
    public function getBuilderContentForDisplay($post_id, $with_css = false)
    {
        if (!get_post($post_id)) {
            return '';
        }

        $editor = Plugin::$instance->editor;

        // Avoid recursion
        if (get_the_ID() === (int) $post_id) {
            $content = '';
            if ($editor->isEditMode()) {
                $content = '<div class="elementor-alert elementor-alert-danger">' . __(
                    'Invalid Data: The Template ID cannot be the same as the currently edited template. ' .
                    'Please choose a different one.'
                ) . '</div>';
            }

            return $content;
        }

        // Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
        $is_edit_mode = $editor->isEditMode();
        $editor->setEditMode(false);

        $with_css = $with_css ? true : $is_edit_mode;

        $content = $this->getBuilderContent($post_id, $with_css);

        // Restore edit mode state
        Plugin::$instance->editor->setEditMode($is_edit_mode);

        return $content;
    }

    // public function startExcerptFlag($excerpt)

    // public function endExcerptFlag($excerpt)

    // public function removeContentFilters();

    /**
     * Has Elementor In Page
     *
     * Determine whether the current page is using Elementor.
     *
     * @since 2.0.9
     *
     * @access public
     * @return bool
     */
    public function hasElementorInPage()
    {
        return $this->_has_elementor_in_page;
    }

    /**
     * Get Init Settings
     *
     * Used to define the default/initial settings of the object. Inheriting classes may implement this method to define
     * their own default/initial settings.
     *
     * @since 2.3.0
     *
     * @access protected
     * @return array
     */
    protected function getInitSettings()
    {
        $is_preview_mode = Plugin::$instance->preview->isPreviewMode(Plugin::$instance->preview->getPostId());

        $settings = [
            'environmentMode' => [
                'edit' => $is_preview_mode,
                'wpPreview' => is_preview(),
            ],
            'is_rtl' => is_rtl(),
            'breakpoints' => Responsive::getBreakpoints(),
            'version' => _VEC_VERSION_,
            'urls' => [
                'assets' => _MODULE_DIR_ . 'vecelements/views/',
                'front_ajax' => \Context::getContext()->link->getModuleLink('vecelements', 'ajax')
            ],
        ];

        $settings['settings'] = SettingsManager::getSettingsFrontendConfig();

        if (is_singular()) {
            $post = get_post();
            $settings['post'] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'excerpt' => $post->post_excerpt,
            ];
        } else {
            $settings['post'] = [
                'id' => 0,
                'title' => wp_get_document_title(),
                'excerpt' => '',
            ];
        }

        $empty_object = (object) [];

        if ($is_preview_mode) {
            $settings['elements'] = [
                'data' => $empty_object,
                'editSettings' => $empty_object,
                'keys' => $empty_object,
            ];
        }

        // if (is_user_logged_in()) {
        //     $user = wp_get_current_user();

        //     if (!empty($user->roles)) {
        //         $settings['user'] = [
        //             'roles' => $user->roles,
        //         ];
        //     }
        // }

        return $settings;
    }

    // private function restoreContentFilters();

    // private function processMoreTag($content);
}
