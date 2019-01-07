<div class="tdmdownloads">

    <!-- Download logo-->
    <div class="tdmdownloads-logo center marg10">
        <a title="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>" href="<{$xoops_url}>/modules/tdmdownloads/index.php"><img src="<{$xoops_url}>/modules/tdmdownloads/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>"></a>
    </div>

    <!-- Category path -->
    <div class="bold marg1 pad1"><{$navigation}></div>

    <!-- Submit helps -->
    <div class="tdmdownloads-tips">
        <ul>
            <li><{$smarty.const._MD_TDMDOWNLOADS_SUBMIT_SUBMITONCE}></li>
            <li><{$smarty.const._MD_TDMDOWNLOADS_SUBMIT_ALLPENDING}></li>
            <li><{$smarty.const._MD_TDMDOWNLOADS_SUBMIT_DONTABUSE}></li>
            <li><{$smarty.const._MD_TDMDOWNLOADS_SUBMIT_TAKEDAYS}></li>
        </ul>
    </div>

    <{if $error}>
        <div class='tdmdownloads-errorMsg errorMsg'><{$error}></div>
    <{/if}>

    <!-- Submit form -->
    <div class="tdmdownloads-submitform"><{$form}></div>

</div>
