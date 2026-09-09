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

    /**
     * 腾讯云点播web端上传
     * @doc https://cloud.tencent.com/document/product/266/9239
     */
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

        uploader.done().then(function (result) {
            $('input[name=file_id]').val(result.fileId);
            $('#trans-submit').removeAttr('disabled').removeClass('layui-btn-disabled');
            $('#trans-block').show();
            $.ajax({
                type: 'POST',
                url: $('#cos-upload-form').attr('action'),
                data: {file_id: result.fileId}
            });
        });

    });

});
