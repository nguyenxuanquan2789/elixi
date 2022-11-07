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

class CoreXDocumentTypesXPageNotFound extends ThemePageDocument
{
    public function getName()
    {
        return 'page-not-found';
    }

    public static function getTitle()
    {
        return __('404 Page');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['category'] = '404 error';

        return $config;
    }
}
