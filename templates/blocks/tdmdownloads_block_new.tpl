<div class="tdmdownloads-block">
    <{if $tdmblockstyle == 'simple1' || $tdmblockstyle == 'simple2' || $tdmblockstyle == 'simple3' || $tdmblockstyle == 'simple4'}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_stylesimple.tpl' downloads=$downloads}>
        <{/foreach}>
        <{if $perm_submit}>
            <{if $tdmblockstyle == 'simple4'}>
                <div class='col-xs-12 col-sm-3 tdmdownloads-blockpanel'>
            <{else}>
                <div class='col-xs-12 col-sm-12 tdmdownloads-blockpanel'>
            <{/if}>
                <div class='tdmdownloads-blockcard-add center'>
                    <a class="" href="<{$mod_url}>/submit.php" title='<{$smarty.const._ADD}>' target='_self'>+</a>
                </div>
            </div>
        <{/if}>
    <{else}>
        <{foreach item=downloads from=$block}>
            <{include file='db:tdmdownloads_block_styledefault.tpl' downloads=$downloads}>
        <{/foreach}>
    <{/if}>

</div>
