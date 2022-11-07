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

use VEC\CoreXDocumentTypesXPost as Post;
use VEC\ModulesXLibraryXDocumentsXLibraryDocument as LibraryDocument;

/**
 * Elementor page library document.
 *
 * Elementor page library document handler class is responsible for
 * handling a document of a page type.
 *
 * @since 2.0.0
 */
class ModulesXLibraryXDocumentsXPage extends LibraryDocument
{
    /**
     * Get document properties.
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
        $properties = parent::getProperties();

        $properties['support_wp_page_templates'] = true;

        return $properties;
    }

    /**
     * Get document name.
     *
     * Retrieve the document name.
     *
     * @since 2.0.0
     * @access public
     *
     * @return string Document name.
     */
    public function getName()
    {
        return 'page';
    }

    /**
     * Get document title.
     *
     * Retrieve the document title.
     *
     * @since 2.0.0
     * @access public
     * @static
     *
     * @return string Document title.
     */
    public static function getTitle()
    {
        return __('Page');
    }

    /**
     * @since 2.1.3
     * @access public
     */
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    /**
     * @since 2.0.0
     * @access protected
     */
    protected function _registerControls()
    {
        parent::_registerControls();

        Post::registerHideTitleControl($this);

        Post::registerStyleControls($this);
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['type'] = 'page';

        return $config;
    }
}
