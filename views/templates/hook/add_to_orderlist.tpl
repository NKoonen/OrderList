{if $alreadyInAList}
    <a href="{$add_product_link}"><i class="material-icons">playlist_add</i> {l s='Add to Order List' mod='orderlist'}</a>
{else}
    <p class="success">
        <i class="material-icons">beenhere</i> {l s='Already in your Order List' mod='orderlist'}
    </p>
{/if}
