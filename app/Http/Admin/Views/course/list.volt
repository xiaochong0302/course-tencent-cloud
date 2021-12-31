{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set category_url = url({'for':'admin.course.category'}) %}
    {% set add_url = url({'for':'admin.course.add'}) %}
    {% set search_url = url({'for':'admin.course.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>课程管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ category_url }}">
                <i class="layui-icon layui-icon-add-1"></i>分类管理
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加课程
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索课程
            </a>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>课程</th>
            <th>课时</th>
            <th>课件</th>
            <th>学员</th>
            <th>收藏</th>
            <th>咨询</th>
            <th>评价</th>
            <th>价格</th>
            <th>推荐</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.course.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.course.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.course.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.course.restore','id':item.id}) %}
            {% set catalog_url = url({'for':'admin.course.chapters','id':item.id}) %}
            {% set student_url = url({'for':'admin.student.list'},{'course_id':item.id}) %}
            {% set review_url = url({'for':'admin.review.list'},{'course_id':item.id}) %}
            {% set consult_url = url({'for':'admin.consult.list'},{'course_id':item.id}) %}
            <tr>
                <td>
                    <p>标题：<a href="{{ catalog_url }}">{{ item.title }}</a>（{{ item.id }}）</p>
                    <p class="meta">
                        {% if item.category.id is defined %}
                            <span>分类：{{ item.category.name }}</span>
                        {% endif %}
                        {% if item.teacher.id is defined %}
                            <span>讲师：{{ item.teacher.name }}</span>
                        {% endif %}
                        <span>难度：{{ level_info(item.level) }}</span>
                    </p>
                    <p class="meta">
                        <span>类型：{{ model_info(item.model) }}</span>
                        <span>评分：{{ item.rating }}</span>
                        <span>创建：{{ date('Y-m-d',item.create_time) }}</span>
                    </p>
                </td>
                <td>{{ item.lesson_count }}</td>
                <td>{{ item.resource_count }}</td>
                <td>{{ item.user_count }}</td>
                <td>{{ item.favorite_count }}</td>
                <td>{{ item.consult_count }}</td>
                <td>{{ item.review_count }}</td>
                <td>
                    <p>原始：{{ '￥%0.2f'|format(item.origin_price) }}</p>
                    <p>市场：{{ '￥%0.2f'|format(item.market_price) }}</p>
                    <p>会员：{{ '￥%0.2f'|format(item.vip_price) }}</p>
                </td>
                <td><input type="checkbox" name="featured" value="1" lay-skin="switch" lay-text="是|否" lay-filter="featured" data-url="{{ update_url }}" {% if item.featured == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ course_url }}" target="_blank">浏览课程</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑课程</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除课程</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原课程</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ catalog_url }}">章节管理</a></li>
                            <li><a href="{{ student_url }}">学员管理</a></li>
                            <hr>
                            <li><a href="{{ consult_url }}">咨询管理</a></li>
                            <li><a href="{{ review_url }}">评价管理</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.define(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;

            form.on('switch(featured)', function (data) {
                var checked = $(this).is(':checked');
                var featured = checked ? 1 : 0;
                var url = $(this).data('url');
                var tips = featured === 1 ? '确定要推荐？' : '确定要取消推荐？';
                layer.confirm(tips, function () {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {featured: featured},
                        success: function (res) {
                            layer.msg(res.msg, {icon: 1});
                        },
                        error: function (xhr) {
                            var json = JSON.parse(xhr.responseText);
                            layer.msg(json.msg, {icon: 2});
                            data.elem.checked = !checked;
                            form.render();
                        }
                    });
                }, function () {
                    data.elem.checked = !checked;
                    form.render();
                });
            });
        });

    </script>

{% endblock %}