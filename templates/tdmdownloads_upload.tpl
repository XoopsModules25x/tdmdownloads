<{include file='db:tdmdownloads_header.tpl'}>

<{if $form|default:false}>
    <{$form}>
<{/if}>

<{if $multiupload|default:false}>
    <div class="clear">&nbsp;</div>
    <{include file="db:tdmdownloads_trigger_uploads.tpl"}>
    <h2><{$categoryname|default:''}></h2>
    <div id="fine-uploader-manual-trigger"></div>
    <div><{$smarty.const._IMGMAXSIZE}> <{$file_maxsize}></div>
    <div><{$smarty.const._IMGMAXWIDTH}> <{$img_maxwidth}></div>
    <div><{$smarty.const._IMGMAXHEIGHT}> <{$img_maxheight}></div>
    <!-- Your code to create an instance of Fine Uploader and bind to the DOM/template
    ====================================================================== -->
    <script>
        var filesTotal = 0;
        var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: '<{$xoops_url}>/ajaxfineupload.php',
                params: {
                    "Authorization": "<{$jwt}>"
                }
            },
            text: {
                formatProgress: "<{$smarty.const._FORMATPROGRESS}>",
                failUpload: "<{$smarty.const._FAILUPLOAD}>",
                waitingForResponse: "<{$smarty.const._WAITINGFORRESPONSE}>",
                paused: "<{$smarty.const._PAUSED}>"
            },
            messages: {
                typeError: "<{$smarty.const._TYPEERROR}>",
                sizeError: "<{$smarty.const._SIZEERROR}>",
                minSizeError: "<{$smarty.const._MINSIZEERROR}>",
                emptyError: "<{$smarty.const._EMPTYERROR}>",
                noFilesError: "<{$smarty.const._NOFILESERROR}>",
                tooManyItemsError: "<{$smarty.const._TOOMANYITEMSERROR}>",
                maxHeightImageError: "<{$smarty.const._MAXHEIGHTIMAGEERROR}>",
                maxWidthImageError: "<{$smarty.const._MAXWIDTHIMAGEERROR}>",
                minHeightImageError: "<{$smarty.const._MINHEIGHTIMAGEERROR}>",
                minWidthImageError: "<{$smarty.const._MINWIDTHIMAGEERROR}>",
                retryFailTooManyItems: "<{$smarty.const._RETRYFAILTOOMANYITEMS}>",
                onLeave: "<{$smarty.const._ONLEAVE}>",
                unsupportedBrowserIos8Safari: "<{$smarty.const._UNSUPPORTEDBROWSERIOS8SAFARI}>"
            },
            thumbnails: {
                placeholders: {
                    waitingPath: '<{$xoops_url}>/media/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: '<{$xoops_url}>/media/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            validation: {
                acceptFiles: [<{$allowedmimetypes}>],
                allowedExtensions: [<{$allowedfileext}>],
                image: {
                    maxHeight: <{$img_maxheight}>,
                    maxWidth: <{$img_maxwidth}>
                },
                sizeLimit: <{$file_maxsize}>
            },
            autoUpload: false,
            callbacks: {
                onError: function (id, name, errorReason, xhrOrXdr) {
                    console.log(qq.format("Error uploading {}.  Reason: {}", name, errorReason));
                },
                onStatusChange: function (id, oldStatus, newStatus) {
                    document.getElementById("qq-uploader-status").classList.remove("qq-hide");
                    if (newStatus == "submitting") {
                        filesTotal = id;
                    }
                },
                onSubmitted: function (id, name) {
                    if (id == filesTotal) {
                        document.getElementById('qq-uploader-status-text').innerHTML = '<{$smarty.const.CO_TDMDOWNLOADS_FU_SUBMITTED}>';
                    } else {
                        document.getElementById('qq-uploader-status-text').innerHTML = '<{$smarty.const.CO_TDMDOWNLOADS_FU_SUBMIT}>' + (id + 1);
                    }
                },
                onUpload: function (id, name) {
                    document.getElementById('qq-uploader-status-text').innerHTML = '<{$smarty.const.CO_TDMDOWNLOADS_FU_UPLOAD}>' + id;
                },
                onAllComplete: function (succeeded, failed) {
                    if (failed.length > 0) {
                        document.getElementById('qq-uploader-status-text').innerHTML = '<{$smarty.const.CO_TDMDOWNLOADS_FU_FAILED}>';
                    } else {
                        document.getElementById('qq-uploader-status-text').innerHTML = '<{$smarty.const.CO_TDMDOWNLOADS_FU_SUCCEEDED}>';
                    }
                }
            },
            debug: <{$fineup_debug}>
        });

        qq(document.getElementById("trigger-upload")).attach("click", function () {
            manualUploader.uploadStoredFiles();
        });
    </script>
<{/if}>
<div class="clear">&nbsp;</div>
<div class='multiupload-footer'>
    <{if $catId}>
        <div class='col-xs-12 col-sm-12 right'>
            <a class='btn btn-default wgg-btn' href='admin/category.php?op=edit_cat&amp;downloadscat_cid=<{$catId}>' title='<{$smarty.const.CO_TDMDOWNLOADS_ALBUM_EDIT}>'>
                <span class="wgg-btn-icon"><img class='' src='<{$pathIcon16}>/edit.png' alt='<{$smarty.const.CO_TDMDOWNLOADS_ALBUM_EDIT}>'></span><{$smarty.const.CO_TDMDOWNLOADS_ALBUM_EDIT}>
            </a>
        </div>
    <{/if}>
</div>


<{include file='db:tdmdownloads_footer.tpl'}>
