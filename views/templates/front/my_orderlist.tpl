{extends file='customer/page.tpl'}

{block name='page_title'}
    {l s='My Order List' mod='orderlist'}
{/block}
{block name='page_content'}
    <div class="container">
        <div class="products row" style="margin-bottom: 5em;">
            <div class="col-xs-12 col-sm-10 col-md-10">
                {foreach $products as $product}
                    <article class="col-sm-4 product-miniature js-product-miniature" style="margin-bottom: 2.4rem;">
                        <div class="thumbnail-container">
                            {if $removelinks[$product.id] !== NULL}
                                <a href="{$removelinks[$product.id]}">
                                    <i style="color: red;position: absolute;z-index: 1;"
                                       class="material-icons">delete</i>
                                </a>
                            {/if}
                            <a href="{$product.url}" class="thumbnail product-thumbnail">
                                <img src="{$product.cover.bySize.home_default.url}">
                            </a>
                        </div>
                        <div class="product-description">
                            <h3 class="h3 product-title" itemprop="name">
                                <a href="{$product.url}"> {$product.name|truncate:100:'...'}</a>
                                <div class="product-price-and-shipping">
                                    <span itemprop="price"
                                          class="price">{$product.price}</span>
                                </div>
                            </h3>
                            <form action="{$urls.pages.cart}" method="post">
                                <input type="hidden" name="token" value="{$static_token}">
                                <input type="hidden" name="id_product" value="{$product.id_product}">
                                <input type="hidden" name="id_customization" value="0">
                                <div class="product-quantity clearfix">
                                    <div class="input-group">
                                        <input type="number" name="qty" value="1" class="input-group form-control"
                                               min="1">
                                    </div>
                                    <div class="add">
                                        <button data-button-action="add-to-cart" class="btn btn-success add-to-cart">
                                            {l s='Add to cart' d='Admin.Orderscustomers.Feature'}
                                            <span class="icon fa fa-fwh fa-arrow-right border rounded-circle border-dashed ml-2"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </article>
                {/foreach}
            </div>
        </div>
    </div>
{/block}
