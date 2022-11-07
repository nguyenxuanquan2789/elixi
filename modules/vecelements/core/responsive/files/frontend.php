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

use VEC\CoreXFilesXBase as Base;
use VEC\CoreXResponsiveXResponsive as Responsive;

class CoreXResponsiveXFilesXFrontend extends Base
{
    const META_KEY = 'elementor-custom-breakpoints-files';

    private $template_file;

    /**
     * @since 2.1.0
     * @access public
     */
    public function __construct($file_name, $template_file = null)
    {
        $this->template_file = $template_file;

        parent::__construct($file_name);
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function parseContent()
    {
        $breakpoints = Responsive::getBreakpoints();

        $breakpoints_keys = array_keys($breakpoints);

        $file_content = call_user_func('file_get_contents', $this->template_file);

        $file_content = preg_replace_callback('/-width:\s*(767|768|1024|1025)px\s*\)/', function ($placeholder_data) use ($breakpoints_keys, $breakpoints) {
            $width = (int) $placeholder_data[1];
            $size = $width < 768 ? 'sm' : ($width < 1025 ? 'md' : 'lg');
            $breakpoint_index = array_search($size, $breakpoints_keys);

            $is_max_point = 767 === $width || 1024 === $width;

            if ($is_max_point) {
                $breakpoint_index++;
            }

            $value = $breakpoints[$breakpoints_keys[$breakpoint_index]];

            if ($is_max_point) {
                $value--;
            }

            return "-width:{$value}px)";
        }, $file_content);

        return $file_content;
    }

    /**
     * Load meta.
     *
     * Retrieve the file meta data.
     *
     * @since 2.1.0
     * @access protected
     */
    protected function loadMeta()
    {
        $option = $this->loadMetaOption();

        $file_meta_key = $this->getFileMetaKey();

        if (empty($option[$file_meta_key])) {
            return [];
        }

        return $option[$file_meta_key];
    }

    /**
     * Update meta.
     *
     * Update the file meta data.
     *
     * @since 2.1.0
     * @access protected
     *
     * @param array $meta New meta data.
     */
    protected function updateMeta($meta)
    {
        $option = $this->loadMetaOption();

        $option[$this->getFileMetaKey()] = $meta;

        update_option(static::META_KEY, $option);
    }

    /**
     * Delete meta.
     *
     * Delete the file meta data.
     *
     * @since 2.1.0
     * @access protected
     */
    protected function deleteMeta()
    {
        $option = $this->loadMetaOption();

        $file_meta_key = $this->getFileMetaKey();

        if (isset($option[$file_meta_key])) {
            unset($option[$file_meta_key]);
        }

        if ($option) {
            update_option(static::META_KEY, $option);
        } else {
            delete_option(static::META_KEY);
        }
    }

    /**
     * @since 2.1.0
     * @access private
     */
    private function getFileMetaKey()
    {
        return pathinfo($this->getFileName(), PATHINFO_FILENAME);
    }

    /**
     * @since 2.1.0
     * @access private
     */
    private function loadMetaOption()
    {
        $option = get_option(static::META_KEY);

        if (!$option) {
            $option = [];
        }

        return $option;
    }
}
