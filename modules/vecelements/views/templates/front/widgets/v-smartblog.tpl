{if isset($posts) AND !empty($posts)}
<div class="elementor-image-carousel-wrapper elementor-slick-slider">
<div class="elementor-image-carousel slick-block {$classes}">
	{foreach from=$posts item=post}
    	{include file="$style" class="" postcategory=$post}
    {/foreach}
</div>
</div>
{else}
  <p>{l s='There is no post.'}</p>
{/if}