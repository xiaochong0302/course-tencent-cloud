{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.article.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索文章</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">文章编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="文章编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">作者编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="作者编号精确匹配">
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
                <select name="category_id">
                    <option value="">请选择</option>
                    {% for item in categories %}
                        <option value="{{ item.id }}">{{ item.name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标签</label>
            <div class="layui-input-block">
                <div id="xm-tag-ids"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">来源类型</label>
            <div class="layui-input-block">
                {% for value,title in source_types %}
                    <input type="radio" name="source_type" value="{{ value }}" title="{{ title }}">
                {% endfor %}
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
            el: '#xm-tag-ids',
            name: 'xm_tag_ids',
            filterable: true,
            max: 5,
            data: {{ xm_tags|json_encode }}
        });

    </script>

{% endblock %}