    <div class='col-xs-12 col-sm-3 tdmdownloads-blockpanel'>
        <div class='tdmdownloads-blockcard center'>
            <{if $downloads.logourl}>
                <p class="tdm-download-logo">
                    <img src="<{$downloads.logourl}>" alt="<{$downloads.title}>">
                </p>
            <{/if}>
            <p class="tdmdownloads-blocktitle"><{$downloads.title}></p>
            <{if $downloads.description}>
                <p class="tdmdownloads-blockdesc"><{$downloads.description}></p>
            <{/if}>
            <{if $downloads.inforation}>
                <{$smarty.const._MB_TDMDOWNLOADS_SUBMITDATE}><{$downloads.date}>
                <{$smarty.const._MB_TDMDOWNLOADS_SUBMITTER}><{$downloads.submitter}>
                <{$smarty.const._MB_TDMDOWNLOADS_REATING}><{$downloads.rating}>
                <{$smarty.const._MB_TDMDOWNLOADS_HITS}><{$downloads.hits}>
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
