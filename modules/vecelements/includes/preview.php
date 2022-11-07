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
 * Elementor preview.
 *
 * Elementor preview handler class is responsible for initializing Elementor in
 * preview mode.
 *
 * @since 1.0.0
 */
class Preview
{
    /**
     * Post ID.
     *
     * Holds the ID of the current post being previewed.
     *
     * @since 1.0.0
     * @access private
     *
     * @var int Post ID.
     */
    private $post_id;

    /**
     * Init.
     *
     * Initialize Elementor preview mode.
     *
     * Fired by `template_redirect` action.
     *
     * @since 1.0.0
     * @access public
     */
    public function init()
    {
        if (is_admin() || !$this->isPreviewMode()) {
            return;
        }

        $this->post_id = \VecElements::getPreviewUId();

        add_action('wp_enqueue_scripts', function () {
            ${'this'}->enqueueStyles();
            ${'this'}->enqueueScripts();
        });

        add_filter('the_content', [$this, 'builder_wrapper'], 999999);

        add_action('wp_footer', [$this, 'wp_footer']);

        /**
         * Preview init.
         *
         * Fires on Elementor preview init, after Elementor preview has finished
         * loading but before any headers are sent.
         *
         * @since 1.0.0
         *
         * @param Preview $this The current preview.
         */
        do_action('elementor/preview/init', $this);
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
        return $this->post_id;
    }

    /**
     * Whether preview mode is active.
     *
     * Used to determine whether we are in the preview mode.
     *
     * @since 1.0.0
     * @access public
     *
     * @param int $post_id Optional. Post ID. Default is `0`.
     *
     * @return bool Whether preview mode is active.
     */
    public function isPreviewMode($post_id = 0)
    {
        if (empty($post_id)) {
            $post_id = get_the_ID();
        }

        if (!User::isCurrentUserCanEdit($post_id)) {
            return false;
        }

        // if (!isset($_GET['elementor-preview']) || $post_id != $_GET['elementor-preview']) {
        if (!isset(${'_GET'}['preview_id']) || $post_id != ${'_GET'}['preview_id'] || !isset(${'_GET'}['ver'])) {
            return false;
        }

        return true;
    }

    /**
     * Builder wrapper.
     *
     * Used to add an empty HTML wrapper for the builder, the javascript will add
     * the content later.
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $content The content of the builder.
     *
     * @return string HTML wrapper for the builder.
     */
    public function builderWrapper($content)
    {
        if (UId::$_ID == $this->post_id) {
            $document = Plugin::$instance->documents->get($this->post_id);

            $attributes = $document->getContainerAttributes();
            $attributes['id'] = 'elementor';
            $attributes['class'] .= ' elementor-edit-mode';

            $content = '<div ' . Utils::renderHtmlAttributes($attributes) . '></div>';
        }

        return $content;
    }

    /**
     * Enqueue preview styles.
     *
     * Registers all the preview styles and enqueues them.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.0.0
     * @access private
     */
    private function enqueueStyles()
    {
        // Hold-on all jQuery plugins after all HTML markup render.
        // wp_add_inline_script('jquery-migrate', 'jQuery.holdReady( true );');

        Plugin::$instance->frontend->enqueueStyles();

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        $direction_suffix = is_rtl() ? '-rtl' : '';

        wp_register_style(
            'elementor-select2',
            _VEC_ASSETS_URL_ . 'lib/e-select2/css/e-select2' . $suffix . '.css',
            [],
            '4.0.6-rc.1'
        );

        wp_register_style(
            'editor-preview',
            _VEC_ASSETS_URL_ . 'css/editor-preview' . $direction_suffix . $suffix . '.css',
            ['elementor-select2'],
            _VEC_VERSION_
        );

        wp_enqueue_style('editor-preview');

        /**
         * Preview enqueue styles.
         *
         * Fires after Elementor preview styles are enqueued.
         *
         * @since 1.0.0
         */
        do_action('elementor/preview/enqueue_styles');
    }

    /**
     * Enqueue preview scripts.
     *
     * Registers all the preview scripts and enqueues them.
     *
     * Fired by `wp_enqueue_scripts` action.
     *
     * @since 1.5.4
     * @access private
     */
    private function enqueueScripts()
    {
        Plugin::$instance->frontend->registerScripts();

        Plugin::$instance->widgets_manager->enqueueWidgetsScripts();

        $suffix = _PS_MODE_DEV_ ? '' : '.min';

        wp_enqueue_script(
            'elementor-inline-editor',
            _VEC_ASSETS_URL_ . 'lib/inline-editor/js/inline-editor' . $suffix . '.js',
            [],
            _VEC_VERSION_,
            true
        );

        /**
         * Preview enqueue scripts.
         *
         * Fires after Elementor preview scripts are enqueued.
         *
         * @since 1.5.4
         */
        do_action('elementor/preview/enqueue_scripts');
    }

    /**
     * Elementor Preview footer scripts and styles.
     *
     * Handle styles and scripts from frontend.
     *
     * Fired by `wp_footer` action.
     *
     * @since 2.0.9
     * @access public
     */
    public function wpFooter()
    {
        $frontend = Plugin::$instance->frontend;
        if ($frontend->hasElementorInPage()) {
            // Has header/footer/widget-template - enqueue all style/scripts/fonts.
            $frontend->wpFooter();
        } else {
            // Enqueue only scripts.
            $frontend->enqueueScripts();
        }
    }

    /**
     * Preview constructor.
     *
     * Initializing Elementor preview.
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        add_action('template_redirect', [$this, 'init'], 0);
    }
}
