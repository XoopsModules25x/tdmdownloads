<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>

<{if $categories_list}>
    <table class='table table-bordered'>
        <thead>
        <tr class='head'>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMTITLE}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMIMG}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMTEXT}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMWEIGHT}></th>
            <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></th>
        </tr>
        </thead>
        <{if $categories_count}>
            <tbody>
            <{foreach item=category from=$categories_list}>
                <tr class="<{cycle values='odd, even'}>">
                    <td class='left'><a href="<{$tdmdownloads_url}>/viewcat.php?cid=<{$category.cid}>"><{$category.category}></a></td>
                    <td class='center'><img src="<{$category.cat_imgurl}>" alt="<{$category.title}>" title="<{$category.title}>"></td>
                    <td class='left'><{$category.cat_description_main}></td>
                    <td class='center'><{$category.cat_weight}></td>

                    <td class='center'>
                        <a href='category.php?op=edit_cat&amp;downloadscat_cid=<{$category.cid}>' title='<{$smarty.const._EDIT}>'>
                            <img src='<{$pathModIcon16}>/edit.png' alt='<{$smarty.const._EDIT}>'>
                        </a>
                        <a href='category.php?op=del_cat&amp;downloadscat_cid=<{$category.cid}>' title='<{$smarty.const._DELETE}>'>
                            <img src='<{$pathModIcon16}>/delete.png' alt='<{$smarty.const._DELETE}>'>
                        </a>
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

<{if $themeForm}>
    <{$themeForm}>
<{/if}>

<{if $message_erreur}>
    <div class='errorMsg'><{$message_erreur}></div>
<{/if}>
<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
