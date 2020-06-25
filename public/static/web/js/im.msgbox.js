layui.use(['jquery', 'layer', 'layim', 'laypage'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var layim = layui.layim;
    var laypage = layui.laypage;

    var $target = $('#LAY_view');
    var $page = $('#LAY_page');
    var count = $page.data('count');
    var limit = 15;

    /**
     * 标记信息为已读
     */
    markMessageAsRead();

    /**
     * 加载第一页数据
     */
    loadPageHtml($target, 1);

    /**
     * 两页以上才显示分页
     */
    if (count > limit) {
        laypage.render({
            elem: $page.attr('id'),
            limit: limit,
            count: count,
            jump: function (obj, first) {
                if (!first) {
                    loadPageHtml($target, obj.curr);
                }
            }
        });
    }

    function loadPageHtml(target, page) {
        $.get('/im/msg/sys', {page: page}, function (html) {
            target.html(html);
        });
    }

    function markMessageAsRead() {
        $.post('/im/msg/read');
    }

    //操作
    var action = {
        acceptFriend: function (othis) {
            var li = othis.parents('li');
            var sender = li.find('.layim-msgbox-user');
            //选择分组
            parent.layui.layim.setFriendGroup({
                type: 'friend',
                username: sender.data('name'),
                avatar: sender.data('avatar'),
                group: parent.layui.layim.cache().friend,
                submit: function (group, index) {
                    $.ajax({
                        type: 'POST',
                        url: '/im/friend/accept',
                        data: {
                            message_id: li.data('id'),
                            group_id: group
                        },
                        success: function () {
                            //将好友追加到主面板
                            parent.layui.layim.addList({
                                type: 'friend',
                                username: sender.data('name'),
                                avatar: sender.data('avatar'),
                                id: sender.data('id'),
                                groupid: group,
                            });
                            othis.parent().html('已同意');
                            parent.layer.close(index);
                        }
                    });
                }
            });
        },
        refuseFriend: function (othis) {
            var li = othis.parents('li');
            layer.confirm('确定拒绝吗？', function (index) {
                $.ajax({
                    type: 'POST',
                    url: '/im/friend/refuse',
                    data: {message_id: li.data('id')},
                    success: function () {
                        layer.close(index);
                        othis.parent().html('<em>已拒绝</em>');
                    }
                });
            });
        },
        acceptGroup: function (othis) {
        },
        refuseGroup: function (othis) {
        }
    };

    $('body').on('click', '.layui-btn', function () {
        var othis = $(this), type = othis.data('type');
        action[type] ? action[type].call(this, othis) : '';
    });

});