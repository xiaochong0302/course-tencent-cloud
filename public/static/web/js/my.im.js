layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.btn-delete-friend').on('click', function () {
        var url = $(this).data('url');
        var friendId = $(this).data('friendId');
        layer.confirm('确定要删除好友吗？', function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: {friend_id: friendId},
                success: function (res) {

                }
            });
        });
    });

    $('.btn-delete-group').on('click', function () {
        var url = $(this).data('url');
        var groupId = $(this).data('groupId');
        layer.confirm('确定要退出群组吗？', function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: {group_id: groupId},
                success: function (res) {

                }
            });
        });
    });

});