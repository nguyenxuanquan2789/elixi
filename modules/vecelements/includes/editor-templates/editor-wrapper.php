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

$favicon = _PS_IMG_ . \Configuration::get('PS_FAVICON') . '?' . \Configuration::get('PS_IMG_UPDATE_TIME');

$document = Plugin::$instance->documents->getCurrent();

$body_classes = [
    'elementor-editor-active',
    'elementor-editor-' . $document->getTemplateType(),
    'ps-version-' . str_replace('.', '-', _PS_VERSION_),
];

if (is_rtl()) {
    $body_classes[] = 'rtl';
}

// if (!Plugin::$instance->role_manager->userCan('design')) {
//     $body_classes[] = 'elementor-editor-content-only';
// }

ob_start();
?><!DOCTYPE html>
<html lang="<?= get_locale() ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if (\Tools::usingSecureMode()) : ?>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<?php endif ?>
    <title><?= __('V-Elements - Live page builder') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= esc_attr($favicon) ?>">
    <?php do_action('wp_head') ?>
    <script>
        var ajaxurl = '<?= Helper::getAjaxLink() ?>';
    </script>
</head>
<body class="<?= implode(' ', $body_classes) ?>">
    <div id="elementor-editor-wrapper">
        <div id="elementor-panel" class="elementor-panel"></div>
        <div id="elementor-preview">
            <div id="elementor-loading">
                <div class="elementor-loader-wrapper">
                    <div class="elementor-loader">
                        <div class="elementor-loader-boxes">
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                            <div class="elementor-loader-box"></div>
                        </div>
                    </div>
                    <div class="elementor-loading-title"><?= __('Loading') ?></div>
                </div>
            </div>
            <div id="elementor-preview-responsive-wrapper"
                class="elementor-device-desktop elementor-device-rotate-portrait">
                <div id="elementor-preview-loading">
                    <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
                </div>
                <?php // Frame will be create here by the Javascript later. ?>
            </div>
        </div>
        <div id="elementor-navigator"></div>
    </div>
    <?php
    do_action('wp_footer');
    do_action('admin_print_footer_scripts');
    ?>
</body>
</html>
<?php
ob_flush();
