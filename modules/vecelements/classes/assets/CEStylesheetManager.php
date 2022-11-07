<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

use PrestaShop\PrestaShop\Core\ConfigurationInterface;

defined('_PS_VERSION_') or exit;

class CEStylesheetManager extends StylesheetManager
{
    public function __construct(array $directories, ConfigurationInterface $configuration, $list = null)
    {
        parent::__construct($directories, $configuration);

        is_null($list) or $this->list = $list;
    }

    public function getList()
    {
        return parent::getDefaultList();
    }

    public function listAll()
    {
        return parent::getList();
    }
}
