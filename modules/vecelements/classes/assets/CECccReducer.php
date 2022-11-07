<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or exit;

class CECccReducer extends CccReducer
{
    public function reduceCss($cssFileList)
    {
        return empty($cssFileList['external']) ? $cssFileList : parent::reduceCss($cssFileList);
    }
}
