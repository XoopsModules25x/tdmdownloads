<div class="tdmdownloads-block">
    <{if $tdmblockstyle == 'simple'}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_stylesimple.tpl' downloads=$downloads}>
        <{/foreach}>
    <{else}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_styledefault.tpl' downloads=$downloads}>
        <{/foreach}>
    <{/if}>
    
</div>
