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

/**
 * Elementor post CSS file.
 *
 * Elementor CSS file handler class is responsible for generating the single
 * post CSS file.
 *
 * @since 1.2.0
 */
class CoreXFilesXCSSXPost extends Base
{
    /**
     * Elementor post CSS file prefix.
     */
    const FILE_PREFIX = '';

    const META_KEY = '_elementor_css';

    /**
     * Post ID.
     *
     * Holds the current post ID.
     *
     * @var int
     */
    private $post_id;

    /**
     * Post CSS file constructor.
     *
     * Initializing the CSS file of the post. Set the post ID and initiate the stylesheet.
     *
     * @since 1.2.0
     * @access public
     *
     * @param int $post_id Post ID.
     */
    public function __construct($post_id)
    {
        $this->post_id = $post_id;

        parent::__construct(self::FILE_PREFIX . $post_id . '.css');
    }

    /**
     * Get CSS file name.
     *
     * Retrieve the CSS file name.
     *
     * @since 1.6.0
     * @access public
     *
     * @return string CSS file name.
     */
    public function getName()
    {
        return 'post';
    }

    /**
     * Get post ID.
     *
     * Retrieve the ID of current post.
     *
     * @since 1.2.0
     * @access public
     *
     * @return int Post ID.
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Get unique element selector.
     *
     * Retrieve the unique selector for any given element.
     *
     * @since 1.2.0
     * @access public
     *
     * @param ElementBase $element The element.
     *
     * @return string Unique element selector.
     */
    public function getElementUniqueSelector(ElementBase $element)
    {
        return '.elementor-' . $this->post_id . ' .elementor-element' . $element->getUniqueSelector();
    }

    /**
     * Load meta data.
     *
     * Retrieve the post CSS file meta data.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return array Post CSS file meta data.
     */
    protected function loadMeta()
    {
        return get_post_meta($this->post_id, static::META_KEY, true);
    }

    /**
     * Update meta data.
     *
     * Update the global CSS file meta data.
     *
     * @since 1.2.0
     * @access protected
     *
     * @param array $meta New meta data.
     */
    protected function updateMeta($meta)
    {
        update_post_meta($this->post_id, static::META_KEY, $meta);
    }

    /**
     * Delete meta.
     *
     * Delete the file meta data.
     *
     * @since  2.1.0
     * @access protected
     */
    protected function deleteMeta()
    {
        delete_post_meta($this->post_id, static::META_KEY);
    }

    /**
     * Get post data.
     *
     * Retrieve raw post data from the database.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return array Post data.
     */
    protected function getData()
    {
        return Plugin::$instance->db->getPlainEditor($this->post_id);
    }

    /**
     * Render CSS.
     *
     * Parse the CSS for all the elements.
     *
     * @since 1.2.0
     * @access protected
     */
    protected function renderCss()
    {
        $data = $this->getData();

        if (!empty($data)) {
            foreach ($data as $element_data) {
                $element = Plugin::$instance->elements_manager->createElementInstance($element_data);

                if (!$element) {
                    continue;
                }

                $this->renderStyles($element);
            }
        }
    }

    /**
     * Enqueue CSS.
     *
     * Enqueue the post CSS file in Elementor.
     *
     * This method ensures that the post was actually built with elementor before
     * enqueueing the post CSS file.
     *
     * @since 1.2.2
     * @access public
     */
    public function enqueue()
    {
        if (!Plugin::$instance->db->isBuiltWithElementor($this->post_id)) {
            return;
        }

        parent::enqueue();
    }

    /**
     * Add controls-stack style rules.
     *
     * Parse the CSS for all the elements inside any given controls stack.
     *
     * This method recursively renders the CSS for all the child elements in the stack.
     *
     * @since 1.6.0
     * @access public
     *
     * @param ControlsStack $controls_stack The controls stack.
     * @param array          $controls       Controls array.
     * @param array          $values         Values array.
     * @param array          $placeholders   Placeholders.
     * @param array          $replacements   Replacements.
     * @param array          $all_controls   All controls.
     */
    public function addControlsStackStyleRules(ControlsStack $controls_stack, array $controls, array $values, array $placeholders, array $replacements, array $all_controls = null)
    {
        parent::addControlsStackStyleRules($controls_stack, $controls, $values, $placeholders, $replacements, $all_controls);

        if ($controls_stack instanceof ElementBase) {
            foreach ($controls_stack->getChildren() as $child_element) {
                $this->renderStyles($child_element);
            }
        }
    }

    /**
     * Get enqueue dependencies.
     *
     * Retrieve the name of the stylesheet used by `wp_enqueue_style()`.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return array Name of the stylesheet.
     */
    protected function getEnqueueDependencies()
    {
        return ['elementor-frontend'];
    }

    /**
     * Get inline dependency.
     *
     * Retrieve the name of the stylesheet used by `wp_add_inline_style()`.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string Name of the stylesheet.
     */
    protected function getInlineDependency()
    {
        return 'elementor-frontend';
    }

    /**
     * Get file handle ID.
     *
     * Retrieve the handle ID for the post CSS file.
     *
     * @since 1.2.0
     * @access protected
     *
     * @return string CSS file handle ID.
     */
    protected function getFileHandleId()
    {
        return 'elementor-post-' . $this->post_id;
    }

    /**
     * Render styles.
     *
     * Parse the CSS for any given element.
     *
     * @since 1.2.0
     * @access protected
     *
     * @param ElementBase $element The element.
     */
    protected function renderStyles(ElementBase $element)
    {
        /**
         * Before element parse CSS.
         *
         * Fires before the CSS of the element is parsed.
         *
         * @since 1.2.0
         *
         * @param Post         $this    The post CSS file.
         * @param ElementBase $element The element.
         */
        do_action('elementor/element/before_parse_css', $this, $element);

        $element_settings = $element->getSettings();

        $this->addControlsStackStyleRules($element, $element->getStyleControls(null, $element->getParsedDynamicSettings()), $element_settings, ['{{ID}}', '{{WRAPPER}}'], [$element->getId(), $this->getElementUniqueSelector($element)]);

        /**
         * After element parse CSS.
         *
         * Fires after the CSS of the element is parsed.
         *
         * @since 1.2.0
         *
         * @param Post         $this    The post CSS file.
         * @param ElementBase $element The element.
         */
        do_action('elementor/element/parse_css', $this, $element);
    }
}
