<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

use VEC\CoreXBaseXModule as BaseModule;

class ModulesXCreativeXModule extends BaseModule
{
    public function getName()
    {
        return 'creative';
    }

    public function enqueuePreviewScripts()
    {
        // Redirect to preview page when edited hook is missing
        $uid = \Tools::getValue('preview_id');

        if ($uid && UId::CONTENT === UId::parse($uid)->id_type) {
            $tab = 'AdminVECContent';
            $id_employee = (int) \Tools::getValue('id_employee');

            wp_register_script(
                'editor-preview',
                _VEC_ASSETS_URL_ . 'js/editor-preview.js',
                [],
                _VEC_VERSION_,
                true
            );

            wp_localize_script(
                'editor-preview',
                'cePreview',
                \Context::getContext()->link->getModuleLink('vecelements', 'preview', [
                    'id_employee' => $id_employee,
                    'adtoken' => \Tools::getAdminToken($tab . (int) \Tab::getIdFromClassName($tab) . $id_employee),
                    'preview_id' => $uid,
                    'ctx' => (int) \Tools::getValue('ctx'),
                ], null, null, null, true)
            );

            wp_enqueue_script('editor-preview');
        }
    }

    public function enqueueFrontendScripts()
    {
        // Add Quick Edit button on frontend when employee has active session
        if ($editor = $this->getEditorLink()) {
            wp_register_script('frontend-edit', _VEC_ASSETS_URL_ . 'js/frontend-edit.js', [], _VEC_VERSION_);
            wp_localize_script('frontend-edit', 'ceFrontendEdit', [
                'editor_url' => $editor,
                'edit_title' => __('Edit with V-Elements'),
            ]);
            wp_enqueue_script('frontend-edit');
            wp_enqueue_style('frontend-edit', _VEC_ASSETS_URL_ . 'css/frontend-edit.css', [], _VEC_VERSION_);
        }
    }

    private function getEditorLink()
    {
        static $link;

        if (null === $link) {
            $link = '';

            if (\Configuration::get('elementor_frontend_edit') &&
                ($id_employee = get_current_user_id()) &&
                ($dir = glob(_PS_ROOT_DIR_ . '/*/filemanager', GLOB_ONLYDIR))
            ) {
                $tab = 'AdminVECEditor';
                $link = __PS_BASE_URI__ . basename(dirname($dir[0])) . '/index.php?' . http_build_query([
                    'controller' => $tab,
                    'token' => \Tools::getAdminToken($tab . (int) \Tab::getIdFromClassName($tab) . $id_employee),
                ]);
            }
        }
        return $link;
    }

    public function preloadFonts()
    {
        $lib = _MODULE_DIR_ . 'vecelements/views/lib';
        ?>
        <link rel="preload" href="<?= esc_attr("$lib/ceicons/fonts/ceicons.woff2?t6ebnx") ?>"
            as="font" type="font/woff2" crossorigin>
        
        <link rel="preload" href="<?= esc_attr("$lib/font-awesome/fonts/fontawesome-webfont.woff2?v=4.7.0") ?>"
            as="font" type="font/woff2" crossorigin>
        <?php
    }

    public function __construct()
    {
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueuePreviewScripts']);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueueFrontendScripts']);

        if (!is_admin()) {
            add_action('wp_head', [$this, 'preloadFonts']);
        }
    }
}
