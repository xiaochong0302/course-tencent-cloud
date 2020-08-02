layui.use(['jquery', 'layer', 'layim', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var layim = layui.layim;
    var helper = layui.helper;

    $('body').on('click', '.apply-friend', function () {
        var friendId = $(this).data('id');
        var username = $(this).data('name');
        var avatar = $(this).data('avatar');
        helper.checkLogin(function () {
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
    });

    $('body').on('click', '.apply-group', function () {
        var groupId = $(this).data('id');
        var groupName = $(this).data('name');
        var avatar = $(this).data('avatar');
        helper.checkLogin(function () {
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

});