{extends file='customer/page.tpl'}

{block name='page_title'}
    {l s='My Order List' mod='orderlist'}
{/block}

{block name='page_content'}
    <div class="orderlist container">
        <div class="products row mb-3">
            {foreach $products as $product}
                {include file='module:orderlist/views/templates/front/_partials/miniatures/product.tpl' product=$product}
            {/foreach}
        </div>
    </div>
{/block}
