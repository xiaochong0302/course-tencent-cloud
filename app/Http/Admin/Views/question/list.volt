{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/question') }}

    {% set add_url = url({'for':'admin.question.add'}) %}
    {% set search_url = url({'for':'admin.question.search'}) %}
    {% set category_url = url({'for':'admin.question.category'}) %}
    {% set batch_delete_url = url({'for':'admin.question.batch_delete'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>问题管理</cite></a>
            </span>
            <span class="layui-btn layui-btn-sm layui-bg-red kg-batch" data-url="{{ batch_delete_url }}">批量删除</span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ category_url }}">
                <i class="layui-icon layui-icon-add-1"></i>问题分类
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加问题
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索问题
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
            <th>问题信息</th>
            <th>统计信息</th>
            <th>状态</th>
            <th>推荐</th>
            <th>关闭</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set question_url = url({'for':'home.question.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set edit_url = url({'for':'admin.question.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.question.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.question.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.question.restore','id':item.id}) %}
            {% set moderate_url = url({'for':'admin.question.moderate','id':item.id}) %}
            {% set answer_add_url = url({'for':'admin.answer.add'},{'question_id':item.id}) %}
            {% set answer_list_url = url({'for':'admin.answer.list'},{'question_id':item.id}) %}
            <tr>
                <td><input class="item" type="checkbox" value="{{ item.id }}" lay-filter="item"></td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}">{{ item.owner.name }}</a></p>
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
                        <span>回答：{{ item.answer_count }}</span>
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
                                <li><a href="{{ moderate_url }}">审核问题</a></li>
                            {% elseif item.published == 2 %}
                                <li><a href="{{ question_url }}" target="_blank">浏览问题</a></li>
                                <li><a href="{{ answer_add_url }}">回答问题</a></li>
                            {% endif %}
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

        layui.define(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

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