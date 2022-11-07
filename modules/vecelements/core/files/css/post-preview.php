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

use VEC\CoreXFilesXCSSXPost as Post;

/**
 * Elementor post preview CSS file.
 *
 * Elementor CSS file handler class is responsible for generating the post
 * preview CSS file.
 *
 * @since 1.9.0
 */
class CoreXFilesXCSSXPostPreview extends Post
{
    /**
     * Preview ID.
     *
     * Holds the ID of the current post being previewed.
     *
     * @var int
     */
    private $preview_id;

    /**
     * Post preview CSS file constructor.
     *
     * Initializing the CSS file of the post preview. Set the post ID and the
     * parent ID and initiate the stylesheet.
     *
     * @since 1.9.0
     * @access public
     *
     * @param int $post_id Post ID.
     */
    public function __construct($post_id)
    {
        $this->preview_id = $post_id;

        $parent_id = wp_get_post_parent_id($post_id);

        parent::__construct($parent_id);
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function getPreviewId()
    {
        return $this->preview_id;
    }

    /**
     * Get data.
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
        return Plugin::$instance->db->getPlainEditor($this->preview_id);
    }

    /**
     * Get file handle ID.
     *
     * Retrieve the handle ID for the previewed post CSS file.
     *
     * @since 1.9.0
     * @access protected
     *
     * @return string CSS file handle ID.
     */
    protected function getFileHandleId()
    {
        return 'elementor-preview-' . $this->preview_id;
    }

    /**
     * Get meta data.
     *
     * Retrieve the previewed post CSS file meta data.
     *
     * @since 1.9.0
     * @access public
     *
     * @param string $property Optional. Custom meta data property. Default is
     *                         null.
     *
     * @return array Previewed post CSS file meta data.
     */
    public function getMeta($property = null)
    {
        // Parse CSS first, to get the fonts list.
        $css = $this->getContent();

        $meta = [
            'status' => self::CSS_STATUS_INLINE,
            'fonts' => $this->getFonts(),
            'css' => $css,
        ];

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }
}
