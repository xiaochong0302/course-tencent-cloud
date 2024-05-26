layui.use(['jquery', 'element', 'layer'], function () {

    var $ = layui.jquery;
    var element = layui.element;
    var layer = layui.layer;

    var $uploadBtn = $('#res-upload-btn');
    var $resFile = $('input[name=res_file]');
    var $uploadBlock = $('#res-upload-block');
    var $progressBlock = $('#res-progress-block');
    var courseId = $('input[name=course_id]').val();

    var myConfig = {
        bucket: $('input[name=bucket]').val(),
        region: $('input[name=region]').val(),
        storageClass: 'STANDARD'
    };

    var cos = new COS({
        getAuthorization: function (options, callback) {
            $.post('/admin/upload/credentials', {
                bucket: options.Bucket,
                region: options.Region,
            }, function (data) {
                var credentials = data && data.credentials;
                if (!data || !credentials) {
                    layer.msg('获取临时凭证失败', {icon: 2});
                    return console.error('invalid credentials');
                }
                callback({
                    TmpSecretId: credentials.TmpSecretId,
                    TmpSecretKey: credentials.TmpSecretKey,
                    XCosSecurityToken: credentials.Token,
                    ExpiredTime: data.expiredTime,
                    StartTime: data.startTime
                });
            });
        }
    });

    loadResourceList();

    $uploadBtn.on('click', function () {
        $resFile.trigger('click');
    });

    $resFile.on('change', function (e) {
        var file = this.files[0];
        var keyName = getKeyName(file.name);
        cos.putObject({
            ContentDisposition: 'attachment',
            StorageClass: myConfig.storageClass,
            Bucket: myConfig.bucket,
            Region: myConfig.region,
            Key: keyName,
            Body: file,
            onProgress: function (info) {
                if (!isNaN(info.percent)) {
                    var percent = Math.ceil(100 * info.percent);
                    element.progress('res-upload-progress', percent + '%');
                }
            }
        }, function (err, data) {
            if (data && data.statusCode === 200) {
                $.post('/admin/resource/create', {
                    upload: {
                        name: file.name,
                        mime: file.type,
                        size: file.size,
                        path: keyName,
                        md5: data.ETag ? data.ETag.replace(/"/g, '') : ''
                    },
                    course_id: courseId,
                }, function () {
                    $uploadBlock.removeClass('layui-hide');
                    $progressBlock.addClass('layui-hide');
                    loadResourceList();
                });
            }
            console.log(err || data);
        });
        $uploadBlock.addClass('layui-hide');
        $progressBlock.removeClass('layui-hide');
    });

    $('body').on('change', '.res-name', function () {
        var url = $(this).data('url');
        $.post(url, {
            name: $(this).val()
        }, function (res) {
            layer.msg(res.msg, {icon: 1});
        });
    });

    $('body').on('click', '.res-btn-delete', function () {
        var url = $(this).data('url');
        layer.confirm('确定要删除吗？', function () {
            $.post(url, function (res) {
                layer.msg(res.msg, {icon: 1});
                loadResourceList();
            });
        });
    });

    function getKeyName(filename) {
        var ext = getFileExtension(filename);
        var date = new Date();
        var name = [
            date.getDate(),
            date.getHours(),
            date.getMinutes(),
            date.getSeconds(),
            Math.round(10000 * Math.random())
        ].join('');
        return '/resource/' + name + '.' + ext;
    }

    function getFileExtension(filename) {
        var index = filename.lastIndexOf('.');
        if (index === -1) return '';
        return filename.slice(index + 1);
    }

    function loadResourceList() {
        var url = $('#res-list').data('url');
        $.get(url, function (html) {
            $('#res-list').html(html);
        });
    }

});