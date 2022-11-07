<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

use VEC\CoreXBaseXThemePageDocument as ThemePageDocument;

class CoreXDocumentTypesXPageIndex extends ThemePageDocument
{
    public function getName()
    {
        return 'page-index';
    }

    public static function getTitle()
    {
        return __('Home Page');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['type'] = 'page';

        return $config;
    }
}
