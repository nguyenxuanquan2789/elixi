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

use VEC\CoreXBaseXModule as BaseModule;
use VEC\ModulesXLibraryXDocumentsXNotSupported as NotSupported;
use VEC\ModulesXLibraryXDocumentsXPage as Page;
use VEC\ModulesXLibraryXDocumentsXSection as Section;

/**
 * Elementor library module.
 *
 * Elementor library module handler class is responsible for registering and
 * managing Elementor library modules.
 *
 * @since 2.0.0
 */
class ModulesXLibraryXModule extends BaseModule
{
    /**
     * Get module name.
     *
     * Retrieve the library module name.
     *
     * @since 2.0.0
     * @access public
     *
     * @return string Module name.
     */
    public function getName()
    {
        return 'library';
    }

    /**
     * Library module constructor.
     *
     * Initializing Elementor library module.
     *
     * @since 2.0.0
     * @access public
     */
    public function __construct()
    {
        Plugin::$instance->documents
            ->registerDocumentType('not-supported', NotSupported::getClassFullName())
            ->registerDocumentType('page', Page::getClassFullName())
            ->registerDocumentType('section', Section::getClassFullName());
    }
}
