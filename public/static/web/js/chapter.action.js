layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    var myShare = {
        title: $('input[name="share.title"]').val(),
        pic: $('input[name="share.pic"]').val(),
        url: $('input[name="share.url"]').val(),
        qrcode: $('input[name="share.qrcode"]').val()
    };

    $('.icon-praise').on('click', function () {
        var $this = $(this);
        var $likeCount = $this.next();
        var likeCount = parseInt($likeCount.text());
        $.ajax({
            type: 'POST',
            url: $this.data('url'),
            beforeSend: function () {
                return helper.checkLogin();
            },
            success: function () {
                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                    $likeCount.text(likeCount - 1);
                    likeCount -= 1;
                } else {
                    $this.addClass('active');
                    $likeCount.text(likeCount + 1);
                    likeCount += 1;
                }
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
            }
        });
    });

    $('.icon-wechat').on('click', function () {
        var content = '<div class="qrcode"><img src="' + myShare.qrcode + '" alt="分享到微信"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    });

    $('.icon-qq').on('click', function () {
        var title = '推荐一门好课：' + myShare.title + '，快来和我一起学习吧！';
        Share.qq(title, myShare.url, myShare.pic);
    });

    $('.icon-weibo').on('click', function () {
        var title = '推荐一门好课：' + myShare.title + '，快来和我一起学习吧！';
        Share.weibo(title, myShare.url, myShare.pic);
    });

    $('.icon-danmu-set').on('click', function () {
        layer.open({
            type: 1,
            title: '弹幕设置',
            area: '600px',
            shadeClose: true,
            content: $('#my-danmu-set')
        });
    });

});