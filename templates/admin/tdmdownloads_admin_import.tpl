<!-- Header -->
<style>
    .intro {
        background: #FBE3E4;
        color: #D12F19;
        text-align: center;
        border: 2px solid #FBC2C4;
        padding: 40px;
        font-weight: bold;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        border-radius: 4px;
        line-height: 140%;
        margin-top:0px;
        margin-bottom:50px;
    }
</style>
<{include file='db:tdmdownloads_admin_header.tpl'}>

<{if $successes}>
    <{foreach item=success from=$successes}>
        
    <{/foreach}>
<{/if}>
<{if $errors}>
    <{foreach item=error from=$errors}>
        <font color='#ff0000'><{$error.title}>: </font><{$error.info}><br>
    <{/foreach}>
<{/if}>


<{if $intro}>
    <div class='intro'><{$smarty.const._AM_TDMDOWNLOADS_IMPORT_WARNING}></div>
<{/if}>

<{if $form}>
	<{$form}>
<{/if}>
	
<{if $error}>
	<div class='errorMsg'><strong><{$error}></strong></div>
<{/if}>
<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
