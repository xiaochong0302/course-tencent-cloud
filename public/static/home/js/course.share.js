layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var myShare = {
        title: $('input[name="share.title"]').val(),
        pic: $('input[name="share.pic"]').val(),
        url: $('input[name="share.url"]').val(),
        qrcode: $('input[name="share.qrcode"]').val()
    };

    $('.share-wechat').on('click', function () {
        helper.wechatShare(myShare.qrcode);
    });

    $('.share-qq').on('click', function () {
        var title = '推荐一门好课：' + myShare.title + '，快来和我一起学习吧！';
        helper.qqShare(title, myShare.url, myShare.pic);
    });

    $('.share-weibo').on('click', function () {
        var title = '推荐一门好课：' + myShare.title + '，快来和我一起学习吧！';
        helper.weiboShare(title, myShare.url, myShare.pic);
    });

});