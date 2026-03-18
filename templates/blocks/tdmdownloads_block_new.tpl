<div class="tdmdownloads-block">
    <{if $tdmblockstyle == 'simple1' || $tdmblockstyle == 'simple2' || $tdmblockstyle == 'simple3' || $tdmblockstyle == 'simple4'}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_stylesimple.tpl' downloads=$downloads}>
        <{/foreach}>
    <{else}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_styledefault.tpl' downloads=$downloads}>
        <{/foreach}>
    <{/if}>
    <{if $perm_submit}>
        <div class='col-xs-12 col-sm-12 tdmdownloads-blockpanel'>
            <div class='tdmdownloads-blockcard-add center'>
                <a class="btn btn-default" href="<{$mod_url}>/submit.php" title='<{$smarty.const._ADD}>' target='_self'><{$smarty.const._MD_TDMDOWNLOADS_SUBMIT_PROPOSER}></a>
            </div>
        </div>
    <{/if}>
</div>
