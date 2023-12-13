{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/consult') }}

    {% set search_url = url({'for':'admin.consult.search'}) %}
    {% set batch_delete_url = url({'for':'admin.consult.batch_delete'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                {% if request.get('course_id') > 0 %}
                    <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                {% endif %}
                <a><cite>咨询管理</cite></a>
            </span>
            <span class="layui-btn layui-btn-sm layui-bg-red kg-batch" data-url="{{ batch_delete_url }}">批量删除</span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索咨询
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
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th><input class="all" type="checkbox" lay-filter="all"></th>
            <th>用户</th>
            <th>内容</th>
            <th>时间</th>
            <th>状态</th>
            <th>私密</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set item.answer = item.answer ? item.answer : 'N/A' %}
            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set moderate_url = url({'for':'admin.consult.moderate','id':item.id}) %}
            {% set edit_url = url({'for':'admin.consult.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.consult.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.consult.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.consult.restore','id':item.id}) %}
            <tr>
                <td><input class="item" type="checkbox" value="{{ item.id }}" lay-filter="item"></td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>课程：<a href="{{ course_url }}">{{ item.course.title }}</a>（{{ item.course.id }}）</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.question }}">提问：{{ item.question }}</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.answer }}">回复：{{ item.answer }}</p>
                </td>
                <td>
                    <p>提问：{{ date('Y-m-d',item.create_time) }}</p>
                    {% if item.reply_time > 0 %}
                        <p>回复：{{ date('Y-m-d',item.reply_time) }}</p>
                    {% else %}
                        <p>回复：N/A</p>
                    {% endif %}
                </td>
                <td>{{ publish_status(item.published) }}</td>
                <td><input type="checkbox" name="private" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.private == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ moderate_url }}">审核</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}