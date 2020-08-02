layui.use(['form', 'helper'], function () {

    var form = layui.form;
    var helper = layui.helper;

    form.on('submit(im_search)', function (data) {
        var usersUrl = '/im/search?target=tab-users&type=user&query=' + data.field.query;
        var groupsUrl = '/im/search?target=tab-groups&type=group&query=' + data.field.query;
        helper.ajaxLoadHtml(usersUrl, 'tab-users');
        helper.ajaxLoadHtml(groupsUrl, 'tab-groups');
        return false;
    });

});