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
?>
<script type="text/template" id="tmpl-elementor-panel-history-page">
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
        <div id="elementor-panel-elements-navigation-history"
            class="elementor-panel-navigation-tab elementor-active" data-view="history"><?= __('Actions') ?></div>
        <div id="elementor-panel-elements-navigation-revisions"
            class="elementor-panel-navigation-tab" data-view="revisions"><?= __('Revisions') ?></div>
    </div>
    <div id="elementor-panel-history-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-tab">
    <div id="elementor-history-list"></div>
    <div class="elementor-history-revisions-message"><?= __('Switch to Revisions tab for older versions') ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-no-items">
    <i class="elementor-nerd-box-icon eicon-nerd"></i>
    <div class="elementor-nerd-box-title"><?= __('No History Yet') ?></div>
    <div class="elementor-nerd-box-message">
        <?= __('Once you start working, you\'ll be able to redo / undo any action you make in the editor.') ?>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-history-item">
    <div class="elementor-history-item__details">
        <span class="elementor-history-item__title">{{{ title }}}</span>
        <span class="elementor-history-item__subtitle">{{{ subTitle }}}</span>
        <span class="elementor-history-item__action">{{{ action }}}</span>
    </div>
    <div class="elementor-history-item__icon">
        <span class="fa" aria-hidden="true"></span>
    </div>
</script>
