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
<script type="text/template" id="tmpl-elementor-panel-revisions">
    <div class="elementor-panel-box">
        <div class="elementor-panel-scheme-buttons">
            <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-discard">
                <button class="elementor-button" disabled>
                    <i class="fa fa-times" aria-hidden="true"></i>
                    <?= __('Discard') ?>
                </button>
            </div>
            <div class="elementor-panel-scheme-button-wrapper elementor-panel-scheme-save">
                <button class="elementor-button elementor-button-success" disabled>
                    <?= __('Apply') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="elementor-panel-box">
        <div class="elementor-panel-heading">
            <div class="elementor-panel-heading-title"><?= __('Revisions') ?></div>
        </div>
        <div id="elementor-revisions-list" class="elementor-panel-box-content"></div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-no-revisions">
    <i class="elementor-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
    <div class="elementor-nerd-box-title"><?= __('No Revisions Saved Yet') ?></div>
    <div class="elementor-nerd-box-message">
        {{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_1' : 'revisions_disabled_1' ) }}}
    </div>
    <div class="elementor-nerd-box-message">
        {{{ elementor.translate( elementor.config.revisions_enabled ? 'no_revisions_2' : 'revisions_disabled_2' ) }}}
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-loading">
    <i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-elementor-panel-revisions-revision-item">
    <div class="elementor-revision-item__wrapper {{ type }}">
        <div class="elementor-revision-item__gravatar">{{{ gravatar }}}</div>
        <div class="elementor-revision-item__details">
            <div class="elementor-revision-date">{{{ date }}}</div>
            <div class="elementor-revision-meta">
                <span>{{{ elementor.translate(type) }}}</span><# if (author) { #> <?= __('By') ?> {{{ author }}}<# } #>
            </div>
        </div>
        <div class="elementor-revision-item__tools">
        <# if ( 'current' === type ) { #>
            <i class="elementor-revision-item__tools-current fa fa-star" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?= __('Current') ?></span>
        <# } else { #>
            <i class="elementor-revision-item__tools-delete fa fa-times" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?= __('Delete') ?></span>
        <# } #>
            <i class="elementor-revision-item__tools-spinner fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
        </div>
    </div>
</script>
