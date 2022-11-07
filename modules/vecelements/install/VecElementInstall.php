<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VecElementInstall
{
    private static $hooks = [
        'displayBackOfficeHeader',
        'displayHeader',
        'displayFooterProduct',
        'overrideLayoutTemplate',
        'VECTemplate',
        // Actions
        'actionObjectVECRevisionDeleteAfter',
        'actionObjectVECTemplateDeleteAfter',
        'actionObjectVECThemeDeleteAfter',
        'actionObjectVECContentDeleteAfter',
        'actionObjectProductDeleteAfter',
        'actionObjectCategoryDeleteAfter',
        'actionObjectManufacturerDeleteAfter',
        'actionObjectSupplierDeleteAfter',
        'actionObjectCmsDeleteAfter',
        'actionObjectCmsCategoryDeleteAfter',
        'actionObjectYbc_blog_post_classDeleteAfter',
        'actionObjectXipPostsClassDeleteAfter',
        'actionObjectStBlogClassDeleteAfter',
        'actionObjectBlogPostsDeleteAfter',
        'actionObjectNewsClassDeleteAfter',
        'actionObjectTvcmsBlogPostsClassDeleteAfter',
        'actionProductAdd',
    ];

    public static function initConfigs()
    {
        $defaults = [
            // General
            'elementor_frontend_edit' => 1,
            'elementor_max_revisions' => 0,
            // Style
            'elementor_default_generic_fonts' => 'sans-serif',
            'elementor_container_width' => 1140, //update it using themeoptions
            'elementor_space_between_widgets' => 20,
            'elementor_page_title_selector' => 'header.page-header h1',
            'elementor_page_wrapper_selector' => '#wrapper, #wrapper .container, #content'
            ,
            'elementor_viewport_lg' => 1025,
            'elementor_viewport_md' => 768,
        ];
        foreach ($defaults as $key => $value) {
            Configuration::hasKey($key) or Configuration::updateValue($key, $value);
        }
    }

    public static function createTables()
    {
        $db = Db::getInstance();
        $vec_revision = _DB_PREFIX_ . 'vec_revision';
        $vec_template = _DB_PREFIX_ . 'vec_template';
        $vec_content = _DB_PREFIX_ . 'vec_content';
        $vec_theme = _DB_PREFIX_ . 'vec_theme';
        $vec_meta = _DB_PREFIX_ . 'vec_meta';
        $engine = _MYSQL_ENGINE_;

        return $db->execute("
            CREATE TABLE IF NOT EXISTS `$vec_revision` (
                `id_vec_revision` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent` bigint(20) UNSIGNED NOT NULL,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(255) NOT NULL,
                `type` varchar(64) NOT NULL DEFAULT '',
                `content` longtext NOT NULL,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_revision`),
                KEY `id` (`parent`),
                KEY `date_add` (`date_upd`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$vec_template` (
                `id_vec_template` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `type` varchar(64) NOT NULL DEFAULT '',
                `content` longtext,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_template`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$vec_content` (
                `id_vec_content` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `id_product` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `hook` varchar(64) NOT NULL DEFAULT '',
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_content`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$vec_content}_shop` (
                `id_vec_content` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_content`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$vec_content}_lang` (
                `id_vec_content` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `content` longtext,
                PRIMARY KEY (`id_vec_content`,`id_shop`,`id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$vec_theme` (
                `id_vec_theme` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `type` varchar(64) NOT NULL DEFAULT '',
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_theme`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$vec_theme}_shop` (
                `id_vec_theme` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_vec_theme`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$vec_theme}_lang` (
                `id_vec_theme` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `content` text,
                PRIMARY KEY (`id_vec_theme`,`id_shop`,`id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$vec_meta` (
                `id_vec_meta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `value` longtext,
                PRIMARY KEY (`id_vec_meta`),
                KEY `id` (`id`),
                KEY `name` (`name`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ");
    }

    public static function getHooks($all = true)
    {
        $hooks = self::$hooks;

        if ($all) {
            $vec_content = _DB_PREFIX_ . 'vec_content';
            $rows = Db::getInstance()->executeS("SELECT DISTINCT hook FROM $vec_content");

            if (!empty($rows)) {
                foreach ($rows as &$row) {
                    $hook = $row['hook'];

                    if ($hook && !in_array($hook, $hooks)) {
                        $hooks[] = $hook;
                    }
                }
            }
        }
        return $hooks;
    }
    
}