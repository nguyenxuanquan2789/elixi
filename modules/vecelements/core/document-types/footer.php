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

use VEC\CoreXBaseXHeaderFooterBase as HeaderFooterBase;

class CoreXDocumentTypesXFooter extends HeaderFooterBase
{
    public static function getProperties()
    {
        $properties = parent::getProperties();

        $properties['location'] = 'footer';

        return $properties;
    }

    public function getName()
    {
        return 'footer';
    }

    public static function getTitle()
    {
        return __('Footer');
    }
}
