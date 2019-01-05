<div class="item tdmdownloads-item">
    <div class="itemHead">
		<span class="itemTitle">
			<strong><a title="<{$down.title}>" href="<{$xoops_url}>/modules/tdmdownloads/singlefile.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$down.title}></a></strong>
		</span>
        <br>
    </div>
    <div class="itemInfo">

    </div>
    <div class="itemBody">
        <{if $down.new}>
            <span class="itemNew"><{$down.new}></span>
        <{/if}>
        <{if $down.pop}>
            <span class="itemPop"><{$down.pop}></span>
        <{/if}>
        <{if $down.perm_download != ""}>
            <span class="itemDownload"><a title="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>" href="visit.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory external"><img src="<{$xoops_url}>/modules/tdmdownloads/assets/images/icon/download-now.png"
                                                                                                                                                                                       alt="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>"></a></span>
        <{/if}>
        <span class="itemPoster"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_SUBMITDATE}><{$down.updated}></span>
        <span class="itemPostDate"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_SUBMITTER}><{$down.submitter}></span>
        <{$down.description_short}>
        <div class="itemText justify">
            <{if $show_screenshot === true}>
                <{if $down.logourl != ''}>
                    <img class="<{$img_float}>" width="<{$shotwidth}>" src="<{$down.logourl}>" alt="<{$down.title}>">
                <{/if}>
            <{/if}>
        </div>
        <div class="endline"></div>
    </div>
    <div class="itemFoot">
        <span class="itemAdminLink"><{$down.adminlink}></span>
        <span class="itemPermaLink"><a title="<{$down.title}>" href="<{$xoops_url}>/modules/tdmdownloads/singlefile.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$smarty.const._MD_TDMDOWNLOADS_MOREDETAILS}></a></span>

        <div class="tdmdownloads-linetitle"></div>
    </div>
</div>
