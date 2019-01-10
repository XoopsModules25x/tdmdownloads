<div class="tdmdownloads">
    <!-- Download logo-->
    <div class="tdmdownloads-logo center marg10">
        <a title="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>" href="<{$xoops_url}>/modules/tdmdownloads/index.php"><img src="<{$xoops_url}>/modules/tdmdownloads/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>"></a>
    </div>

    <!-- Download searchform -->
    <div class="tdmdownloads-searchform"><{$searchForm}></div>
    <div class="tdmdownloads-thereare"><{$lang_thereare}></div>

    <table width="100%" cellspacing="0" class="outer_sertec" border="1">
        <tr>
            <td class="head" align="center" style="width: 40px; vertical-align: middle;">&nbsp;</td>
            <td class="head" align="left" style="vertical-align: middle;"><{$smarty.const._MD_TDMDOWNLOADS_SEARCH_TITLE}></td>
            <td class="head" align="left" style="width: 150px; vertical-align: middle;" colspan="2"><{$smarty.const._MD_TDMDOWNLOADS_SEARCH_CATEGORIES}></td>
            <{foreach item=field from=$field}>
                <td class="head" align="left" style="vertical-align: middle;"><{$field}></td>
            <{/foreach}>
            <td class="head" align="center" style="width: 100px; vertical-align: middle;"><{$smarty.const._MD_TDMDOWNLOADS_SEARCH_DATE}></td>
            <td class="head" align="center" style="width: 60px; vertical-align: middle;"><{$smarty.const._MD_TDMDOWNLOADS_SEARCH_NOTE}></td>
            <td class="head" align="center" style="width: 60px; vertical-align: middle;"><{$smarty.const._MD_TDMDOWNLOADS_SEARCH_HITS}></td>
        </tr>
        <{foreach item=download from=$search_list}>
            <tr class='<{cycle values="odd,even"}>'>
                <td align="center" style="vertical-align: middle;">
					<a href="<{$xoops_url}>/modules/tdmdownloads/visit.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>" target="_blank"><img src="./assets/images/download.png" alt="<{$smarty.const._MD_TDMDOWNLOADS_SEARCH_DOWNLOAD}><{$download.title}>" title="<{$smarty.const._MD_TDMDOWNLOADS_SEARCH_DOWNLOAD}><{$download.title}>"></a>
				</td>
                <td align="left" style="vertical-align: middle;">
					<a href="<{$xoops_url}>/modules/tdmdownloads/singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>" title="<{$download.title}>"><{$download.title}></a>
				</td>
                <td align="center" style="width: 32px; vertical-align: middle;">
					<img src="<{$download.imgurl}>" alt="<{$download.cat}>" title="<{$download.cat}>" width="30">
				</td>
                <td align="left" style="vertical-align: middle;">
					<a href="<{$xoops_url}>/modules/tdmdownloads/viewcat.php?cid=<{$download.cid}>" target="_blank" title="<{$download.cat}>"><{$download.cat}></a>
				</td>
                <{foreach item=fielddata from=$download.fielddata}>
                    <td align="left" style="vertical-align: middle;"><{$fielddata}></td>
                <{/foreach}>
                <td align="center" style="vertical-align: middle;"><{$download.date}></td>
                <td align="center" style="vertical-align: middle;"><{$download.rating}></td>
                <td align="center" style="vertical-align: middle;"><{$download.hits}></td>
            </tr>
        <{/foreach}>
    </table>

    <{if $pagenav != ''}>
        <!-- Download Pagenav-->
        <div class="tdmdownloads-pagenav"><{$pagenav}></div>
    <{/if}>

</div>