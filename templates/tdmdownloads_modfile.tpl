<div class="tdmdownloads">

    <!-- Download logo-->
    <div class="tdmdownloads-logo center marg10">
        <a title="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>" href="<{$mod_url}>/index.php"><img src="<{$mod_url}>/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>"></a>
    </div>

    <!-- Category path -->
    <div class="bold marg1 pad1"><{$navigation|default:false}></div>

    <{if $message_erreur|default:''}>
        <div class='tdmdownloads-errorMsg errorMsg'><{$message_erreur}></div>
    <{/if}>

    <!-- Submit form -->
    <div class="tdmdownloads-submitform"><{$themeForm|default:false}></div>

</div>
