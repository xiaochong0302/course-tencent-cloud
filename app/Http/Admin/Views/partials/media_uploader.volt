{{ js_include('lib/vod-js-sdk-v6.min.js') }}

<script>

    layui.use(['jquery', 'element'], function () {

        var $ = layui.jquery;
        var element = layui.element;

        var getSignature = function () {
            var result = '';
            $.ajax({
                type: 'POST',
                url: '/admin/vod/upload/signature',
                async: false,
                success: function (res) {
                    result = res.signature;
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
                    var percent = parseInt(100 * info.percent);
                    element.progress('upload-progress', percent + '%');
                }
            });

            uploader.on('media_upload', function (info) {

            });

            uploader.done().then(function (result) {
                $('input[name=file_id]').val(result.fileId);
                var chapterId = $('input[name=chapter_id]').val();
                $.ajax({
                    type: 'POST',
                    url: '/admin/chapter/' + chapterId + '/content',
                    data: {file_id: result.fileId}
                });
            });

        });

    });

</script>