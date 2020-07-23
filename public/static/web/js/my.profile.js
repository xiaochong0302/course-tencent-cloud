layui.use(['layarea'], function () {

    var layarea = layui.layarea;

    layarea.render({
        elem: '#area-picker',
        change: function (res) {
            console.log(res);
        }
    });

});