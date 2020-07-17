layui.use(['jquery', 'form', 'layer', 'layim', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var layim = layui.layim;
    var helper = layui.helper;

    form.on('submit(im_search)', function (data) {
        var usersUrl = '/im/search?limit=12&target=tab-users&type=user&query=' + data.field.query;
        var groupsUrl = '/im/search?limit=12&target=tab-groups&type=group&query=' + data.field.query;
        helper.ajaxLoadHtml(usersUrl, 'tab-users');
        helper.ajaxLoadHtml(groupsUrl, 'tab-groups');
        return false;
    });

    $('body').on('click', '.apply-friend', function () {
        var friendId = $(this).data('id');
        var username = $(this).data('name');
        var avatar = $(this).data('avatar');
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
                    }
                });
            }
        });
    });

    $('body').on('click', '.apply-group', function () {
        var groupId = $(this).data('id');
        var groupName = $(this).data('name');
        var avatar = $(this).data('avatar');
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
                    }
                });
            }
        });
    });

});