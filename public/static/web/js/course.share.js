layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    var myShare = {
        title: $('input[name="share.title"]').val(),
        pic: $('input[name="share.pic"]').val(),
        url: $('input[name="share.url"]').val(),
        qrcode: $('input[name="share.qrcode"]').val()
    };

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
        qqShare(title, myShare.url, myShare.pic);
    });

    $('.icon-weibo').on('click', function () {
        var title = '推荐一门好课：' + myShare.title + '，快来和我一起学习吧！';
        weiboShare(title, myShare.url, myShare.pic);
    });

    function qqShare(title, url, pic) {
        var shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?';
        shareUrl += 'title=' + encodeURIComponent(title || document.title);
        shareUrl += '&url=' + encodeURIComponent(url || document.location);
        shareUrl += '&pics=' + pic;
        window.open(shareUrl, '_blank');
    }

    function weiboShare(title, url, pic) {
        var shareUrl = 'http://v.t.sina.com.cn/share/share.php?';
        shareUrl += 'title=' + encodeURIComponent(title || document.title);
        shareUrl += '&url=' + encodeURIComponent(url || document.location);
        shareUrl += '&pic=' + encodeURIComponent(pic || '');
        window.open(shareUrl, '_blank');
    }

});