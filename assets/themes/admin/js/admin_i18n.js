/**
 * Upload file
 * ref:
 * - https://gist.github.com/Borodin/7256178
 * - https://zinoui.com/blog/ajax-file-upload
 */
function upload(input, to) {
    var $progressBar = $(input).siblings("#progress");
    var $uploadSukses = $(input).siblings("#upload_sukses");
    var $uploadGagal = $(input).siblings("#upload_gagal");
    var formData = new FormData();
    // formData.append('upfile', $('input[type=file]')[0].files[0]);
    // formData.append('upfile', input.files[0]);
    formData.append('upfile', $(input).prop('files')[0]);
    var xhr = new XMLHttpRequest();
    xhr.upload.onload = function(e) {
        // console.log('file upload');
    }
    xhr.upload.onloadstart = function(e) {
        $progressBar.value = 0;
        $progressBar.show();
        $uploadSukses.hide()
        $uploadGagal.hide()
    }
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            $progressBar.prop('max', e.total);
            $progressBar.prop('value', e.loaded);
            // var percentComplete = Math.floor((e.loaded / e.total) * 100) + '%';
            // console.log(percentComplete);
        }
    }
    xhr.upload.onloadend = function(e) {
        $progressBar.value = e.loaded;
        $progressBar.hide();
    }
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            var resp = JSON.parse(xhr.response);
            // console.log('Server got:', resp.message);
            to.parent().closest('div').removeClass('has-success')
            to.parent().closest('div').removeClass('has-error')
            if (!resp.error) {
                to.val(resp.message);
                to.parent().closest('div').addClass('has-success')
                $uploadSukses.show()
            } else {
                to.val('');
                to.parent().closest('div').addClass('has-error')
                $uploadGagal.html(resp.message);
                $uploadGagal.show();
            }
        }
    }
    xhr.open("POST", config.baseURL + "ajax/uploadfile", true);
    xhr.send(formData);
}
/**
 * Global admin functions
 */
var placeholder = "-- Pilih --";
$.fn.select2.defaults.set("theme", "bootstrap");
$(".select2").select2({
    placeholder: placeholder,
    width: null,
});
/**
 * Document ready
 */
$(document).ready(function() {
    $('.page-header').prepend($('.header'))
    /**
     * Enable tooltips
     */
    if ($('.tooltips').length) {
        $('.tooltips').tooltip();
    }
    /**
     * Activate any date pickers
     */
    if ($(".input-group.date").length) {
        $(".input-group.date").datepicker({
            autoclose: true,
            todayHighlight: true
        });
    }
    /**
     * Detect items per page change on all list pages and send users back to page 1 of the list
     */
    $('select#limit').change(function() {
        var limit = $(this).val();
        var currentUrl = document.URL.split('?');
        var uriParams = "";
        var separator;
        if (currentUrl[1] != undefined) {
            var parts = currentUrl[1].split('&');
            for (var i = 0; i < parts.length; i++) {
                if (i == 0) {
                    separator = "?";
                } else {
                    separator = "&";
                }
                var param = parts[i].split('=');
                if (param[0] == 'limit') {
                    uriParams += separator + param[0] + "=" + limit;
                } else if (param[0] == 'offset') {
                    uriParams += separator + param[0] + "=0";
                } else {
                    uriParams += separator + param[0] + "=" + param[1];
                }
            }
        } else {
            uriParams = "?limit=" + limit;
        }
        // reload page
        window.location.href = currentUrl[0] + uriParams;
    });
    /**
     * Enable Summernote WYSIWYG editor on any textareas with the 'editor' class
     */
    if ($('textarea.editor').length) {
        $('textarea.editor').each(function() {
            var id = $(this).attr('id');
            $('#' + id).summernote({
                height: 300
            });
        });
    }
});