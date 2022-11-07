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

use VEC\ModulesXLibraryXDocumentsXLibraryDocument as LibraryDocument;
use VEC\TemplateLibraryXSourceLocal as SourceLocal;

/**
 * Elementor section library document.
 *
 * Elementor section library document handler class is responsible for
 * handling a document of a section type.
 *
 */
class ModulesXLibraryXDocumentsXNotSupported extends LibraryDocument
{
    /**
     * Get document properties.
     *
     * Retrieve the document properties.
     *
     * @access public
     * @static
     *
     * @return array Document properties.
     */
    public static function getProperties()
    {
        $properties = parent::getProperties();

        // $properties['admin_tab_group'] = '';
        $properties['register_type'] = false;
        $properties['is_editable'] = false;
        $properties['show_in_library'] = false;

        $properties['cpt'] = [
            SourceLocal::CPT,
        ];

        return $properties;
    }

    /**
     * Get document name.
     *
     * Retrieve the document name.
     *
     * @access public
     *
     * @return string Document name.
     */
    public function getName()
    {
        return 'not-supported';
    }

    /**
     * Get document title.
     *
     * Retrieve the document title.
     *
     * @access public
     * @static
     *
     * @return string Document title.
     */
    public static function getTitle()
    {
        return __('Not Supported');
    }

    public function saveTemplateType()
    {
        // Do nothing.
    }

    // public function printAdminColumnType()

    // public function filterAdminRowActions($actions)
}
