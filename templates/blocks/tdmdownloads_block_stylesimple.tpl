<{if $tdmblockstyle == 'simple4'}>
    <div class='col-xs-12 col-sm-3 tdmdownloads-blockpanel'>
<{elseif $tdmblockstyle == 'simple3'}>
    <div class='col-xs-12 col-sm-4 tdmdownloads-blockpanel'>
<{elseif $tdmblockstyle == 'simple2'}>
    <div class='col-xs-12 col-sm-6 tdmdownloads-blockpanel'>
<{else}>
    <div class='col-xs-12 col-sm-12 tdmdownloads-blockpanel'>
<{/if}>
        <div class='tdmdownloads-blockcard'>
            <div class='tdmdownloads-blockinfo center'>
                <{if $downloads.logourl}>
                    <p class="tdm-download-logo">
                        <img src="<{$downloads.logourl}>" width="<{$downloads.logourl_width}>" alt="<{$downloads.title}>">
                    </p>
                <{/if}>
                <p class="tdmdownloads-blocktitle"><{$downloads.title}></p>
                <{if $downloads.description}>
                    <p class="tdmdownloads-blockdesc"><{$downloads.description}></p>
                <{/if}>
                <{if $downloads.inforation}>
                    <p class="tdmdownloads-blockinfo">
                        <{$smarty.const._MB_TDMDOWNLOADS_SUBMITDATE}><{$downloads.date}><br>
                        <{$smarty.const._MB_TDMDOWNLOADS_SUBMITTER}><{$downloads.submitter}><br>
                        <{$smarty.const._MB_TDMDOWNLOADS_REATING}><{$downloads.rating}><br>
                        <{$smarty.const._MB_TDMDOWNLOADS_HITS}><{$downloads.hits}>
                    </p>
                <{/if}>
            </div>
            <div class='tdmdownloads-blockbtns center'>
                <p class="center">
                    <a class="btn btn-default" href="<{$xoops_url}>/modules/tdmdownloads/visit.php?lid=<{$downloads.lid}>" title='<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>' target='_blank'><i class="glyphicon glyphicon-cloud-download tdmdownloads-blockbtn1" title="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>"></i></a>
                    <{if $perm_submit}>
                        <a class="btn btn-default" href="<{$xoops_url}>/modules/tdmdownloads/modfile.php?lid=<{$downloads.lid}>" title='<{$smarty.const._EDIT}>' target='_blank'><i class="glyphicon glyphicon-edit tdmdownloads-blockbtn2" title="<{$smarty.const._EDIT}>"></i></a>
                    <{/if}>
                </p>
            </div>
        </div>
    </div>
