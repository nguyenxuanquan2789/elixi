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
<script type="text/template" id="tmpl-elementor-repeater-row">
    <div class="elementor-repeater-row-tools">
        <# if ( itemActions.drag_n_drop ) {  #>
            <div class="elementor-repeater-row-handle-sortable">
                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?= __('Drag & Drop') ?></span>
            </div>
        <# } #>
        <div class="elementor-repeater-row-item-title"></div>
        <# if ( itemActions.duplicate ) {  #>
            <div class="elementor-repeater-row-tool elementor-repeater-tool-duplicate">
                <i class="fa fa-copy" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?= __('Duplicate') ?></span>
            </div>
        <# }
        if ( itemActions.remove ) {  #>
            <div class="elementor-repeater-row-tool elementor-repeater-tool-remove">
                <i class="fa fa-remove" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?= __('Remove') ?></span>
            </div>
        <# } #>
    </div>
    <div class="elementor-repeater-row-controls"></div>
</script>
