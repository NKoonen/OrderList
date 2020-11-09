{extends file='catalog/_partials/miniatures/product.tpl'}

{block name='product_thumbnail' prepend}
    {if $removelinks[$product.id] !== NULL}
		<a class="remove" href="{$removelinks[$product.id]}">
			<i class="material-icons">delete</i>
		</a>
    {/if}
{/block}

{block name='product_price_and_shipping' append}
    {if !$configuration.is_catalog && $product.show_price}
	    <div class="m-2">
		    <form action="{$urls.pages.cart}" method="post">
			    <input type="hidden" name="token" value="{$static_token}">
			    <input type="hidden" name="id_product" value="{$product.id_product}">
			    <input type="hidden" name="id_customization" value="0">
			    <div class="product-quantity clearfix">
				    <div class="input-group">
					    <input type="number" name="qty" value="1" class="input-group form-control" min="1">
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
    {/if}
{/block}
