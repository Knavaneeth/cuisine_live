var uploader;

function createUploader(ids, functions) {
    var image_filter = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'php'];
    var image_string = " jpg, jpeg, png, gif, bmp , php";
    var params = '&type=' + ids + "&currentController=" + $("#currentController").val();
    if( jQuery('.Inhouse_gallery').is(':visible') ) {  params += "&gallery_type=Inhouse_gallery";   }
    if( jQuery('.spl_gallery').is(':visible') ) {  params += "&gallery_type=spl_gallery";   }
    uploader = new qq.FileUploader({
        element: document.getElementById(ids),
        action: ajax_url + "?action=uploadImage&" + params + "&tbl",
        debug: false,
        onSubmit: function(id, fileName) {
            jQuery("." + ids + "_status_bar").css({
                width: "1%"
            });
            var ext = fileName.split('.').pop().toLowerCase();
            var filter = null;
            var filtermsg = null;
            filter = image_filter;
            filtermsg = image_string;
            if (jQuery.inArray(ext, filter) == -1) {
                alert(fileName + " has invalid extension. Only " + filtermsg + "are allowed.");
                return false;
            }
            jQuery(".qq-upload-list").hide();
            jQuery("." + ids + "_chart_status").show();
            ShowHideCancelUpload(ids, true);
        },
        onProgress: function(id, fileName, loaded, total) {
            var percentage = loaded / total;
            percentage = percentage * 100;
            var percent = percentage.toFixed(0);
            var loadedInMB = bytesToSize(loaded, 2);
            var totalInMB = bytesToSize(total, 2);
            var progress_file = "<br/> Uploading : " + loadedInMB + " Of " + totalInMB + "";
            jQuery("." + ids + "_percent_bar").html(percent + " %" + progress_file);
            jQuery("." + ids + "_status_bar").css({
                width: percent + "%"
            });
        },
        onComplete: function(id, fileName, responseJSON) {
            jQuery("." + ids + "_chart_status").hide();
            ShowHideCancelUpload(ids, false);
            if (responseJSON.code == 1) {                  
                if (functions) {
                    var fn = window[functions];                                          
                    fn(responseJSON);
                }
            } else alert(responseJSON.msg);
        },
        onCancel: function(id, fileName) {
            jQuery("." + ids + "_status_bar").css({
                width: "1%"
            });
            jQuery("." + ids + "_chart_status").hide();
            ShowHideCancelUpload(ids, false);
        },
        showMessage: function(message) {
            alert(message);
        }
    });
}

function ShowHideCancelUpload(ids, a) {
    if (ids == "btnchart") {
        if (a) {
            jQuery("#cancelChart").show();
        } else {
            jQuery("#cancelChart").hide();
        }
    } else {
        if (a) {
            jQuery("#cancelvideo").show();
        } else {
            jQuery("#cancelvideo").hide();
        }
    }
}

function cancelUpload(ids) {
    if (ids == "btnchart") {
        uploader_chart._handler.cancelAll();
    } else {
        uploader._handler.cancelAll();
    }
}

function bytesToSize(bytes, precision) {
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;
    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B';
    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
        return (bytes / kilobyte).toFixed(precision) + ' KB';
    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return (bytes / megabyte).toFixed(precision) + ' MB';
    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
        return (bytes / gigabyte).toFixed(precision) + ' GB';
    } else if (bytes >= terabyte) {
        return (bytes / terabyte).toFixed(precision) + ' TB';
    } else {
        return bytes + ' B';
    }
}