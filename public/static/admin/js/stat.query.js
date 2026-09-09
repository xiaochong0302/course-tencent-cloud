layui.use(['form', 'layer'], function () {

    var layer = layui.layer;
    var form = layui.form;

    form.on('submit(query)', function (data) {

        var year = parseInt(data.field.year);
        var month = parseInt(data.field.month);

        if (isOverCurrentTime(year, month)) {
            layer.msg('超过当前时间，无法查询！');
            return false;
        }
    });

    function isOverCurrentTime(year, month) {

        var now = new Date();
        var currentYear = now.getFullYear();
        var currentMonth = now.getMonth() + 1;

        if (year > currentYear) {
            return true;
        } else if (year === currentYear && month > currentMonth) {
            return true;
        }

        return false;
    }

});
