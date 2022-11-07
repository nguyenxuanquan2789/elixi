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

use VEC\CoreXBaseXDocument as Document;
use VEC\TemplateLibraryXSourceLocal as SourceLocal;

/**
 * Elementor library document.
 *
 * Elementor library document handler class is responsible for handling
 * a document of the library type.
 *
 * @since 2.0.0
 */
abstract class ModulesXLibraryXDocumentsXLibraryDocument extends Document
{
    // const TAXONOMY_TYPE_SLUG = 'elementor_library_type';

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

        // $properties['admin_tab_group'] = 'library';
        $properties['show_in_library'] = true;
        $properties['register_type'] = true;

        return $properties;
    }

    public function _getInitialConfig()
    {
        $config = parent::_getInitialConfig();

        $config['library'] = [
            'save_as_same_type' => true,
        ];

        return $config;
    }

    // public function printAdminColumnType()

    /**
     * Save document type.
     *
     * Set new/updated document type.
     *
     * @since 2.0.0
     * @access public
     */
    public function saveTemplateType()
    {
        parent::saveTemplateType();

        // wp_set_object_terms($this->post->ID, $this->getName(), self::TAXONOMY_TYPE_SLUG);
    }
}
