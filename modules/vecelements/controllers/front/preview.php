<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

class VecElementsPreviewModuleFrontController extends ModuleFrontController
{
    protected $uid;

    protected $title;

    public function init()
    {
        if (Tools::getIsset('redirect') && VecElements::hasAdminToken('AdminVECEditor')) {
            $cookie = VEC\get_post_meta(0, 'cookie', true);
            VEC\delete_post_meta(0, 'cookie');

            if (!empty($cookie)) {
                $lifetime = max(1, (int) Configuration::get('PS_COOKIE_LIFETIME_BO')) * 3600 + time();
                $admin = new Cookie('psAdmin', '', $lifetime);

                foreach ($cookie as $key => &$value) {
                    $admin->$key = $value;
                }
                unset($admin->remote_addr);

                $admin->write();
            }
            Tools::redirectAdmin(urldecode(Tools::getValue('redirect')));
        }

        $this->uid = VecElements::getPreviewUId(false);

        if (!$this->uid) {
            Tools::redirect('index.php?controller=404');
        }

        parent::init();
    }

    public function initContent()
    {
        $model = $this->uid->getModel();
        //$hook_name = \Tools::strtolower(\VECContent::getHookById($this->uid->id));

        // if ('VECTemplate' != $model && $hook_name != 'display404pagebuilder') {
        //     $this->warning[] = VECSmarty::get(_VEC_TEMPLATES_ . 'admin/admin.tpl', 'ce_undefined_position');
        // }
        $post = VEC\get_post($this->uid);

        $this->title = $post->post_title;
        $this->context->smarty->assign($model::${'definition'}['table'], [
            'id' => $post->_obj->id,
            'content' => '',
        ]);

        parent::initContent();

        $this->title = $post->post_title;
        $this->context->smarty->addTemplateDir(_VEC_TEMPLATES_);
        $this->context->smarty->assign([
            'HOOK_LEFT_COLUMN' => '',
            'HOOK_RIGHT_COLUMN' => '',
        ]);

        
        $this->context->smarty->assign('breadcrumb', $this->getBreadcrumb());
        $this->template = 'front/preview.tpl';
        
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = [
            'links' => [
                ['url' => 'javascript:;', 'title' => 'V-Elements'],
                ['url' => 'javascript:;', 'title' => VEC\__('Preview')],
            ],
        ];
        if (!empty($this->title)) {
            $breadcrumb['links'][] = ['url' => 'javascript:;', 'title' => $this->title];
        }
        return $breadcrumb;
    }

    public function getBreadcrumbPath()
    {
        $breadcrumb = $this->getBreadcrumbLinks();

        return VECSmarty::capture(_VEC_TEMPLATES_ . 'admin/admin.tpl', 'ce_preview_breadcrumb', [
            'links' => $breadcrumb['links'],
        ]);
    }
}
