layui.use(['jquery', 'element'], function () {

    var $ = layui.jquery;
    var element = layui.element;

    var getSignature = function () {
        var result = '';
        $.ajax({
            type: 'POST',
            url: '/admin/upload/vod/sign',
            async: false,
            success: function (res) {
                result = res.sign;
            }
        });
        return result;
    };

    $('#upload-btn').on('click', function () {
        $('input[name=file]').trigger('click');
    });

    $('input[name=file]').on('change', function (e) {

        var tcVod = new TcVod.default({
            getSignature: getSignature
        });

        var uploader = tcVod.upload({
            mediaFile: this.files[0]
        });

        $('#upload-block').addClass('layui-hide');
        $('#upload-progress-block').removeClass('layui-hide');

        uploader.on('media_progress', function (info) {
            if (!isNaN(info.percent)) {
                var percent = Math.ceil(100 * info.percent);
                element.progress('upload-progress', percent + '%');
            }
        });

        uploader.on('media_upload', function (info) {

        });

        uploader.done().then(function (result) {
            $('input[name=file_id]').val(result.fileId);
            $('#vod-submit').removeAttr('disabled').removeClass('layui-btn-disabled');
            $.ajax({
                type: 'POST',
                url: $('#vod-form').attr('action'),
                data: {file_id: result.fileId}
            });
        });

    });

});