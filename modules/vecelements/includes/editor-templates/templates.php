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
<script type="text/template" id="tmpl-elementor-template-library-header-actions">
    <div id="elementor-template-library-header-import" class="elementor-templates-modal__header__item">
        <i class="eicon-upload-circle-o" aria-hidden="true" title="<?= esc_attr__('Import Template') ?>"></i>
        <span class="elementor-screen-only"><?= __('Import Template') ?></span>
    </div>
    <div id="elementor-template-library-header-sync" class="elementor-templates-modal__header__item">
        <i class="eicon-sync" aria-hidden="true" title="<?= esc_attr__('Sync Library') ?>"></i>
        <span class="elementor-screen-only"><?= __('Sync Library') ?></span>
    </div>
    <div id="elementor-template-library-header-save" class="elementor-templates-modal__header__item">
        <i class="eicon-save-o" aria-hidden="true" title="<?= esc_attr__('Save') ?>"></i>
        <span class="elementor-screen-only"><?= __('Save') ?></span>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-preview">
    <div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
        {{{ elementor.templates.getLayout().getTemplateActionButton( obj ) }}}
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-header-back">
    <i class="eicon-" aria-hidden="true"></i>
    <span><?= __('Back to Library') ?></span>
</script>

<script type="text/template" id="tmpl-elementor-template-library-loading">
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
</script>

<?php /** @codingStandardsIgnoreStart Generic.Files.LineLength */ ?>
<script type="text/template" id="tmpl-elementor-template-library-header-menu">
<# screens.forEach( ( screen ) => { #>
    <div class="elementor-template-library-menu-item" data-template-source="{{{ screen.source }}}"{{{ screen.type ? ' data-template-type="' + screen.type + '"' : '' }}}>{{{ screen.title }}}</div>
<# } ); #>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates">
    <# var activeSource = elementor.templates.getFilter('source'); #>
    <div id="elementor-template-library-toolbar">
        
        <div id="elementor-template-library-filter-toolbar-local" class="elementor-template-library-filter-toolbar"></div>

    </div>
    <# if ( 'local' === activeSource ) { #>
        <div id="elementor-template-library-order-toolbar-local">
            <div class="elementor-template-library-local-column-1">
                <input type="radio" id="elementor-template-library-order-local-title" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="title" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-title" class="elementor-template-library-order-label"><?= __('Name') ?></label>
            </div>
            <div class="elementor-template-library-local-column-2">
                <input type="radio" id="elementor-template-library-order-local-type" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="type" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-type" class="elementor-template-library-order-label"><?= __('Type') ?></label>
            </div>
            <div class="elementor-template-library-local-column-3">
                <input type="radio" id="elementor-template-library-order-local-author" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="author" data-default-ordering-direction="asc">
                <label for="elementor-template-library-order-local-author" class="elementor-template-library-order-label"><?= __('Created By') ?></label>
            </div>
            <div class="elementor-template-library-local-column-4">
                <input type="radio" id="elementor-template-library-order-local-date" class="elementor-template-library-order-input" name="elementor-template-library-order-local" value="date">
                <label for="elementor-template-library-order-local-date" class="elementor-template-library-order-label"><?= __('Creation Date') ?></label>
            </div>
            <div class="elementor-template-library-local-column-5">
                <div class="elementor-template-library-order-label"><?= __('Actions') ?></div>
            </div>
        </div>
    <# } #>
    <div id="elementor-template-library-templates-container"></div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-remote">
    <div class="elementor-template-library-template-body">
        <# if ( 'page' === type ) { #>
            <div class="elementor-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
        <# } else { #>
            <img src="{{ thumbnail }}">
        <# } #>
        <div class="elementor-template-library-template-preview">
            <i class="fa fa-search-plus" aria-hidden="true"></i>
        </div>
    </div>
    <div class="elementor-template-library-template-footer">
        {{{ elementor.templates.getLayout().getTemplateActionButton( obj ) }}}
        <div class="elementor-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
        <div class="elementor-template-library-favorite">
            <input id="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
            <label for="elementor-template-library-template-{{ template_id }}-favorite-input" class="elementor-template-library-template-favorite-label">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
                <span class="elementor-screen-only"><?= __('Favorite') ?></span>
            </label>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-template-local">
    <div class="elementor-template-library-template-name elementor-template-library-local-column-1">{{{ title }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-type elementor-template-library-local-column-2">{{{ elementor.translate( type ) }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-author elementor-template-library-local-column-3">{{{ author }}}</div>
    <div class="elementor-template-library-template-meta elementor-template-library-template-date elementor-template-library-local-column-4">{{{ human_date }}}</div>
    <div class="elementor-template-library-template-controls elementor-template-library-local-column-5">
        <div class="elementor-template-library-template-preview">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <span class="elementor-template-library-template-control-title"><?= __('Preview') ?></span>
        </div>
        <button class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button elementor-button-success">
            <i class="eicon-file-download" aria-hidden="true"></i>
            <span class="elementor-button-title"><?= __('Insert') ?></span>
        </button>
        <div class="elementor-template-library-template-more-toggle">
            <i class="eicon-ellipsis-h" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?= __('More actions') ?></span>
        </div>
        <div class="elementor-template-library-template-more">
            <div class="elementor-template-library-template-delete">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
                <span class="elementor-template-library-template-control-title"><?= __('Delete') ?></span>
            </div>
            <div class="elementor-template-library-template-export">
                <a href="{{ export_link }}">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    <span class="elementor-template-library-template-control-title"><?= __('Export') ?></span>
                </a>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-template-library-insert-button">
    <a class="elementor-template-library-template-action elementor-template-library-template-insert elementor-button">
        <i class="eicon-file-download" aria-hidden="true"></i>
        <span class="elementor-button-title"><?= __('Insert') ?></span>
    </a>
</script>

<script type="text/template" id="tmpl-elementor-template-library-save-template">
    <div class="elementor-template-library-blank-icon">
        <i class="eicon-library-save" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?= __('Save') ?></span>
    </div>
    <div class="elementor-template-library-blank-title">{{{ title }}}</div>
    <div class="elementor-template-library-blank-message">{{{ description }}}</div>
    <form id="elementor-template-library-save-template-form">
        <input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
        <input id="elementor-template-library-save-template-name" name="title" placeholder="<?= esc_attr__('Enter Template Name') ?>" required>
        <button id="elementor-template-library-save-template-submit" class="elementor-button elementor-button-success">
            <span class="elementor-state-icon">
                <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
            </span>
            <?= __('Save') ?>
        </button>
    </form>
    <!--div class="elementor-template-library-blank-footer">
        <?= __('Want to learn more about the Elementor library?') ?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?= __('Click here') ?></a>
    </div-->
</script>

<script type="text/template" id="tmpl-elementor-template-library-import">
    <form id="elementor-template-library-import-form">
        <div class="elementor-template-library-blank-icon">
            <i class="eicon-library-upload" aria-hidden="true"></i>
        </div>
        <div class="elementor-template-library-blank-title"><?= __('Import Template to Your Library') ?></div>
        <div class="elementor-template-library-blank-message"><?= __('Drag & drop your .JSON or .zip template file') ?></div>
        <div id="elementor-template-library-import-form-or"><?= __('or') ?></div>
        <label for="elementor-template-library-import-form-input" id="elementor-template-library-import-form-label" class="elementor-button elementor-button-success"><?= __('Select File') ?></label>
        <input id="elementor-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
        <!--div class="elementor-template-library-blank-footer">
            <?= __('Want to learn more about the Elementor library?') ?>
            <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?= __('Click here') ?></a>
        </div-->
    </form>
</script>

<script type="text/template" id="tmpl-elementor-template-library-templates-empty">
    <div class="elementor-template-library-blank-icon">
        <i class="eicon-nerd" aria-hidden="true"></i>
    </div>
    <div class="elementor-template-library-blank-title"></div>
    <div class="elementor-template-library-blank-message"></div>
    <!--div class="elementor-template-library-blank-footer">
        <?= __('Want to learn more about the Elementor library?') ?>
        <a class="elementor-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?= __('Click here') ?></a>
    </div-->
</script>

<script type="text/template" id="tmpl-elementor-template-library-preview">
    <?= "<\x69frame></\x69frame>" ?>
</script>
