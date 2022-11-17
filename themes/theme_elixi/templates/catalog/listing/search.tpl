{*
 * This file allows you to customize your search page.
 * You can safely remove it if you want it to appear exactly like all other product listing pages
 *}
{extends file='catalog/listing/product-list.tpl'}

{block name="error_content"}
  <h4>{l s='No matches were found for your search' d='Shop.Theme.Catalog'}</h4>
  <p>{l s='Please try other keywords to describe what you are looking for.' d='Shop.Theme.Catalog'}</p>
  <a href="{$urls.pages.index}" class="btn btn-primary">{l s='Back to homepage' d='Shop.Theme.Catalog'}</a>
  <a href="{$urls.pages.contact}" class="btn btn-primary">{l s='Contact us' d='Shop.Theme.Catalog'}</a>
{/block}
