<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VECMigrate
{
    const IDS_META_KEY = '_ce_migrate_ids';

    const MOVE_TIMEOUT = 30;

    private static $ids;

    private static function searchIds()
    {
        $table = _DB_PREFIX_ . 'creativepage';
        $rows = Db::getInstance()->executeS("SELECT id, active FROM $table ORDER BY id");
        $ids = [
            'content' => [],
            'template' => [],
        ];
        if (!empty($rows)) {
            foreach ($rows as &$row) {
                $ids[$row['active'] < 2 ? 'content' : 'template'][] = (int) $row['id'];
            }
        }
        return $ids;
    }

    public static function storeIds()
    {
        $ids = self::searchIds();
        $count = count($ids['content']) + count($ids['template']);

        if ($count > 0) {
            Configuration::updateGlobalValue('ce_migrate', $count);

            VEC\update_post_meta(0, self::IDS_META_KEY, $ids) && self::$ids = $ids;
        }
        return $count;
    }

    public static function getIds()
    {
        if (null === self::$ids) {
            self::$ids = VEC\get_post_meta(0, self::IDS_META_KEY, true);
        }
        return self::$ids;
    }

    public static function removeIds($type, $done)
    {
        $ids = self::getIds();
        $ids[$type] = array_values(array_diff($ids[$type], $done));

        if (!empty($ids['content']) || !empty($ids['template'])) {
            VEC\update_post_meta(0, self::IDS_META_KEY, $ids) && self::$ids = $ids;
        } else {
            self::deleteIds();

            Media::clearCache();
        }
        return [
            'type' => $type,
            'done' => $done,
        ];
    }

    private static function deleteIds()
    {
        Configuration::deleteByName('ce_migrate');

        VEC\delete_post_meta(0, self::IDS_META_KEY) && self::$ids = false;
    }

    private static function getJsDef()
    {
        $link = Context::getContext()->link;

        return [
            'ids' => self::getIds(),
            'count' => Configuration::getGlobalValue('ce_migrate'),
            'baseDir' => __PS_BASE_URI__,
            'ajaxUrl' => [
                'content' => $link->getAdminLink('AdminVECContent') . '&ajax=1',
                'template' => $link->getAdminLink('AdminVECTemplates') . '&ajax=1',
            ],
        ];
    }

    public static function registerJavaScripts()
    {
        $context = Context::getContext();

        if ($context->controller instanceof AdminController) {
            if (Tools::getValue('VECMigrate') == 'reset' && !self::storeIds()) {
                return;
            }
            $ce_migrate = self::getJsDef();

            Media::addJsDef([
                'ceMigrate' => $ce_migrate,
            ]);
            $context->controller->js_files[] = _MODULE_DIR_ . 'vecelements/views/js/migrate.js?v=' . _VEC_VERSION_;
        }
    }

    public static function renderJavaScripts()
    {
        $ce_migrate = json_encode(self::getJsDef());

        return VECSmarty::sprintf(_VEC_TEMPLATES_ . 'admin/admin.tpl', 'ce_inline_script', "
            $('#module-modal-import .modal-content').hide();
            window.ceMigrate = $ce_migrate;
            $.getScript(ceMigrate.baseDir + 'modules/vecelements/views/js/migrate.js');
        ");
    }

    public static function moveConfigs()
    {
        $table = _DB_PREFIX_ . 'creativepage';
        // Get old data rows
        $rows = Db::getInstance()->executeS(
            "SELECT id_shop, meta_key, meta_value FROM {$table}_meta
            WHERE id = 0 AND meta_key LIKE 'elementor_scheme_%'"
        );
        // Update configs
        if (!empty($rows)) {
            foreach ($rows as &$row) {
                $id_shop = $row['id_shop'];
                $id_group = Shop::getGroupFromShop($id_shop);

                Configuration::updateValue($row['meta_key'], $row['meta_value'], false, $id_group, $id_shop);
            }
        }
    }

    private static function isMoved($id)
    {
        $res = VEC\get_post_meta($id, '_ce_migrated', true);

        if (isset($res['started']) && $res['started'] + self::MOVE_TIMEOUT < time()) {
            return false;
        }
        return (bool) $res;
    }

    private static function startMoving($id)
    {
        return VEC\update_post_meta($id, '_ce_migrated', [
            'started' => time(),
        ]);
    }

    private static function setMoved($id)
    {
        return VEC\update_post_meta($id, '_ce_migrated', time());
    }

    public static function moveContent($id, $module)
    {
        if (self::isMoved($id)) {
            return true;
        } else {
            self::startMoving($id);
        }
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'creativepage';

        // Get old data rows
        $rows = $db->executeS(
            "SELECT * FROM $table AS a
            INNER JOIN {$table}_lang AS b ON a.id = b.id
            INNER JOIN {$table}_shop AS sa ON a.id = sa.id AND b.id_shop = sa.id_shop
            WHERE a.id = " . (int) $id
        );
        if (empty($rows)) {
            return false;
        }
        $res = true;
        $id_vec_content = null;
        $shops = [];
        // Re-structuring rows
        foreach ($rows as &$row) {
            $id_shop = $row['id_shop'];
            $id_lang = $row['id_lang'];

            if (empty($shops[$id_shop])) {
                $shops[$id_shop] = $row;
                $shops[$id_shop]['title'] = [];
                $shops[$id_shop]['data'] = [];
            }
            $shops[$id_shop]['title'][$id_lang] = $row['title'];
            $shops[$id_shop]['data'][$id_lang] = $row['data'];
        }
        foreach ($shops as $id_shop => &$row) {
            // Create VECContent if needed
            if (!$row['id_page'] || 'displayFooterProduct' == $row['type']) {
                // Insert vec_content fields
                if (!$id_vec_content) {
                    $vec_content = [
                        'id_employee' => (int) $row['id_employee'],
                        'id_product' => (int) $row['id_page'],
                        'hook' => $db->escape($row['type']),
                        'active' => $row['id_page'] ? 1 : (int) $row['active'],
                        'date_add' => $db->escape($row['date_add']),
                        'date_upd' => $db->escape($row['date_upd']),
                    ];
                    if (!$db->insert('vec_content', $vec_content)) {
                        return false;
                    }
                    $id_vec_content = $db->insert_ID();

                    // Register hook
                    if (!$row['id_page']) {
                        $module->registerHook($vec_content['hook'], array_keys($shops));
                    }
                }
                // Insert vec_content_shop fields
                $vec_content_shop = [
                    'id_vec_content' => (int) $id_vec_content,
                    'id_shop' => (int) $id_shop,
                    'active' => (int) $row['active'],
                    'date_add' => $db->escape($row['date_add']),
                    'date_upd' => $db->escape($row['date_upd']),
                ];
                if (!$db->insert('vec_content_shop', $vec_content_shop)) {
                    return false;
                }
                // Insert vec_content_lang fields
                foreach ($row['title'] as $id_lang => $title) {
                    $vec_content_lang = [
                        'id_vec_content' => (int) $id_vec_content,
                        'id_lang' => (int) $id_lang,
                        'id_shop' => (int) $id_shop,
                        'title' => $db->escape($title),
                        'content' => '',
                    ];
                    if (!$db->insert('vec_content_lang', $vec_content_lang)) {
                        return false;
                    }
                }
                $id_page = $id_vec_content;
                $id_type = VEC\UId::CONTENT;
            } else {
                $id_page = $row['id_page'];
                $id_type = VEC\UId::getTypeId($row['type']);
            }
            // Update meta data
            foreach ($row['data'] as $id_lang => &$json) {
                if ($json) {
                    $uid = new VEC\UId($id_page, $id_type, $id_lang, $id_shop);
                    $data = json_decode($json, true);

                    if ($id_vec_content || $row['id_page'] && $row['active']) {
                        $res &= VEC\update_post_meta($uid, '_elementor_edit_mode', 'builder');
                    }
                    $res &= VEC\update_post_meta($uid, '_elementor_data', $data);
                }
            }
        }
        empty($res) or self::setMoved($id);

        return $res;
    }

    public static function moveTemplate($id)
    {
        if (self::isMoved($id)) {
            return true;
        } else {
            self::startMoving($id);
        }
        $db = Db::getInstance();
        $table = _DB_PREFIX_ . 'creativepage';

        // Get old data row
        $row = $db->getRow(
            "SELECT * FROM $table AS a
            INNER JOIN {$table}_lang AS b ON a.id = b.id AND b.id_lang = 1 AND b.id_shop = 1
            WHERE a.id = " . (int) $id
        );
        if (empty($row)) {
            return false;
        }
        // Insert vec_template fields
        $res = $db->insert('vec_template', [
            'id_employee' => (int) $row['id_employee'],
            'title' => $db->escape($row['title']),
            'type' => $db->escape($row['type']),
            'active' => true,
            'date_add' => $db->escape($row['date_add']),
            'date_upd' => $db->escape($row['date_upd']),
        ]);
        // Update meta data
        if ($res) {
            $uid = new VEC\UId($db->insert_ID(), VEC\UId::TEMPLATE);
            $data = json_decode($row['data'], true);

            $res &= VEC\update_post_meta($uid, '_elementor_edit_mode', 'builder');
            $res &= VEC\update_post_meta($uid, '_elementor_data', $data);
        }
        empty($res) or self::setMoved($id);

        return $res;
    }
}
