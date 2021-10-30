<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>

<{if $message_erreur|default:''}>
    <div class='errorMsg'><{$message_erreur}></div>
<{/if}>

<div align="right">
    <form id="form_document_tri" name="form_document_tri" method="get" action="document.php">
        <{$selectDocument|default:''}> <{$selectOrder|default:''}>
    </form>
</div>

<!-- show default list -->
<{if $downloads_list|default:''}>
    <table class='table table-bordered'>
        <thead>
        <tr class='head'>
            <th class='center' style='width:5%'><{$smarty.const._AM_TDMDOWNLOADS_FORMFILE}></th>
            <th class='left' style='width:20%'><{$smarty.const._AM_TDMDOWNLOADS_FORMTITLE}></th>
            <th class='left'><{$smarty.const._AM_TDMDOWNLOADS_FORMCAT}></th>
            <th class='center' style='width:5%'><{$smarty.const._AM_TDMDOWNLOADS_FORMHITS}></th>
            <th class='center' style='width:5%'><{$smarty.const._AM_TDMDOWNLOADS_FORMRATING}></th>
            <th class='center' style='width:15%'><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></th>
        </tr>
        </thead>
        <{if $downloads_count}>
            <tbody>
            <{foreach item=download from=$downloads_list}>
                <tr class="<{cycle values='odd, even'}>">
                    <td class='center'>
                        <a href="../visit.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>" target="_blank"><img src="<{$pathModIcon16}>/download-now.png" alt="Download <{$download.title}>" title="Download <{$download.title}>"></a>
                    </td>
                    <td class='left'>
                        <a href="../singlefile.php?.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>" target="_blank"><{$download.title}></a>
                    </td>
                    <td class='left'><{$download.category}></td>
                    <td class='center'><{$download.hits}></td>
                    <td class='center'><{$download.rating}></td>
                    <td class='center'>
                        <{if $download.statut_display == 1}>
                            <a href="downloads.php?op=lock_status&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/on.png" border="0" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMLOCK}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMLOCK}>"></a>
                        <{else}>
                            <a href="downloads.php?op=update_status&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/off.png" border="0" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMVALID}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMVALID}>"></a>
                        <{/if}>
                        <a href="downloads.php?op=view_downloads&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/view_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>"></a>
                        <a href="downloads.php?op=edit_downloads&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/edit.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>"></a>
                        <a href="downloads.php?op=del_downloads&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/delete.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>"></a>
                    </td>
                </tr>
            <{/foreach}>
            </tbody>
        <{/if}>
    </table>
    <div class='clear'>&nbsp;</div>
    <{if $pagenav|default:''}>
        <div class='xo-pagenav floatright'><{$pagenav}></div>
        <div class='clear spacer'></div>
    <{/if}>
<{/if}>

<!-- show details of a download -->
<{if $download_detail|default:''}>
    <table width="100%" cellspacing="1" class="outer">
        <tr>
            <th align="center" colspan="2"><{$download.title}></th>
        </tr>
        <tr class="even">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMFILE}></td>
            <td><a href="../visit.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/download-now.png" alt="Download <{$download.title}>" title="Download <{$download.title}>"></a></td>
        </tr>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMCAT}></td>
            <td><{$download.category}></td>
        </tr>
        <{foreach item=field from=$downloads.fields_list|default:null}>
            <tr class="<{cycle values='odd, even'}>">
                <td width="30%"><{$field.name}></td>
                <td><{$field.value}></td>
            </tr>
        <{/foreach}>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMTEXT}></td>
            <td><{$download.description}></td>
        </tr>
        <!-- tags -->
        <{if $download.tags|default:''}>
            <tr class="<{cycle values='odd, even'}>">
                <td width="30%"><{$tags.title}></td>
                <td><{$tags.value}></td>
            </tr>
        <{/if}>
        <{if $download.logourl}>
            <tr class="<{cycle values='odd, even'}>">
                <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMIMG}></td>
                <td><img src="<{$uploadurl_shots}><{$download.logourl}>" alt="<{$download.title}>" title="<{$download.title}>"></td>
            </tr>
        <{/if}>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMDATE}></td>
            <td><{$download.date}></td>
        </tr>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMPOSTER}></td>
            <td><{$download.submitter}></td>
        </tr>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMHITS}></td>
            <td><{$download.hits}></td>
        </tr>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMRATING}></td>
            <td><{$download.rating}> (<{$download.votes}> <{$smarty.const._AM_TDMDOWNLOADS_FORMVOTE}>)</td>
        </tr>
        <{if $download.paypal|default:''}>
            <tr class="<{cycle values='odd, even'}>">
                <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMPAYPAL}></td>
                <td><{$download.paypal}></td>
            </tr>
        <{/if}>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMCOMMENTS}></td>
            <td><{$download.comments}> <a href="../singlefile.php?cid=<{$download.cid}>&amp;lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/view_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>"></a></td>
        </tr>
        <tr class="<{cycle values='odd, even'}>">
            <td width="30%"><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></td>
            <td>
                <{if $downloadstatus|default:0 > 0 }><a href="downloads.php?op=update_status&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/off.png" border="0" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMVALID}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMVALID}>"></a><{/if}>
                <a href="downloads.php?op=edit_downloads&downloads_lid=<{$download.lid}>"><img src="<{$pathModIcon16}>/edit.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>"></a>
                <a href="downloads.php?op=del_downloads&downloads_lid=<{$download.lid}>">
                    <img src="<{$pathModIcon16}>/delete.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>">
                </a>
            </td>
        </tr>
    </table>
    <!-- handle ratings -->
    <hr>
    <table width="100%">
        <tr>
            <th colspan="5">
                <b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTESTOTAL}> <{$ratings.votes_total}></b><br><br>
            </th>
        </tr>
        <tr>
            <td colspan="5">
                <b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER}> <{$ratings.user_total}></b><br><br>
            </td>
        </tr>
        <tr>
            <td><b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER}></b></td>
            <td><b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP}></b></td>
            <td align="center"><b><{$smarty.const._AM_TDMDOWNLOADS_FORMRATING}></b></td>
            <td><b><{$smarty.const._AM_TDMDOWNLOADS_FORMDATE}></b></td>
            <td align="center"><b><{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}></b></td>
        </tr>

        <{foreach item=urating from=$ratings.user_list}>
            <tr class="<{cycle values='odd, even'}>">
                <td><{$urating.ratinguser}></td>
                <td><{$urating.ratinghostname}></td>
                <td><{$urating.rating}></td>
                <td><{$urating.ratingtimestamp}></td>
                <td><{$urating.myTextForm}></td>
            </tr>
        <{/foreach}>

        <tr>
            <td colspan="5"><br><b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME}> <{$ratings.anon_total}></b><br><br></td>
        </tr>
        <tr>
            <td colspan="2"><b><{$smarty.const._AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP}></b></td>
            <td align="center"><b><{$smarty.const._AM_TDMDOWNLOADS_FORMRATING}></b></td>
            <td><b><{$smarty.const._AM_TDMDOWNLOADS_FORMDATE}></b></td>
            <td align="center"><b><{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}></b></td>
        </tr>
        <{foreach item=arating from=$ratings.anon_list}>
            <tr class="<{cycle values='odd, even'}>">
                <td><{$arating.ratinghostname}></td>
                <td><{$arating.rating}></td>
                <td><{$arating.ratingtimestamp}></td>
                <td><{$arating.myTextForm}></td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>

<{if $themeForm|default:''}>
    <{$themeForm}>
<{/if}>

<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
