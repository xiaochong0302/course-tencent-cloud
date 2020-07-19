layui.use(['jquery', 'rate'], function () {

    var $ = layui.jquery;
    var rate = layui.rate;

    $('.btn-cancel').on('click', function () {
        parent.layer.closeAll();
    });

    rate.render({
        elem: '#rating1',
        value: 5,
        choose: function (value) {
            $('input[name=rating1]').val(value);
        }
    });

    rate.render({
        elem: '#rating2',
        value: 5,
        choose: function (value) {
            $('input[name=rating2]').val(value);
        }
    });

    rate.render({
        elem: '#rating3',
        value: 5,
        choose: function (value) {
            $('input[name=rating3]').val(value);
        }
    });

});