<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'vecelements/classes/VECTemplate.php';

class AdminVECTemplatesController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'vec_template';

    public $identifier = 'id_vec_template';

    public $className = 'VECTemplate';

    protected $_defaultOrderBy = 'title';

    public function __construct()
    {
        parent::__construct();
    }

    public function processBulkExport()
    {
        $uids = [];

        foreach ($this->boxes as $id) {
            $uids[] = new VEC\UId($id, VEC\UId::TEMPLATE);
        }

        VEC\Plugin::instance()->templates_manager->getSource('local')->exportMultipleTemplates($uids);
    }

    protected function processUpdateOptions()
    {
        // Process import template
        VEC\UId::$_ID = new VEC\UId(0, VEC\UId::TEMPLATE);

        $res = VEC\Plugin::instance()->templates_manager->directImportTemplate();

        if ($res instanceof VEC\WPError) {
            $this->errors[] = $res->getMessage();
        } elseif (isset($res[1]['template_id'])) {
            // More templates
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminVECTemplates') . '&conf=18');
        } elseif (isset($res[0]['template_id'])) {
            // Simple template
            $id = Tools::substr($res[0]['template_id'], 0, -6);

            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminVECTemplates') . "&id_vec_template=$id&updatevec_template&conf=18"
            );
        } else {
            $this->errors[] = $this->l('Unknown error during import!');
        }
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _VEC_PATH_ . 'classes/VECMigrate.php';

            $done = [];

            foreach ($ids as $id) {
                VECMigrate::moveTemplate($id) && $done[] = (int) $id;
            }
            $res = VECMigrate::removeIds('template', $done);

            die(json_encode($res));
        }
    }
}
