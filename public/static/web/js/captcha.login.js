layui.use(['jquery'], function () {
    var $ = layui.jquery;
    var captcha = new TencentCaptcha(
        $('#captcha-btn')[0],
        $('#captcha-btn').attr('data-app-id'),
        function (res) {
            if (res.ret === 0) {
                $('#ticket').val(res.ticket);
                $('#rand').val(res.randstr);
                $('#captcha-block').hide();
                $('#submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
            }
        }
    );
});