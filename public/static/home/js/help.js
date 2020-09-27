layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.btn-cs').on('click', function () {
        helper.cs();
    });

});