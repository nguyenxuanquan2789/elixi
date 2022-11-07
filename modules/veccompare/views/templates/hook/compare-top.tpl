<a class="btn-compare-top btn-header" href="{url entity='module' name='veccompare' controller='comparator'}" title="{l s='Compare' mod='veccompare'}">
    {if isset($icon) && $icon}
        <i class="compare-top-icon {$icon}"></i>  
    {/if}
    <span class="btn-compare-text">{l s='Compare' mod='veccompare'}</span>
    <span class="js-compare-count compare-count">0</span>
</a>