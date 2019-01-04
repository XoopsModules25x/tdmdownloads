<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>
            
<{if $fields_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMTITLE}></th>
                <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMIMAGE}></th>
                <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMWEIGHT}></th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMAFFICHE}></th>
                <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMAFFICHESEARCH}></th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></th>
			</tr>
		</thead>
		<{if $fields_count}>
			<tbody>
				<{foreach item=field from=$fields_list}>
					<tr class="<{cycle values='odd, even'}>">
						<td class='left'><{$field.title}></td>
						<td class='center'><img src="<{$field.img}>" alt="<{$field.title}>" title="<{$field.title}>"></td>
                        <td class='center'><{$field.weight}></td>
						<td class='center'>
                            <a href="field.php?op=update_status&amp;fid=<{$field.fid}>&aff=<{if $field.status == 1}>0"><img src="<{$modPathIcon16}>/on.png"><{else}>1"><img src="<{$modPathIcon16}>/off.png"><{/if}></a>
                        </td>
                        <td class='center'>
                            <a href="field.php?op=update_search&amp;fid=<{$field.fid}>&aff=<{if $field.search == 1}>0"><img src="<{$modPathIcon16}>/on.png"><{else}>1"><img src="<{$modPathIcon16}>/off.png"><{/if}></a>
                        </td>

                        
						<td class='center'>
                            <a href="field.php?op=edit_field&amp;fid=<{$field.fid}>"><img src="<{$modPathIcon16}>/edit.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMEDIT}>"></a>
                            <{if $field.status_def < 1}>
                                <a href="field.php?op=del_field&amp;fid=<{$field.fid}>"><img src="<{$modPathIcon16}>/delete.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDEL}>"></a>
                            <{/if}>
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
