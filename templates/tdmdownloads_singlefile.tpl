<div class="tdmdownloads">

    <!-- Download logo-->
    <div class="tdmdownloads-logo center marg10">
        <a title="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>" href="<{$mod_url}>/index.php"><img src="<{$mod_url}>/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>"></a>
    </div>

    <!-- Category path -->
    <div class="bold marg1 pad1"><{$navigation}></div>

    <!-- Start Donload body info -->
    <div class="item tdmdownloads-item">
        <div class="itemHead">
            <span class="itemTitle"><h1><{$title}> <{$version}></h1></span>
        </div>
        <{if $new || $pop}>
            <div class="itemInfo">
                <span class="itemPoster"><{$new}></span>
                <span class="itemPostDate"><{$pop}></span>
            </div>
        <{/if}>
        <div class="itemBody">
            <div class="itemText tdmdownloads-itemText <{$textfloat}>">
                <{if $show_screenshot === true}>
                    <{if $logourl|default:'' != ''}>
                        <img class="<{$img_float}>" width="<{$shotwidth}>" src="<{$logourl}>" alt="<{$title}>">
                    <{/if}>
                <{/if}>
                <{$description}>
            </div>
            <div class="tdmdownloads-downInfo <{$infofloat}>">
                <div class="tdmdownloads-box" id="tdmdownloads-box-1">
                    <div id="date"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_DATEPROP}><{$date}></div>
                    <div id="author"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_AUTHOR}><{$author}></div>
                    <div id="hits"><{$hits}></div>
                    <div id="rating"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_RATING}><{$rating}><{$votes}></div>
                    <{if $commentsnav|default:'' != ''}>
                        <div id="comments"><{$nb_comments}></div>
                    <{/if}>
                </div>
                <{if $sup_aff === true}>
                    <div class="tdmdownloads-box" id="tdmdownloads-box-2">
                        <{foreach item=champ from=$champ_sup}>
                            <div class="champ" style="background: url(<{$champ.image}>) no-repeat left;"><{$champ.data}></div>
                        <{/foreach}>
                    </div>
                <{/if}>
                <div class="tdmdownloads-box" id="tdmdownloads-box-3">
                    <{if $perm_vote|default:'' != ''}>
                        <div id="torate">
                            <a href="<{$mod_url}>/ratefile.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE}>"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE}></a>
                        </div>
                    <{/if}>
                    <{if $perm_modif|default:'' != ''}>
                        <div id="tomodify">
                            <a href="<{$mod_url}>/modfile.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_MODIFY}>"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_MODIFY}></a>
                        </div>
                    <{/if}>
                    <div id="toreport">
                        <a href="<{$mod_url}>/brokenfile.php?lid=<{$lid}>" title="<{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN}>"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN}></a>
                    </div>
                    <div id="totell"><{$tellafriend_texte}></div>
                </div>
                <{if $perm_download|default:'' != ''}>
                    <div class="tdmdownloads-box" id="tdmdownloads-box-4">
                        <div id="download">
                            <a href="visit.php?cid=<{$cid}>&amp;lid=<{$lid}>" rel="directory nofollow external"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_DLNOW}></a>
                        </div>
                    </div>
                <{else}>
                    <div class="tdmdownloads-box" id="tdmdownloads-box-5">
                        <div id="noperm"><{$smarty.const._MD_TDMDOWNLOADS_SINGLEFILE_NOPERM}></div>
                    </div>
                <{/if}>
                <{if $paypal}>
                    <div id="paypal">
                        <{$paypal}>
                    </div>
                <{/if}>
            </div>
            <div class="endline"></div>
        </div>
        <{if $adminlink}>
            <div class="itemFoot">
                <span class="itemAdminLink"><{$adminlink}></span>
            </div>
        <{/if}>
    </div>
    <!-- End Donload body info -->

    <{if $tags}>
        <!-- Tag bar-->
        <div class="tdmdownloads-tag"><{include file="db:tag_bar.tpl"}></div>
    <{/if}>

    <{if $show_social}>
        <!-- Social Networks -->
        <div class="tdmdownloads-socialnetwork">
            <ul>
                <li>
                    <div class="facebook">
                        <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                        <fb:like href="<{$mod_url}>/singlefile.php?lid=<{$lid}>" layout="button_count" show_faces="false"></fb:like>
                    </div>
                </li>
                <li>
                    <div class="twitter">
                        <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
                        <a href="http://twitter.com/share/<{$mod_url}>/singlefile.php?lid=<{$lid}>" class="twitter-share-button">Tweet</a>
                    </div>
                </li>
                <li>
                    <div class="google">
                        <script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>
                        <g:plusone size="medium" count="true"></g:plusone>
                    </div>
                </li>
            </ul>
        </div>
    <{/if}>

    <{if $show_bookmark|default:false}>
        <!-- Bookmarks -->
        <div class="tdmdownloads-bookmarkme">
            <div class="head tdmdownloads-bookmarkmetitle"><{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_ME}></div>
            <div class="tdmdownloads-bookmarkmeitems">
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_BLINKLIST}>" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&Description=&Url=<{$mod_url}>/singlefile.php?lid=<{$lid}>&Title=<{$downloads.title}>"><img
                            alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_BLINKLIST}>" src="<{$mod_url}>/assets/images/bookmarks/blinklist.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DELICIOUS}>" href="http://del.icio.us/post?url=<{$mod_url}>/singlefile.php?lid=<{$lid}>&title=<{$downloads.title}>"><img
                            alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DELICIOUS}>" src="<{$mod_url}>/assets/images/bookmarks/delicious.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DIGG}>" href="http://digg.com/submit?phase=2&url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DIGG}>"
                                                                                                                                                                                               src="<{$mod_url}>/assets/images/bookmarks/diggman.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FARK}>"
                   href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=<{$mod_url}>/singlefile.php?lid=<{$lid}>&new_comment=<{$downloads.title}>&new_link_other=<{$downloads.title}>&linktype=Misc"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FARK}>"
                                                                                                                                                                                                        src="<{$mod_url}>/assets/images/bookmarks/fark.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FURL}>" href="http://www.furl.net/storeIt.jsp?t=<{$downloads.title}>&u=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FURL}>"
                                                                                                                                                                                                                     src="<{$mod_url}>/assets/images/bookmarks/furl.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_NEWSVINE}>" href="http://www.nwvine.com/_tools/seed&save?u=<{$mod_url}>/singlefile.php?lid=<{$lid}>&h=<{$downloads.title}>"><img
                            alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_NEWSVINE}>" src="<{$mod_url}>/assets/images/bookmarks/newsvine.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_REDDIT}>" href="http://reddit.com/submit?url=<{$mod_url}>/singlefile.php?lid=<{$lid}>&title=<{$downloads.title}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_REDDIT}>"
                                                                                                                                                                                                                      src="<{$mod_url}>/assets/images/bookmarks/reddit.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_YAHOO}>" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?t=<{$downloads.title}>&u=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img
                            alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_YAHOO}>" src="<{$mod_url}>/assets/images/bookmarks/yahoomyweb.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_BALATARIN}>" href="http://balatarin.com/links/submit?phase=2&amp;url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_BALATARIN}>"
                                                                                                                                                                                                                   src="<{$mod_url}>/assets/images/bookmarks/balatarin.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FACEBOOK}>" href="http://www.facebook.com/share.php?u=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_FACEBOOK}>"
                                                                                                                                                                                                    src="<{$mod_url}>/assets/images/bookmarks/facebook_share_icon.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_TWITTER}>" href="http://twitter.com/home?status=Browsing:%20<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_TWITTER}>"
                                                                                                                                                                                                          src="<{$mod_url}>/assets/images/bookmarks/twitter_share_icon.gif"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_SCRIPSTYLE}>" href="http://scriptandstyle.com/submit?url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_SCRIPSTYLE}>"
                                                                                                                                                                                                       src="<{$mod_url}>/assets/images/bookmarks/scriptandstyle.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_STUMBLE}>" href="http://www.stumbleupon.com/submit?url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_STUMBLE}>"
                                                                                                                                                                                                     src="<{$mod_url}>/assets/images/bookmarks/stumbleupon.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_TECHNORATI}>" href="http://technorati.com/faves?add=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_TECHNORATI}>"
                                                                                                                                                                                                  src="<{$mod_url}>/assets/images/bookmarks/technorati.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_MIXX}>" href="http://www.mixx.com/submit?page_url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_MIXX}>"
                                                                                                                                                                                                src="<{$mod_url}>/assets/images/bookmarks/mixx.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_MYSPACE}>" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_MYSPACE}>"
                                                                                                                                                                                                              src="<{$mod_url}>/assets/images/bookmarks/myspace.jpg"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DESIGNFLOAT}>" href="http://www.designfloat.com/submit.php?url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_DESIGNFLOAT}>"
                                                                                                                                                                                                             src="<{$mod_url}>/assets/images/bookmarks/designfloat.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_GOOGLEPLUS}>" href="https://plusone.google.com/_/+1/confirm?hl=en&url=<{$mod_url}>/singlefile.php?lid=<{$lid}>"><img alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_GOOGLEPLUS}>"
                                                                                                                                                                                                                    src="<{$mod_url}>/assets/images/bookmarks/google_plus_icon.png"></a>
                <a rel="nofollow external" title="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_GOOGLEBOOKMARKS}>" href="https://www.google.com/bookmarks/mark?op=add&amp;bkmk=<{$mod_url}>/singlefile.php?lid=<{$lid}>&amp;title=<{$downloads.title}>"><img
                            alt="<{$smarty.const._MD_TDMDOWNLOADS_BOOKMARK_TO_GOOGLEBOOKMARKS}>" src="<{$mod_url}>/assets/images/bookmarks/google-icon.png"></a>
            </div>
        </div>
    <{/if}>
	<{if $commentsnav|default:'' || $lang_notice|default:''}>
		<div style="text-align: center; padding: 3px; margin:3px;">
			<{$commentsnav|default:''}>
			<{$lang_notice|default:''}>
		</div>
	<{/if}>
    <div style="margin:3px; padding: 3px;">
        <{if $comment_mode|default:'' == "flat"}>
            <{include file="db:system_comments_flat.tpl"}>
        <{elseif $comment_mode|default:'' == "thread"}>
            <{include file="db:system_comments_thread.tpl"}>
        <{elseif $comment_mode|default:'' == "nest"}>
            <{include file="db:system_comments_nest.tpl"}>
        <{/if}>
    </div>
    <{include file="db:system_notification_select.tpl"}>
</div>
