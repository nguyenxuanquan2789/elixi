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
<script type="text/template" id="tmpl-elementor-navigator">
    <div id="elementor-navigator__header">
        <i id="elementor-navigator__toggle-all" class="eicon-expand" data-elementor-action="expand"></i>
        <div id="elementor-navigator__header__title"><?= __('Navigator') ?></div>
        <i id="elementor-navigator__close" class="eicon-close"></i>
    </div>
    <div id="elementor-navigator__elements"></div>
    <div id="elementor-navigator__footer">
        <i class="eicon-ellipsis-h"></i>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__elements">
    <# if ( obj.elType ) { #>
        <div class="elementor-navigator__item">
            <div class="elementor-navigator__element__list-toggle">
                <i class="eicon-sort-down"></i>
            </div>
            <#
            if ( icon ) { #>
                <div class="elementor-navigator__element__element-type">
                    <i class="{{{ icon }}}"></i>
                </div>
            <# } #>
            <div class="elementor-navigator__element__title">
                <span class="elementor-navigator__element__title__text">{{{ title }}}</span>
            </div>
            <# if ( 'column' !== elType ) { #>
                <div class="elementor-navigator__element__toggle">
                    <i class="eicon-eye"></i>
                </div>
            <# } #>
        </div>
    <# } #>
    <div class="elementor-navigator__elements"></div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__elements--empty">
    <div class="elementor-empty-view__title"><?= __('Empty') ?></div>
</script>

<script type="text/template" id="tmpl-elementor-navigator__root--empty">
    <i class="elementor-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
    <div class="elementor-nerd-box-title"><?= __('Easy Navigation is Here!') ?></div>
    <div class="elementor-nerd-box-message">
        <?php
        echo __(
            'Once you fill your page with content, ' .
            'this window will give you an overview display of all the page elements. ' .
            'This way, you can easily move around any section, column, or widget.'
        );
        ?>
    </div>
</script>
