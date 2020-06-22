layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.apply-friend').on('click', function () {
        var friendId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '/im/friend/apply',
            data: {friend_id: friendId},
            success: function (res) {
                layer.msg(res.msg, {icon: 1});
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
            }
        });
    });

    $('.apply-group').on('click', function () {
        var groupId = $(this).attr('data-id');
        $.ajax({
            type: 'POST',
            url: '/im/group/apply',
            data: {group_id: groupId},
            success: function (res) {
                layer.msg(res.msg, {icon: 1});
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
            }
        });
    });

});