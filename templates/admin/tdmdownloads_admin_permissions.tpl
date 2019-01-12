<!-- Header -->
<{include file='db:tdmdownloads_admin_header.tpl'}>
<{if $message_erreur}>
    <div class='errorMsg'><{$message_erreur}></div>
<{/if}>
<{if $form_select}>
    <{$form_select}>
<{/if}>
<div class='spacer' style='margin-bottom:30px'></div>

<{if $form_permissions}>
    <{$form_permissions}>
<{/if}>

<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
