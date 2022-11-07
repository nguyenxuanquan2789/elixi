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
use VEC\CoreXFilesXCSSXGlobalCSS as GlobalCSS;
use VEC\CoreXFilesXCSSXPost as PostCSS;
use VEC\CoreXResponsiveXFilesXFrontend as Frontend;

/**
 * Elementor files manager.
 *
 * Elementor files manager handler class is responsible for creating files.
 *
 * @since 1.2.0
 */
class CoreXFilesXManager
{
    /**
     * Files manager constructor.
     *
     * Initializing the Elementor files manager.
     *
     * @since 1.2.0
     * @access public
     */
    public function __construct()
    {
        $this->registerActions();
    }

    /**
     * On post delete.
     *
     * Delete post CSS immediately after a post is deleted from the database.
     *
     * Fired by `deleted_post` action.
     *
     * @since 1.2.0
     * @access public
     *
     * @param string $post_id Post ID.
     */
    public function onDeletePost($post_id)
    {
        if (!Utils::isPostSupport($post_id)) {
            return;
        }

        $css_file = new PostCSS($post_id);

        $css_file->delete();
    }

    /**
     * On export post meta.
     *
     * When exporting data using WXR, skip post CSS file meta key. This way the
     * export won't contain the post CSS file data used by Elementor.
     *
     * Fired by `wxr_export_skip_postmeta` filter.
     *
     * @since 1.2.0
     * @access public
     *
     * @param bool   $skip     Whether to skip the current post meta.
     * @param string $meta_key Current meta key.
     *
     * @return bool Whether to skip the post CSS meta.
     */
    public function onExportPostMeta($skip, $meta_key)
    {
        if (PostCSS::META_KEY === $meta_key) {
            $skip = true;
        }

        return $skip;
    }

    /**
     * Clear cache.
     *
     * Delete all meta containing files data. And delete the actual
     * files from the upload directory.
     *
     * @since 1.2.0
     * @access public
     */
    public function clearCache()
    {
        if (\Shop::getContext() == \Shop::CONTEXT_ALL) {
            \Configuration::deleteByName(GlobalCSS::META_KEY);
            \Configuration::deleteByName(Frontend::META_KEY);
        } else {
            \Configuration::deleteFromContext(GlobalCSS::META_KEY);
            \Configuration::deleteFromContext(Frontend::META_KEY);
        }

        $db = \Db::getInstance();
        $table = _DB_PREFIX_ . 'vec_meta';

        foreach (\Shop::getContextListShopID() as $id_shop) {
            $id_shop = (int) $id_shop;
            $id = sprintf('%02d', $id_shop);
            $meta_key = $db->escape(PostCSS::META_KEY);

            $db->execute("DELETE FROM $table WHERE id LIKE '%$id' AND name = '$meta_key'");

            // Delete files.
            $path = Base::getBaseUploadsDir() . Base::DEFAULT_FILES_DIR;

            foreach (glob("{$path}*$id.css", GLOB_NOSORT) as $file_path) {
                \Tools::deleteFile($file_path);
            }
            foreach (glob("{$path}$id_shop-*.css", GLOB_NOSORT) as $file_path) {
                \Tools::deleteFile($file_path);
            }
        }

        /**
         * Elementor clear files.
         *
         * Fires after Elementor clears files
         *
         * @since 2.1.0
         */
        do_action('elementor/core/files/clear_cache');
    }

    /**
     * Register actions.
     *
     * Register filters and actions for the files manager.
     *
     * @since 1.2.0
     * @access private
     */
    private function registerActions()
    {
        add_action('deleted_post', [$this, 'on_delete_post']);
        add_filter('wxr_export_skip_postmeta', [$this, 'on_export_post_meta'], 10, 2);
    }
}
