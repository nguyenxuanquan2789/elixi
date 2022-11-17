<div class="content-specific">
  <div class="inner-specific">
  <div class="product-description box-specific">
    <h4>{l s='Description' d='Shop.Theme.Catalog'}</h4>
    {block name='product_description'}
      <div class="product-description">{$product.description nofilter}</div>
    {/block}
  </div>
  <div class="product-detail box-specific">
    <h4>{l s='Features' d='Shop.Theme.Catalog'}</h4>
    {block name='product_details'}
      {include file='catalog/_partials/product-details.tpl'}
    {/block}
  </div>
  </div>
  {if $product.attachments}
    <div class="box-specific">
    <h4>{l s='Attachments' d='Shop.Theme.Catalog'}</h4>
    {block name='product_attachments'}
      <section class="product-attachments">
        <p class="h3">{l s='Download' d='Shop.Theme.Actions'}</p>
        {foreach from=$product.attachments item=attachment}
          <div class="attachment">
            <h5><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h5>
            <p>{$attachment.description}</p>
            <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
              {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
            </a>
          </div>
        {/foreach}
      </section>
    {/block}
    </div>
  {/if}
  {foreach from=$product.extraContent item=extra key=extraKey}
    <div class="box-specific">
    <h4>
      {$extra.title}
    </h4>
    <div class="{$extra.attr.class}" id="extra-{$extraKey}" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
      {$extra.content nofilter}
    </div>
    </div>
  {/foreach}
  <div class="box-specific">
  {hook h="displayProductTab"}
  {hook h="displayProductTabContent"}
  </div>
</div>
