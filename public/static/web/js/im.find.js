layui.use(['jquery', 'form', 'layer', 'layim'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var layim = layui.layim;

    form.on('submit(im_search)', function (data) {
        var usersUrl = '/im/search?limit=12&target=tab-users&type=user&query=' + data.field.query;
        var groupsUrl = '/im/search?limit=12&target=tab-groups&type=group&query=' + data.field.query;
        layui.ajaxLoadHtml(usersUrl, 'tab-users');
        layui.ajaxLoadHtml(groupsUrl, 'tab-groups');
        return false;
    });

    $('body').on('click', '.apply-friend', function () {
        var friendId = $(this).attr('data-id');
        var username = $(this).attr('data-name');
        var avatar = $(this).attr('data-avatar');
        layim.add({
            type: 'friend',
            username: username,
            avatar: avatar,
            submit: function (groupId, remark, index) {
                $.ajax({
                    type: 'POST',
                    url: '/im/friend/apply',
                    data: {
                        friend_id: friendId,
                        group_id: groupId,
                        remark: remark
                    },
                    success: function (res) {
                        layer.msg(res.msg, {icon: 1});
                        layer.close(index);
                    },
                    error: function (xhr) {
                        var res = JSON.parse(xhr.responseText);
                        layer.msg(res.msg, {icon: 2});
                    }
                });
            }
        });
    });

    $('body').on('click', '.apply-group', function () {
        var groupId = $(this).attr('data-id');
        var groupName = $(this).attr('data-name');
        var avatar = $(this).attr('data-avatar');
        layim.add({
            type: 'group',
            groupname: groupName,
            avatar: avatar,
            submit: function (group, remark, index) {
                $.ajax({
                    type: 'POST',
                    url: '/im/group/apply',
                    data: {
                        group_id: groupId,
                        remark: remark
                    },
                    success: function (res) {
                        layer.msg(res.msg, {icon: 1});
                        layer.close(index);
                    },
                    error: function (xhr) {
                        var res = JSON.parse(xhr.responseText);
                        layer.msg(res.msg, {icon: 2});
                    }
                });
            }
        });
    });

});