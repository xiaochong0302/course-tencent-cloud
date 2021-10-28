{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.student.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索学员</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">所属课程</label>
            <div class="layui-input-block">
                <div id="xm-course-id"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户账号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="xm_user_id" placeholder="用户编号 / 手机号码 / 邮箱地址">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">加入方式</label>
            <div class="layui-input-block">
                {% for value,title in source_types %}
                    <input type="radio" name="source_type" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        xmSelect.render({
            el: '#xm-course-id',
            name: 'xm_course_id',
            radio: true,
            filterable: true,
            filterMethod: function (val, item, index, prop) {
                return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
            },
            data: {{ xm_courses|json_encode }}
        });

    </script>

{% endblock %}