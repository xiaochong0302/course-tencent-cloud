<div class="kg-qrcode-block">

    {% if qrcode %}

        <div id="qrcode" class="qrcode" qrcode-text="{{ qrcode }}"></div>

        <input type="hidden" name="sn" value="{{ trade.sn }}">

        <div id="success-tips" class="success-tips layui-hide">
            <span><i class="layui-icon layui-icon-ok-circle"></i> 支付成功</span>
        </div>

        <div id="error-tips" class="error-tips layui-hide">
            <span><i class="layui-icon layui-icon-close-fill"></i> 支付失败</span>
        </div>

    {% else %}

        <div class="error-tips">
            <span><i class="layui-icon layui-icon-close-fill"></i> 生成二维码失败</span>
        </div>

    {% endif %}

</div>

{% if qrcode %}

    {{ javascript_include('lib/jquery.min.js') }}
    {{ javascript_include('lib/jquery.qrcode.min.js') }}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            $('#qrcode').qrcode({
                text: $('#qrcode').attr('qrcode-text'),
                width: 150,
                height: 150
            });

            var loopTime = 0;
            var sn = $('input[name=sn]').val();
            var interval = setInterval(function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/test/wxpay/status',
                    data: {sn: sn},
                    success: function (res) {
                        if (res.status == 'finished') {
                            $('#success-tips').removeClass('layui-hide');
                            $('#qrcode').addClass('layui-hide');
                            clearInterval(interval);
                        }
                    },
                    error: function (xhr) {
                        $('#error-tips').removeClass('layui-hide');
                        $('#qrcode').addClass('layui-hide');
                        clearInterval(interval);
                    }
                });
                loopTime += 5;
                if (loopTime >= 300) {
                    $.ajax({
                        type: 'POST',
                        url: '/admin/test/wxpay/cancel',
                        data: {sn: sn},
                        success: function (res) {
                        },
                        error: function (xhr) {
                        }
                    });
                    $('#error-tips').removeClass('layui-hide');
                    $('#qrcode').addClass('layui-hide');
                    clearInterval(interval);
                }
            }, 5000);

        });

    </script>

{% endif %}