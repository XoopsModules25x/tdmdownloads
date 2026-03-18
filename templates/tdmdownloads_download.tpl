<div class="item tdmdownloads-item">
    <div class="itemHead">
        <span class="itemTitle">
            <strong><a title="<{$down.title}>" href="<{$mod_url}>/singlefile.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$down.title}></a></strong>
        </span>
        <br>
    </div>
    <div class="itemInfo">

    </div>
    <div class="itemBody">
        <{if $down.new|default:false}>
            <span class="itemNew"><{$down.new}></span>
        <{/if}>
        <{if $down.pop|default:false}>
            <span class="itemPop"><{$down.pop}></span>
        <{/if}>
        <{if $down.perm_download|default:'' != ''}>
            <span class="itemDownload"><a title="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>" href="visit.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory external"><img src="<{$mod_url}>/assets/images/icons/16/download-now.png"
                                                                                                                                                                                       alt="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}>"></a></span>
        <{/if}>
        <span class="itemPoster"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_SUBMITDATE}><{$down.updated}></span>
        <span class="itemPostDate"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_SUBMITTER}><{$down.submitter}></span>
        <{$down.description_short}>
        <div class="itemText justify">
            <{if $show_screenshot|default:false === true}>
                <{if $down.logourl|default:'' != ''}>
                    <img class="<{$img_float}>" width="<{$shotwidth}>" src="<{$down.logourl}>" alt="<{$down.title}>">
                <{/if}>
            <{/if}>
        </div>
        <div class="endline"></div>
    </div>
    <div class="itemFoot">
        <span class="itemAdminLink"><{$down.adminlink|default:false}></span>
        <span class="itemPermaLink"><a title="<{$down.title}>" href="<{$mod_url}>/singlefile.php?cid=<{$down.cid}>&amp;lid=<{$down.id}>" rel="directory"><{$smarty.const._MD_TDMDOWNLOADS_MOREDETAILS}></a></span>

        <div class="tdmdownloads-linetitle"></div>
    </div>
</div>
