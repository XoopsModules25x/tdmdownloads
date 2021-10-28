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
        margin-top: 0;
        margin-bottom: 50px;
    }
</style>
<{include file='db:tdmdownloads_admin_header.tpl'}>
<{if $message_erreur|default:''}>
    <div class='errorMsg'><{$message_erreur}></div>
<{/if}>

<{if $successes|default:''}>
    <{foreach item=success from=$successes}>

    <{/foreach}>
<{/if}>
<{if $errors|default:''}>
    <{foreach item=error from=$errors}>
        <span style="color: #ff0000; "><{$error.title}>: </span><{$error.info}>
        <br>
    <{/foreach}>
<{/if}>


<{if $intro}>
    <div class='intro'><{$smarty.const._AM_TDMDOWNLOADS_IMPORT_WARNING}></div>
<{/if}>

<{if $themeForm|default:''}>
    <{$themeForm}>
<{/if}>

<br>
<!-- Footer --><{include file='db:tdmdownloads_admin_footer.tpl'}>
