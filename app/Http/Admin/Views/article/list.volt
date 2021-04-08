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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>文章</th>
            <th>来源</th>
            <th>作者</th>
            <th>统计</th>
            <th>推荐</th>
            <th>评论</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set preview_url = url({'for':'home.article.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.article.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.article.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.article.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.article.restore','id':item.id}) %}
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
                    <p>创建：{{ date('Y-m-d H:i',item.create_time) }}，更新：{{ date('Y-m-d H:i',item.update_time) }}</p>
                </td>
                <td>{{ source_info(item.source_type,item.source_url) }}</td>
                <td>
                    <p>昵称：{{ item.owner.name }}</p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>浏览：{{ item.view_count }}，评论：{{ item.comment_count }}</p>
                    <p>点赞：{{ item.like_count }}，收藏：{{ item.favorite_count }}</p>
                </td>
                <td><input type="checkbox" name="featured" value="1" lay-skin="switch" lay-text="是|否" lay-filter="featured" data-url="{{ update_url }}" {% if item.featured == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="comment" value="1" lay-skin="switch" lay-text="开|关" lay-filter="comment" data-url="{{ update_url }}" {% if item.allow_comment == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ preview_url }}" target="_blank">预览文章</a></li>
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

            form.on('switch(comment)', function (data) {
                var checked = $(this).is(':checked');
                var allowComment = checked ? 1 : 0;
                var url = $(this).data('url');
                var tips = allowComment === 1 ? '确定要开启评论？' : '确定要关闭评论？';
                layer.confirm(tips, function () {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {allow_comment: allowComment},
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