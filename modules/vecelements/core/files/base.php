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

abstract class CoreXFilesXBase
{
    const UPLOADS_DIR = 'ce/';

    const DEFAULT_FILES_DIR = '';

    const META_KEY = '';

    private static $wp_uploads_dir = [];

    private $files_dir;

    private $file_name;

    /**
     * File path.
     *
     * Holds the file path.
     *
     * @access private
     *
     * @var string
     */
    private $path;

    /**
     * Content.
     *
     * Holds the file content.
     *
     * @access private
     *
     * @var string
     */
    private $content;

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function getBaseUploadsDir()
    {
        $wp_upload_dir = wp_upload_dir();

        return $wp_upload_dir['basedir'] . '/' . self::UPLOADS_DIR;
    }

    /**
     * @since 2.1.0
     * @access public
     * @static
     */
    public static function getBaseUploadsUrl()
    {
        $wp_upload_dir = wp_upload_dir();

        return $wp_upload_dir['baseurl'] . '/' . self::UPLOADS_DIR;
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function __construct($file_name)
    {
        /**
         * Elementor File Name
         *
         * Filters the File name
         *
         * @since 2.3.0
         *
         * @param string   $file_name
         * @param object $this The file instance, which inherits Elementor\Core\Files
         */
        $file_name = apply_filters('elementor/files/file_name', $file_name, $this);

        $this->setFileName($file_name);

        $this->setFilesDir(static::DEFAULT_FILES_DIR);

        $this->setPath();
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function setFilesDir($files_dir)
    {
        $this->files_dir = $files_dir;
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function getUrl()
    {
        $url = self::getBaseUploadsUrl() . $this->files_dir . $this->file_name;

        return add_query_arg(['v' => $this->getMeta('time')], $url);
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function getContent()
    {
        if (!$this->content) {
            $this->content = $this->parseContent();
        }

        return $this->content;
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function update()
    {
        $this->updateFile();

        $meta = $this->getMeta();

        $meta['time'] = time();

        $this->updateMeta($meta);
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function updateFile()
    {
        $this->content = $this->parseContent();

        if ($this->content) {
            $this->write();
        } else {
            $this->delete();
        }
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function write()
    {
        return file_put_contents($this->path, $this->content);
    }

    /**
     * @since 2.1.0
     * @access public
     */
    public function delete()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }

        $this->deleteMeta();
    }

    /**
     * Get meta data.
     *
     * Retrieve the CSS file meta data. Returns an array of all the data, or if
     * custom property is given it will return the property value, or `null` if
     * the property does not exist.
     *
     * @since 2.1.0
     * @access public
     *
     * @param string $property Optional. Custom meta data property. Default is
     *                         null.
     *
     * @return array|null An array of all the data, or if custom property is
     *                    given it will return the property value, or `null` if
     *                    the property does not exist.
     */
    public function getMeta($property = null)
    {
        $default_meta = $this->getDefaultMeta();

        $meta = array_merge($default_meta, (array) $this->loadMeta());

        if ($property) {
            return isset($meta[$property]) ? $meta[$property] : null;
        }

        return $meta;
    }

    /**
     * @since 2.1.0
     * @access protected
     * @abstract
     */
    abstract protected function parseContent();

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
        return get_option(static::META_KEY);
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
        update_option(static::META_KEY, $meta);
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
        delete_option(static::META_KEY);
    }

    /**
     * @since 2.1.0
     * @access protected
     */
    protected function getDefaultMeta()
    {
        return [
            'time' => 0,
        ];
    }

    // private static function getWpUploadsDir()

    /**
     * @since 2.1.0
     * @access private
     */
    private function setPath()
    {
        $dir_path = self::getBaseUploadsDir() . $this->files_dir;

        if (!is_dir($dir_path)) {
            @mkdir($dir_path, 0775, true);
        }

        $this->path = $dir_path . $this->file_name;
    }
}
