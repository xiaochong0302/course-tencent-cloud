{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set add_url = url({'for':'admin.article.add'}) %}
    {% set search_url = url({'for':'admin.article.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>文章管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加文章
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索文章
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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>文章</th>
            <th>浏览</th>
            <th>评论</th>
            <th>点赞</th>
            <th>收藏</th>
            <th>状态</th>
            <th>推荐</th>
            <th>关闭</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set article_url = url({'for':'home.article.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set edit_url = url({'for':'admin.article.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.article.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.article.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.article.restore','id':item.id}) %}
            {% set moderate_url = url({'for':'admin.article.moderate','id':item.id}) %}
            {% set comment_url = url({'for':'admin.comment.list'},{'item_id':item.id,'item_type':2}) %}
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
                        <span>来源：{{ source_type(item.source_type) }}</span>
                        <span>作者：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></span>
                        <span>创建：{{ date('Y-m-d',item.create_time) }}</span>
                    </p>
                </td>
                <td>{{ item.view_count }}</td>
                <td>{{ item.comment_count }}</td>
                <td>{{ item.like_count }}</td>
                <td>{{ item.favorite_count }}</td>
                <td>{{ publish_status(item.published) }}</td>
                <td><input type="checkbox" name="featured" value="1" lay-skin="switch" lay-text="是|否" lay-filter="featured" data-url="{{ update_url }}"
                           {% if item.featured == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="comment" value="1" lay-skin="switch" lay-text="是|否" lay-filter="closed" data-url="{{ update_url }}"
                           {% if item.closed == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ moderate_url }}">审核文章</a></li>
                            {% elseif item.published == 2 %}
                                <li><a href="{{ article_url }}" target="_blank">浏览文章</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑文章</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除文章</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原文章</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="javascript:" class="kg-comment" data-url="{{ comment_url }}">评论管理</a></li>
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
                layer.confirm(tips, {
                    cancel: function (index) {
                        layer.close(index);
                        data.elem.checked = !checked;
                        form.render();
                    }
                }, function () {
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

            form.on('switch(closed)', function (data) {
                var checked = $(this).is(':checked');
                var closed = checked ? 1 : 0;
                var url = $(this).data('url');
                var tips = closed === 1 ? '确定要关闭评论？' : '确定要开启评论？';
                layer.confirm(tips, function () {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {closed: closed},
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

            $('.kg-comment').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '评论管理',
                    area: ['1000px', '600px'],
                    content: url
                });
            });

        });

    </script>

{% endblock %}