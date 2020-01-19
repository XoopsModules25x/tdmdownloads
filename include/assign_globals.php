<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

$GLOBALS['myalbum_assign_globals'] = [
    'lang_total'           => _ALBM_CAPTION_TOTAL,
    'mod_url'              => $mod_url,
    'mod_copyright'        => $mod_copyright,
    'lang_latest_list'     => _ALBM_LATESTLIST,
    'lang_descriptionc'    => _ALBM_DESCRIPTIONC,
    'lang_lastupdatec'     => _ALBM_LASTUPDATEC,
    'lang_submitter'       => _ALBM_SUBMITTER,
    'lang_hitsc'           => _ALBM_HITSC,
    'lang_commentsc'       => _ALBM_COMMENTSC,
    'lang_new'             => _ALBM_NEW,
    'lang_updated'         => _ALBM_UPDATED,
    'lang_popular'         => _ALBM_POPULAR,
    'lang_ratethisphoto'   => _ALBM_RATETHISPHOTO,
    'lang_editthisphoto'   => _ALBM_EDITTHISPHOTO,
    'lang_deletethisphoto' => _ALBM_DELETE_THIS_PHOTO,
    'lang_guestname'       => _ALBM_CAPTION_GUESTNAME,
    'lang_category'        => _ALBM_CAPTION_CATEGORY,
    'lang_nomatch'         => _ALBM_NOMATCH,
    'lang_directcatsel'    => _ALBM_DIRECTCATSEL,
    'photos_url'           => $photos_url,
    'thumbs_url'           => $thumbs_url,
    'thumbsize'            => $myalbum_thumbsize,
    'colsoftableview'      => $myalbum_colsoftableview,
    'canrateview'          => $GLOBALS['global_perms'] && GPERM_RATEVIEW,
    'canratevote'          => $GLOBALS['global_perms'] && GPERM_RATEVOTE
];
