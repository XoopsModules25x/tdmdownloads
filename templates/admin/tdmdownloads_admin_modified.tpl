<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>

<style>
    .style_dif {color: #FF0000; font-weight: bold;}
</style>
        
<{if $modified_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
                <th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMTITLE}></th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_BROKEN_SENDER}></th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_FORMACTION}></th>
			</tr>
		</thead>
		<{if $modified_count}>
			<tbody>
				<{foreach item=modified from=$modified_list}>
					<tr class="<{cycle values='odd, even'}>">
						<td class='left'><{$modified.download_title}></td>
						<td class='center'><{$modified.modifysubmitter}></td>
						<td class='center'>
                            <a href="modified.php?op=view_downloads&downloads_lid=<{$modified.lid}>&amp;mod_id=<{$modified.requestid}>"><img src="<{$modPathIcon16}>/view_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMDISPLAY}>"></a>
                            <a href="modified.php?op=del_moddownloads&amp;mod_id=<{$modified.requestid}>&amp;new_file=<{$modified.new_file}>"><img src="<{$modPathIcon16}>/ignore_mini.png" alt="<{$smarty.const._AM_TDMDOWNLOADS_FORMIGNORE}>" title="<{$smarty.const._AM_TDMDOWNLOADS_FORMIGNORE}>"></a>
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

<{if $compare_list}>
    <table class='table table-bordered'>
		<thead>
			<tr class='head'>
                <th class='center'>&nbsp;</th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_BROKEN_SENDER}></th>
				<th class='center'><{$smarty.const._AM_TDMDOWNLOADS_MODIFIED_MOD}></th>
			</tr>
		</thead>
        <tbody>
            <{foreach key=ftype item=compare from=$compare_list}>
                <{if $ftype == 'img'}>
                    <tr class="<{if $compare.current == $compare.modified}><{cycle values='odd, even'}><{else}>style_dif<{/if}>">
                        <td class='left'><{$compare.info}></td>
                        <td class='left'><img src="<{$uploadurl_shots}><{$compare.current}>" alt="<{$compare.current}>" title="<{$compare.current}>"></td>
                        <td class='left'><img src="<{$uploadurl_shots}><{$compare.modified}>" alt="<{$compare.modified}>" title="<{$compare.modified}>"></td>
                    </tr>
                <{elseif $ftype == 'cfields'}>
                    <{foreach item=mfield from=$compare}>
                        <tr class="<{if $mfield.current == $mfield.modified}><{cycle values='odd, even'}><{else}>style_dif<{/if}>">
                            <td class='left'><{$mfield.info}></td>
                            <td class='left'><{$mfield.current}></td>
                            <td class='left'><{$mfield.modified}></td>
                        </tr>
                    <{/foreach}> 
                <{else}>
                    <tr class="<{if $compare.current == $compare.modified}><{cycle values='odd, even'}><{else}>style_dif<{/if}>">
                        <td class='left'><{$compare.info}></td>
                        <td class='left'><{$compare.current}></td>
                        <td class='left'><{$compare.modified}></td>
                    </tr>
                <{/if}>
                
            <{/foreach}>      
		</tbody>
	</table>
    <table class='table table-bordered'>
        <tbody>
            <tr class="<{cycle values='odd, even'}>">
                <{foreach item=button from=$cbuttons}>
                    <td class='<{cycle values='right, center, left'}>'><{$button}></td>
                <{/foreach}>
            </tr>
        </tbody>
    </table>
<{/if}>


<{if $form}>
	<{$form}>
<{/if}>
	
<{if $error}>
	<div class='errorMsg'><strong><{$error}></strong></div>
<{/if}>
<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
