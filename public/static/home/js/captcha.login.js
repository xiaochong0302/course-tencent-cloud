layui.use(['jquery'], function () {

    var $ = layui.jquery;

    if ($('#cl-captcha-enabled').val() === '1') {
        var captcha = new TencentCaptcha(
            $('#cl-emit-btn')[0],
            $('#cl-captcha-appId').val(),
            function (res) {
                if (res.ret === 0) {
                    $('#cl-captcha-ticket').val(res.ticket);
                    $('#cl-captcha-rand').val(res.randstr);
                    $('#cl-submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
                    $('#captcha-block').hide();
                }
            }
        );
    }

});