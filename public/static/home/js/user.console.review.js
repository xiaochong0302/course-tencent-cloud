layui.use(['jquery', 'rate'], function () {

    var $ = layui.jquery;
    var rate = layui.rate;

    var $rating1 = $('input[name=rating1]');
    var $rating2 = $('input[name=rating2]');
    var $rating3 = $('input[name=rating3]');

    rate.render({
        elem: '#rating1',
        value: $rating1.val(),
        choose: function (value) {
            $rating1.val(value);
        }
    });

    rate.render({
        elem: '#rating2',
        value: $rating2.val(),
        choose: function (value) {
            $rating2.val(value);
        }
    });

    rate.render({
        elem: '#rating3',
        value: $rating3.val(),
        choose: function (value) {
            $rating3.val(value);
        }
    });

});
