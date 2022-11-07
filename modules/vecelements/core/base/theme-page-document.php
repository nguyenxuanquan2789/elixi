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

use VEC\CoreXBaseXThemeDocument as ThemeDocument;
use VEC\CoreXDocumentTypesXPost as Post;
use VEC\TemplateLibraryXSourceLocal as SourceLocal;

abstract class CoreXBaseXThemePageDocument extends ThemeDocument
{
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        Post::registerPostFieldsControl($this);

        Post::registerStyleControls($this);
    }
}
