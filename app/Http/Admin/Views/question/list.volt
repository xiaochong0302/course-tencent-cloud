{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/question') }}

    {% set add_url = url({'for':'admin.question.add'}) %}
    {% set search_url = url({'for':'admin.question.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>问题管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加问题
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索问题
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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>问题</th>
            <th>回答</th>
            <th>浏览</th>
            <th>点赞</th>
            <th>收藏</th>
            <th>状态</th>
            <th>关闭</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set preview_url = url({'for':'home.question.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set edit_url = url({'for':'admin.question.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.question.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.question.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.question.restore','id':item.id}) %}
            {% set review_url = url({'for':'admin.question.publish_review','id':item.id}) %}
            {% set answer_add_url = url({'for':'admin.answer.add'},{'question_id':item.id}) %}
            {% set answer_list_url = url({'for':'admin.answer.list'},{'question_id':item.id}) %}
            <tr>
                <td>
                    <p>标题：<a href="{{ edit_url }}">{{ item.title }}</a>（{{ item.id }}）</p>
                    <p class="meta">
                        {% if item.category.id is defined %}
                            <span>分类：{{ item.category.name }}</span>
                        {% endif %}
                        {% if item.tags %}
                            <span>标签：{{ tags_info(item.tags) }}</span>
                        {% endif %}
                    </p>
                    <p class="meta">
                        <span>作者：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></span>
                        <span>创建：{{ date('Y-m-d',item.create_time) }}</span>
                    </p>
                </td>
                <td>{{ item.answer_count }}</td>
                <td>{{ item.view_count }}</td>
                <td>{{ item.like_count }}</td>
                <td>{{ item.favorite_count }}</td>
                <td>{{ publish_status(item.published) }}</td>
                <td><input type="checkbox" name="closed" value="1" lay-skin="switch" lay-text="是|否" lay-filter="closed" data-url="{{ update_url }}" {% if item.closed == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ review_url }}">审核问题</a></li>
                            {% elseif item.published == 2 %}
                                <li><a href="{{ preview_url }}" target="_blank">预览问题</a></li>
                            {% endif %}
                            <li><a href="{{ answer_add_url }}">回答问题</a></li>
                            <li><a href="{{ edit_url }}">编辑问题</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除问题</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原问题</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ answer_list_url }}">回答管理</a></li>
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

            form.on('switch(closed)', function (data) {
                var checked = $(this).is(':checked');
                var closed = checked ? 1 : 0;
                var url = $(this).data('url');
                var tips = closed === 1 ? '确定要关闭讨论？' : '确定要开启讨论？';
                layer.confirm(tips, function () {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {closed: data.value},
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

            $('.kg-answer').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '回答管理',
                    area: ['1000px', '600px'],
                    content: url
                });
            });

        });

    </script>

{% endblock %}