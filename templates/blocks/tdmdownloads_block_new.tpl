<div class="tdmdownloads-block">
    <{if $tdmblockstyle == 'simple'}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_stylesimple.tpl' downloads=$downloads}>
        <{/foreach}>
        <{if $perm_submit}>
            <div class='col-xs-12 col-sm-3 tdmdownloads-blockpanel'>
                <div class='tdmdownloads-blockcard-add center'>
                    <a class="" href="<{$xoops_url}>/modules/tdmdownloads/submit.php" title='<{$smarty.const._ADD}>' target='_self'>+</a>
                </div>
            </div>
        <{/if}>
    <{else}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_styledefault.tpl' downloads=$downloads}>
        <{/foreach}>
    <{/if}>
    
</div>
