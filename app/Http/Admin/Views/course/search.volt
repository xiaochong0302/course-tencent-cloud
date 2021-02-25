{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.course.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索课程</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" placeholder="标题模糊匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分类</label>
            <div class="layui-input-block">
                <div id="xm-category-ids"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">讲师</label>
            <div class="layui-input-block">
                <div id="xm-teacher-ids"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">模型</label>
            <div class="layui-input-block">
                {% for value,title in model_types %}
                    <input type="radio" name="model" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">难度</label>
            <div class="layui-input-block">
                {% for value,title in level_types %}
                    <input type="radio" name="level" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">免费</label>
            <div class="layui-input-block">
                <input type="radio" name="free" value="1" title="是">
                <input type="radio" name="free" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推荐</label>
            <div class="layui-input-block">
                <input type="radio" name="featured" value="1" title="是">
                <input type="radio" name="featured" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是">
                <input type="radio" name="published" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">删除</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="是">
                <input type="radio" name="deleted" value="0" title="否">
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
            el: '#xm-category-ids',
            name: 'xm_category_ids',
            max: 5,
            prop: {
                name: 'name',
                value: 'id'
            },
            data: {{ xm_categories|json_encode }}
        });

        xmSelect.render({
            el: '#xm-teacher-ids',
            name: 'xm_teacher_ids',
            max: 5,
            prop: {
                name: 'name',
                value: 'id'
            },
            data: {{ xm_teachers|json_encode }}
        });

    </script>

{% endblock %}