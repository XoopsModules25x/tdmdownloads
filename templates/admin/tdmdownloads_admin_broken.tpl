<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>

<{if $broken_list}>
    <table class='table table-bordered'>
        <thead>
        <tr class='head'>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMFILE}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMTITLE}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_BROKEN_SENDER}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></th>
        </tr>
        </thead>
        <{if $broken_count}>
            <tbody>
            <{foreach item=broken from=$broken_list}>
                <tr class="<{cycle values='odd, even'}>">
                    <td class='left'><a href="../visit.php?cid=<{$broken.cid}>&amp;lid=<{$broken.lid}>" target="_blank"><img src="<{$pathModIcon16}>/download-now.png" alt="Download <{$broken.title}>" title="Download <{$broken.title}>"></a></td>
                    <td class='center'><{$broken.title}></td>
                    <td class='left'><b><{$broken.sender}></b> (<{$broken.ip}>)</td>
                    <td class='center'>
                        <a href="downloads.php?op=view_downloads&downloads_lid=<{$broken.lid}>"><img src="<{$pathModIcon16}>/view_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>"></a>
                        <a href="downloads.php?op=edit_downloads&downloads_lid=<{$broken.lid}>"><img src="<{$pathModIcon16}>/edit.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>"></a>
                        <a href="broken.php?op=del_brokendownloads&broken_id=<{$broken.reportid}>"><img src="<{$pathModIcon16}>/ignore_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMIGNORE}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMIGNORE}>"></a>
                    </td>
                </tr>
            <{/foreach}>
            </tbody>
        <{/if}>
    </table>
    <div class='clear'>&nbsp;</div>
    <{if $pagenav}>
        <div class='xo-pagenav floatright'><{$pagenav}></div>
        <div class='clear spacer'></div>
    <{/if}>
<{/if}>

<{if $form}>
    <{$form}>
<{/if}>

<{if $error}>
    <div class='errorMsg'><strong><{$error}></strong></div>
<{/if}>
<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
