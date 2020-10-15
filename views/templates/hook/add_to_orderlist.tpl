{if $alreadyInAList}
    <a href="{$add_product_link}"><i class="material-icons">playlist_add</i> {l s='Add to Order List' mod='orderlist'}</a>
{else}
    <p class="success">
        <a href="{$my_list}"><i class="material-icons">beenhere</i> {l s='Already in your Order List' mod='orderlist'}</a>
    </p>
{/if}
