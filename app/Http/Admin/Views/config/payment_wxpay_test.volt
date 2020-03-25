<div class="kg-qrcode-block">

    {% if code_url %}

        <div id="qrcode">
            <img class="kg-qrcode" src="{{ code_url }}" alt="二维码图片">
        </div>

        <input type="hidden" name="trade_sn" value="{{ trade_sn }}">

        <div id="success-tips" class="kg-success-tips layui-hide">
            <span>支付成功</span>
        </div>

        <div id="error-tips" class="kg-error-tips layui-hide">
            <span>支付失败</span>
        </div>

    {% else %}

        <div class="kg-error-tips">
            <span>生成二维码失败</span>
        </div>

    {% endif %}

</div>

{% if code_url %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            var loopTime = 0;
            var tradeSn = $('input[name=trade_sn]').val();
            var interval = setInterval(function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/test/wxpay/status',
                    data: {trade_sn: tradeSn},
                    success: function (res) {
                        if (res.status === 'finished') {
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
                        data: {trade_sn: tradeSn},
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