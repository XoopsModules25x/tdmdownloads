<div class="tdmdownloads">

    <!-- Download logo-->
    <div class="tdmdownloads-logo center marg10">
        <a title="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>" href="<{$mod_url}>/index.php"><img src="<{$mod_url}>/assets/images/logo-en.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_DOWNLOAD}>"></a>
    </div>

    <{if is_array($categories|default:'') && count($categories) gt 0}>
        <!-- Start Show categories information -->
        <div class="tdmdownloads-categories">
            <table>
                <tr>
                    <{foreach item=category from=$categories}>
                    <td>
                        <div class="tdmdownloads-data">
                            <div class="tdmdownloads-title">
                                <div class="floatleft title"><h2>
                                        <a title="<{$category.title}>" href="<{$mod_url}>/viewcat.php?cid=<{$category.id}>"><{$category.title}></a>
                                    </h2></div>
                                <div class="floatright total xo-pagact">
                                    <a title="<{$category.title}>" href="<{$mod_url}>/viewcat.php?cid=<{$category.id}>"><{$category.totaldownloads}></a>
                                </div>
                                <div class="endline"></div>
                            </div>
                            <div class="tdmdownloads-body justify">
                                <{if $category.image|default:'' != ''}>
                                    <a class="marg1 pad1" title="<{$category.title}>" href="<{$mod_url}>/viewcat.php?cid=<{$category.id}>"><img class="<{$img_float}>" src="<{$category.image}>" alt="<{$category.title}>"></a>
                                <{/if}>
                                <{$category.description_main}>
                                <div class="endline"></div>
                            </div>
                            <{if $category.subcategories|default:'' != ''}>
                                <div class="tdmdownloads-subtitle"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_SCAT}>
                                    <ul><{$category.subcategories}></ul>
                                </div>
                            <{/if}>
                        </div>
                    </td>
                    <{if $category.count is div by $nb_catcol}>
                </tr>
                <tr>
                    <{/if}>
                    <{/foreach}>
                </tr>
            </table>
        </div>
        <!-- End Show categories information -->

        <!-- RSS logo -->
        <div class="tdmdownloads-rss">
            <a title="<{$smarty.const._MD_TDMDOWNLOADS_RSS}>" href="<{$mod_url}>/rss.php?cid=0"><img src="<{$mod_url}>/assets/images/rss.gif" alt="<{$smarty.const._MD_TDMDOWNLOADS_RSS}>"></a>
        </div>
        <{if $bl_affichage|default:0 == 1}>
            <!-- Start Summary informations -->
            <div class="tdmdownloads-linetitle"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLNAME}></div>
            <table class="mrag2 pad2 tdmdownloads-summary">
                <tr>
                    <{if $bl_date|default:'' != ''}>
                        <td class="width33 top">
                            <div class="bold mrag2 pad2">
                                <img src="<{$mod_url}>/assets/images/icons/16/date.png" alt="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLDATE}>"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLDATE}>
                            </div>
                            <div class="mrag2 pad2">
                                <ul>
                                    <{foreach item=bl_date_itm from=$bl_date}>
                                        <li>
                                            <a title="<{$bl_date_itm.title}>" href="<{$mod_url}>/singlefile.php?cid=<{$bl_date_itm.cid}>&amp;lid=<{$bl_date_itm.id}>"><{$bl_date_itm.title}></a>
                                            (<{$bl_date_itm.date}>)
                                        </li>
                                    <{/foreach}>
                                </ul>
                            </div>
                        </td>
                    <{/if}>
                    <{if $bl_pop|default:'' != ''}>
                        <td class="width33 top">
                            <div class="bold mrag2 pad2">
                                <img src="<{$mod_url}>/assets/images/icons/16/hits.png" alt="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLPOP}>"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLPOP}>
                            </div>
                            <div class="mrag2 pad2">
                                <ul>
                                    <{foreach item=bl_pop_itm from=$bl_pop}>
                                        <li>
                                            <a title="<{$bl_pop_itm.title}>" href="<{$mod_url}>/singlefile.php?cid=<{$bl_pop_itm.cid}>&amp;lid=<{$bl_pop_itm.id}>"><{$bl_pop_itm.title}></a>
                                            (<{$bl_pop_itm.hits}>)
                                        </li>
                                    <{/foreach}>
                                </ul>
                            </div>
                        </td>
                    <{/if}>
                    <{if $bl_rating|default:'' != ''}>
                        <td class="width33 top">
                            <div class="bold mrag2 pad2">
                                <img src="<{$mod_url}>/assets/images/icons/16/votes.png" alt="<{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLRATING}>"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_BLRATING}>
                            </div>
                            <div class="mrag2 pad2">
                                <ul>
                                    <{foreach item=bl_rating_itm from=$bl_rating}>
                                        <li>
                                            <a title="<{$bl_rating_itm.title}>" href="<{$mod_url}>/singlefile.php?cid=<{$bl_rating_itm.cid}>&amp;lid=<{$bl_rating_itm.id}>"><{$bl_rating_itm.title}></a>
                                            (<{$bl_rating_itm.rating}>)
                                        </li>
                                    <{/foreach}>
                                </ul>
                            </div>
                        </td>
                    <{/if}>
                </tr>
            </table>
            <!-- End Summary informations -->
        <{/if}>
        <div class="tdmdownloads-thereare"><{$lang_thereare}></div>
    <{/if}>

    <{if $show_latest_files|default:false}>
        <!-- check if Show new files in index -->
        <{if $file|default:'' != ''}>
            <!-- Start Show new files in index -->
            <div class="tdmdownloads-linetitle"><{$smarty.const._MD_TDMDOWNLOADS_INDEX_LATESTLIST}></div>
            <table>
                <tr>
                    <!-- Start new link loop -->
                    <{section name=i loop=$file}>
                    <td class="col_width<{$nb_dowcol}> top">
                        <{include file="db:tdmdownloads_download.tpl" down=$file[i]}>
                    </td>
                    <{if $file[i].count is div by $nb_dowcol}>
                </tr>
                <tr>
                    <{/if}>
                    <{/section}>
                    <!-- End new link loop -->
                </tr>
            </table>
            <!-- End Show new files in index -->
        <{/if}>
        <!-- End check if Show new files in index -->
    <{/if}>

    <{include file="db:system_notification_select.tpl"}>
</div>
