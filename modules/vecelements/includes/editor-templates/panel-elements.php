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
<script type="text/template" id="tmpl-elementor-panel-elements">
    <div id="elementor-panel-elements-loading">
        <i class="eicon-loading eicon-animation-spin"></i>
    </div>
    <div id="elementor-panel-elements-navigation" class="elementor-panel-navigation">
        <div id="elementor-panel-elements-navigation-all"
            class="elementor-panel-navigation-tab elementor-active" data-view="categories"><?= __('Elements') ?></div>
        <div id="elementor-panel-elements-navigation-global"
            class="elementor-panel-navigation-tab" data-view="global"><?= __('Global') ?></div>
    </div>
    <div id="elementor-panel-elements-search-area"></div>
    <div id="elementor-panel-elements-wrapper"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-categories">
    <div id="elementor-panel-categories"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-elements-category">
    <div class="elementor-panel-category-title">{{{ title }}}</div>
    <div class="elementor-panel-category-items"></div>
</script>

<script type="text/template" id="tmpl-elementor-panel-element-search">
    <label for="elementor-panel-elements-search-input" class="screen-reader-text"><?= __('Search Widget:') ?></label>
    <input type="search" id="elementor-panel-elements-search-input" placeholder="<?= esc_attr__('Search Widget...') ?>">
    <i class="fa fa-search" aria-hidden="true"></i>
</script>

<script type="text/template" id="tmpl-elementor-element-library-element">
    <div class="elementor-element">
        <div class="icon">
            <i class="{{ icon }}" aria-hidden="true"></i>
        </div>
        <div class="elementor-element-title-wrapper">
            <div class="title">{{{ title }}}</div>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-elementor-panel-global"></script>
