{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set add_url = url({'for':'admin.article.add'}) %}
    {% set search_url = url({'for':'admin.article.search'}) %}
    {% set category_url = url({'for':'admin.article.category'}) %}
    {% set batch_delete_url = url({'for':'admin.article.batch_delete'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>文章管理</cite></a>
            </span>
            <span class="layui-btn layui-btn-sm layui-bg-red kg-batch" data-url="{{ batch_delete_url }}">批量删除</span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ category_url }}">
                <i class="layui-icon layui-icon-add-1"></i>文章分类
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加文章
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索文章
            </a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="5%">
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
            <th><input class="all" type="checkbox" lay-filter="all"></th>
            <th>作者信息</th>
            <th>文章信息</th>
            <th>统计信息</th>
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
                <td><input class="item" type="checkbox" value="{{ item.id }}" lay-filter="item"></td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>标题：<a href="{{ edit_url }}">{{ item.title }}</a>（{{ item.id }}）</p>
                    <p class="meta">
                        {% if item.category.id is defined %}
                            <span>分类：{{ item.category.name }}</span>
                        {% endif %}
                        <span>创建：{{ date('Y-m-d',item.create_time) }}</span>
                    </p>
                </td>
                <td>
                    <p class="meta">
                        <span>浏览：{{ item.view_count }}</span>
                        <span>评论：{{ item.comment_count }}</span>
                    </p>
                    <p class="meta">
                        <span>点赞：{{ item.like_count }}</span>
                        <span>收藏：{{ item.favorite_count }}</span>
                    </p>
                </td>
                <td>{{ publish_status(item.published) }}</td>
                <td><input type="checkbox" name="featured" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.featured == 1 %}checked="checked"{% endif %}></td>
                <td><input type="checkbox" name="closed" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
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

        layui.define(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

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