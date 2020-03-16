/**
 * 挑选课程组件
 * @param data array 默认数据
 * @param url string 请求地址
 */
function xmCourse(data, url) {

    layui.use(['jquery', 'table'], function () {

        var $ = layui.jquery;
        var table = layui.table;

        var xmCourse = xmSelect.render({
            el: '#xm-course-ids',
            name: 'xm_course_ids',
            height: 'auto',
            autoRow: true,
            prop: {
                name: 'title',
                value: 'id',
            },
            data: data,
            content: `
            <div class="kg-search-box">
                <div class="layui-inline">
                    <input class="layui-input" type="text" placeholder="请输入课程标题..." id="search-keyword">
                </div>
                <div class="layui-inline">
                    <button type="button" class="layui-btn" id="search-btn">搜索</button>
                </div>
            </div>
            <table class="layui-hide" id="course-table" lay-filter="course"></table>`
        });

        table.render({
            id: 'course-table',
            elem: '#course-table',
            url: url,
            page: true,
            cols: [[
                {field: 'id', title: '编号', width: 40},
                {field: 'title', title: '标题', width: 340},
                {
                    field: 'model', title: '类型', width: 40, templet: function (d) {
                        if (d.model === 1) {
                            return '点播';
                        } else if (d.model === 2) {
                            return '直播';
                        } else if (d.model === 3) {
                            return '图文';
                        }
                    }
                },
                {
                    field: 'level', title: '难度', width: 40, templet: function (d) {
                        if (d.level === 1) {
                            return '入门';
                        } else if (d.level === 2) {
                            return '初级';
                        } else if (d.level === 3) {
                            return '中级';
                        } else if (d.level === 4) {
                            return '高级';
                        }
                    }
                },
                {
                    field: 'user_count', title: '用户', width: 40, templet: function (d) {
                        return d.user_count;
                    }
                },
                {
                    field: 'market_price', title: '价格', width: 40, templet: function (d) {
                        return '￥' + d.market_price;
                    }
                }
            ]]
        });

        table.on('rowDouble(course)', function (obj) {
            var item = obj.data;
            var values = xmCourse.getValue();
            var has = values.find(function (i) {
                return i.id === item.id;
            });
            if (!has) {
                xmCourse.append([item]);
            }
        });

        $('#search-btn').on('click', function () {
            table.reload('course-table', {
                where: {title: $('#search-keyword').val()},
                page: {curr: 1}
            });
        });

    });

}
