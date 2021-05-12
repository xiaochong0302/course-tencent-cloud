layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var myShare = {
        title: $('input[name="share.title"]').val(),
        pic: $('input[name="share.pic"]').val(),
        url: $('input[name="share.url"]').val(),
        qrcode: $('input[name="share.qrcode"]').val()
    };

    $('.icon-wechat').on('click', function () {
        helper.wechatShare(myShare.qrcode);
    });

    $('.icon-qq').on('click', function () {
        var title = '分享一个好问题：' + myShare.title + '，快来和我一起学习吧！';
        helper.qqShare(title, myShare.url, myShare.pic);
    });

    $('.icon-weibo').on('click', function () {
        var title = '分享一个好问题：' + myShare.title + '，快来和我一起学习吧！';
        helper.weiboShare(title, myShare.url, myShare.pic);
    });

});