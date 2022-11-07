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

use VEC\CoreXResponsiveXResponsive as Responsive;

$document = Plugin::$instance->documents->get(Plugin::$instance->editor->getPostId());
?>
<script type="text/template" id="tmpl-elementor-panel">
    <div id="elementor-mode-switcher"></div>
    <header id="elementor-panel-header-wrapper"></header>
    <main id="elementor-panel-content-wrapper"></main>
    <footer id="elementor-panel-footer">
        <div class="elementor-panel-container">
        </div>
    </footer>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu">
    <div id="elementor-panel-page-menu-content"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu-group">
    <div class="elementor-panel-menu-group-title">{{{ title }}}</div>
    <div class="elementor-panel-menu-items"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-menu-item">
    <div class="elementor-panel-menu-item-icon">
        <i class="{{ icon }}"></i>
    </div>
    <# if ( 'undefined' === typeof type || 'link' !== type ) { #>
        <div class="elementor-panel-menu-item-title">{{{ title }}}</div>
    <# } else {
        var target = ( 'undefined' !== typeof newTab && newTab ) ? '_blank' : '_self'; #>
        <a href="{{ link }}" target="{{ target }}"><div class="elementor-panel-menu-item-title">{{{ title }}}</div></a>
    <# } #>
</script>

<?php /** @codingStandardsIgnoreStart Generic.Files.LineLength */ ?>
<script type="text/template" id="tmpl-elementor-panel-header">
    <div id="elementor-panel-header-menu-button" class="elementor-header-button">
        <i class="elementor-icon eicon-menu-bar tooltip-target" aria-hidden="true" data-tooltip="<?= esc_attr__('Menu') ?>"></i>
        <span class="elementor-screen-only"><?= __('Menu') ?></span>
    </div>
    <div id="elementor-panel-header-title"></div>
    <div id="elementor-panel-header-add-button" class="elementor-header-button">
        <i class="elementor-icon eicon-apps tooltip-target" aria-hidden="true" data-tooltip="<?= esc_attr__('Widgets Panel') ?>"></i>
        <span class="elementor-screen-only"><?= __('Widgets Panel') ?></span>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-footer-content">
    <div id="elementor-panel-footer-settings" class="elementor-panel-footer-tool elementor-leave-open tooltip-target" data-tooltip="<?= esc_attr__('Settings') ?>">
        <i class="fa fa-cog" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?= sprintf(__('%s Settings'), $document::getTitle()) ?></span>
    </div>
<?php if ((count($langs = \Language::getLanguages(true, false)) > 1 || \Shop::isFeatureActive()) && ($uid = get_the_ID()) && $uid->id_type != UId::TEMPLATE) : ?>
    <div id="elementor-panel-footer-lang" class="elementor-panel-footer-tool elementor-toggle-state">
        <i class="fa fa-flag tooltip-target" aria-hidden="true" data-tooltip="<?= esc_attr__('Language') ?>"></i>
        <span class="elementor-screen-only">
            <?= __('Language') ?>
        </span>
        <div class="elementor-panel-footer-sub-menu-wrapper">
        <?php if (\Shop::isFeatureActive() && count($shops = \Shop::getShops()) > 1) : ?>
            <form class="elementor-panel-footer-sub-menu" id="ce-context-wrapper" name="context" method="post">
                <?php
                $active_shop = \Shop::getContextShopID();
                $active_group = \Shop::getContextShopGroupID();

                $shop_ids = $uid->getShopIdList(true);
                $group_ids = [];
                $groups = [];

                foreach (\ShopGroup::getShopGroups() as $group) {
                    $groups[$group->id] = $group->name;
                }
                foreach ($shop_ids as $id_shop) {
                    $id_group = $shops[$id_shop]['id_shop_group'];
                    $group_ids[$id_group] = $id_group;
                }
                $star = ' ★ ';
                $tab1 = '🖿 &nbsp; &nbsp;';
                $tab2 = '&nbsp; ● &nbsp; &nbsp;';
                ?>
                <select name="setShopContext" id="ce-context">
                    <option value=""><?= __('All Shops') . (!$active_group ? $star : '') ?></option>
                    <?php foreach ($group_ids as $id_group) : ?>
                        <?php $active = !$active_shop && $id_group == $active_group ? $star : '' ?>
                        <option value="g-<?= $id_group ?>" <?= $active ? 'selected' : '' ?>><?= "$tab1{$groups[$id_group]}$active" ?></option>
                        <?php foreach ($shop_ids as $id_shop) : ?>
                            <?php if ($shops[$id_shop]['id_shop_group'] == $id_group) : ?>
                                <?php $active = $id_shop == $active_shop ? $star : '' ?>
                                <option value="s-<?= $id_shop ?>" <?= $active ? 'selected' : '' ?>><?= "$tab2{$shops[$id_shop]['name']}$active" ?></option>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php endforeach ?>
                </select>
            </form>
        <?php endif ?>
            <div class="elementor-panel-footer-sub-menu" id="ce-langs" data-lang="<?= $uid->id_lang ?>" data-built='<?= json_encode(UId::getBuiltList($uid->id, $uid->id_type)) ?>'>
                <?php foreach ($langs as &$lang) : ?>
                    <div class="elementor-panel-footer-sub-menu-item ce-lang" data-lang="<?= $lang['id_lang'] ?>" data-shops='<?= json_encode(array_keys($lang['shops'])) ?>'>
                        <i class="elementor-icon"><?= $lang['iso_code'] ?></i>
                        <span class="elementor-title"><?= $lang['name'] ?></span>
                        <span class="elementor-description">
                            <button class="elementor-button elementor-button-success">
                                <i class="eicon-file-download"></i>
                                <?= __('Insert') ?>
                            </button>
                        </span>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php endif ?>
    <div id="elementor-panel-footer-navigator" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?= esc_attr__('Navigator') ?>">
        <i class="eicon-navigator" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?= __('Navigator') ?></span>
    </div>
    <!-- <div id="elementor-panel-footer-history" class="elementor-panel-footer-tool elementor-leave-open tooltip-target elementor-toggle-state" data-tooltip="<?= esc_attr__('History') ?>">
        <i class="fa fa-history" aria-hidden="true"></i>
        <span class="elementor-screen-only"><?= __('History') ?></span>
    </div> -->
    <div id="elementor-panel-footer-responsive" class="elementor-panel-footer-tool elementor-toggle-state">
        <i class="eicon-device-desktop tooltip-target" aria-hidden="true" data-tooltip="<?= esc_attr__('Responsive Mode') ?>"></i>
        <span class="elementor-screen-only">
            <?= __('Responsive Mode') ?>
        </span>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <div class="elementor-panel-footer-sub-menu">
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="desktop">
                    <i class="elementor-icon eicon-device-desktop" aria-hidden="true"></i>
                    <span class="elementor-title"><?= __('Desktop') ?></span>
                    <span class="elementor-description"><?= __('Default Preview') ?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="tablet">
                    <i class="elementor-icon eicon-device-tablet" aria-hidden="true"></i>
                    <span class="elementor-title"><?= __('Tablet') ?></span>
                    <?php $breakpoints = Responsive::getBreakpoints() ?>
                    <span class="elementor-description"><?= sprintf(__('Preview for %s'), $breakpoints['md'] . 'px') ?></span>
                </div>
                <div class="elementor-panel-footer-sub-menu-item" data-device-mode="mobile">
                    <i class="elementor-icon eicon-device-mobile" aria-hidden="true"></i>
                    <span class="elementor-title"><?= __('Mobile') ?></span>
                    <span class="elementor-description"><?= sprintf(__('Preview for %s'), '360px') ?></span>
                </div>
            </div>
        </div>
    </div>
    <div id="elementor-panel-footer-saver-preview" class="elementor-panel-footer-tool tooltip-target" data-tooltip="<?= esc_attr__('Preview Changes') ?>">
        <span id="elementor-panel-footer-saver-preview-label">
            <i class="fa fa-eye" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?= __('Preview Changes') ?></span>
        </span>
    </div>
    <div id="elementor-panel-footer-saver-publish" class="elementor-panel-footer-tool">
        <button id="elementor-panel-saver-button-publish" class="elementor-button elementor-button-success elementor-disabled">
            <span class="elementor-state-icon">
                <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
            </span>
            <span id="elementor-panel-saver-button-publish-label">
                <?= __('Publish') ?>
            </span>
        </button>
    </div>
    <div id="elementor-panel-footer-saver-options" class="elementor-panel-footer-tool elementor-toggle-state">
        <button id="elementor-panel-saver-button-save-options" class="elementor-button elementor-button-success tooltip-target elementor-disabled" data-tooltip="<?= esc_attr__('Save Options') ?>">
            <i class="fa fa-caret-up" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?= __('Save Options') ?></span>
        </button>
        <div class="elementor-panel-footer-sub-menu-wrapper">
            <p class="elementor-last-edited-wrapper">
                <span class="elementor-state-icon">
                    <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
                </span>
                <span class="elementor-last-edited">
                    {{{ elementor.config.document.last_edited }}}
                </span>
            </p>
            <div class="elementor-panel-footer-sub-menu">
                <div id="elementor-panel-footer-sub-menu-item-save-draft" class="elementor-panel-footer-sub-menu-item elementor-disabled">
                    <i class="elementor-icon eicon-save" aria-hidden="true"></i>
                    <span class="elementor-title"><?= __('Save Draft') ?></span>
                </div>
                <div id="elementor-panel-footer-sub-menu-item-save-template" class="elementor-panel-footer-sub-menu-item">
                    <i class="elementor-icon fa fa-folder" aria-hidden="true"></i>
                    <span class="elementor-title"><?= __('Save as Template') ?></span>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-mode-switcher-content">
    <input id="elementor-mode-switcher-preview-input" type="checkbox">
    <label for="elementor-mode-switcher-preview-input" id="elementor-mode-switcher-preview">
        <i class="fa" aria-hidden="true" title="<?= esc_attr__('Hide Panel') ?>"></i>
        <span class="elementor-screen-only"><?= __('Hide Panel') ?></span>
    </label>
</script>

<script type="text/template" id="tmpl-editor-content">
    <div class="elementor-panel-navigation">
    <# _.each( elementData.tabs_controls, function( tabTitle, tabSlug ) {
        if ( 'content' !== tabSlug && ! elementor.userCan( 'design' ) ) {
            return;
        }
        #>
        <div class="elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
            <a href="#">{{{ tabTitle }}}</a>
        </div>
    <# } ); #>
    </div>
    <# if ( elementData.reload_preview ) { #>
        <div class="elementor-update-preview">
            <div class="elementor-update-preview-title"><?= __('Update changes to page') ?></div>
            <div class="elementor-update-preview-button-wrapper">
                <button class="elementor-update-preview-button elementor-button elementor-button-success"><?= __('Apply') ?></button>
            </div>
        </div>
    <# } #>
    <div id="elementor-controls"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-schemes-disabled">
    <i class="elementor-nerd-box-icon eicon-nerd" aria-hidden="true"></i>
    <div class="elementor-nerd-box-title">{{{ '<?= __('%s are disabled') ?>'.replace( '%s', disabledTitle ) }}}</div>
    <div class="elementor-nerd-box-message"><?= sprintf(__('You can enable it from the <a href="%s" target="_blank">module settings page</a>.'), Helper::getSettingsLink()) ?></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-color-item">
    <div class="elementor-panel-scheme-color-input-wrapper">
        <input type="text" class="elementor-panel-scheme-color-value" value="{{ value }}" data-alpha="true">
    </div>
    <div class="elementor-panel-scheme-color-title">{{{ title }}}</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-scheme-typography-item">
    <div class="elementor-panel-heading">
        <div class="elementor-panel-heading-toggle">
            <i class="fa" aria-hidden="true"></i>
        </div>
        <div class="elementor-panel-heading-title">{{{ title }}}</div>
    </div>
    <div class="elementor-panel-scheme-typography-items elementor-panel-box-content">
        <?php
        $scheme_fields_keys = GroupControlTypography::getSchemeFieldsKeys();

        $typography_group = Plugin::$instance->controls_manager->getControlGroups('typography');
        $typography_fields = $typography_group->getFields();

        $scheme_fields = array_intersect_key($typography_fields, array_flip($scheme_fields_keys));
        ?>
        <?php foreach ($scheme_fields as $option_name => $option) : ?>
            <div class="elementor-panel-scheme-typography-item">
                <div class="elementor-panel-scheme-item-title elementor-control-title"><?= $option['label'] ?></div>
                <div class="elementor-panel-scheme-typography-item-value">
                    <?php if ('select' === $option['type']) : ?>
                        <select name="<?= esc_attr($option_name) ?>" class="elementor-panel-scheme-typography-item-field">
                            <?php foreach ($option['options'] as $field_key => $field_value) : ?>
                                <option value="<?= esc_attr($field_key) ?>"><?= $field_value ?></option>
                            <?php endforeach ?>
                        </select>
                    <?php elseif ('font' === $option['type']) : ?>
                        <select name="<?= esc_attr($option_name) ?>" class="elementor-panel-scheme-typography-item-field">
                            <option value=""><?= __('Default') ?></option>
                            <?php foreach (Fonts::getFontGroups() as $group_type => $group_label) : ?>
                                <optgroup label="<?= esc_attr($group_label) ?>">
                                    <?php foreach (Fonts::getFontsByGroups([$group_type]) as $font_title => $font_type) : ?>
                                        <option value="<?= esc_attr($font_title) ?>"><?= $font_title ?></option>
                                    <?php endforeach ?>
                                </optgroup>
                            <?php endforeach ?>
                        </select>
                    <?php elseif ('text' === $option['type']) : ?>
                        <input name="<?= esc_attr($option_name) ?>" class="elementor-panel-scheme-typography-item-field">
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-responsive-switchers">
    <div class="elementor-control-responsive-switchers">
    <#
    var devices = responsive.devices || [ 'desktop', 'tablet', 'mobile' ];

    _.each( devices, function( device ) { #>
        <a class="elementor-responsive-switcher elementor-responsive-switcher-{{ device }}" data-device="{{ device }}">
            <i class="eicon-device-{{ device }}"></i>
        </a>
    <# } ); #>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-dynamic-switcher">
    <div class="elementor-control-dynamic-switcher-wrapper">
        <div class="elementor-control-dynamic-switcher">
            <?= __('Dynamic') ?>
            <i class="fa fa-database"></i>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-control-dynamic-cover">
    <div class="elementor-dynamic-cover__settings">
        <i class="fa fa-{{ hasSettings ? 'wrench' : 'database' }}"></i>
    </div>
    <div class="elementor-dynamic-cover__title" title="{{{ title + ' ' + content }}}">{{{ title + ' ' + content }}}</div>
    <# if ( isRemovable ) { #>
        <div class="elementor-dynamic-cover__remove">
            <i class="fa fa-times-circle"></i>
        </div>
    <# } #>
</script>

<script type="text/template" id="tmpl-elementor-panel-page-settings">
    <div class="elementor-panel-navigation">
        <# _.each( elementor.config.page_settings.tabs, function( tabTitle, tabSlug ) { #>
            <div class="elementor-panel-navigation-tab elementor-tab-control-{{ tabSlug }}" data-tab="{{ tabSlug }}">
                <a href="#">{{{ tabTitle }}}</a>
            </div>
        <# } ); #>
    </div>
    <div id="elementor-panel-page-settings-controls"></div>
</script>
