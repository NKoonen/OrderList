<p class="orderlist">
    {if $alreadyInAList}
        <a href="{$my_list_url}" class="go-to-orderlist"><i class="material-icons">beenhere</i> {l s='Already in your Order List' mod='orderlist'}</a>
    {else}
        <a href="{$add_product_link}" class="add-to-orderlist"><i class="material-icons">playlist_add</i> {l s='Add to Order List' mod='orderlist'}</a>
    {/if}
</p>
