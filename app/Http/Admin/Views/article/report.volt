{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set owner_url = url({'for':'home.user.show','id':article.owner.id}) %}
    {% set report_url = url({'for':'admin.article.report','id':article.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核举报</legend>
    </fieldset>

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">文章信息</li>
            <li>举报信息</li>
            <li>审核意见</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="kg-mod-preview">
                    <div class="title">{{ article.title }}</div>
                    <div class="meta">
                        <span class="layui-badge layui-bg-green">{{ source_type(article.source_type) }}</span>
                        <span><a href="{{ owner_url }}" target="_blank">{{ article.owner.name }}</a></span>
                        <span>{{ date('Y-m-d H:i:s',article.create_time) }}</span>
                    </div>
                    <div class="content ke-content kg-zoom">{{ article.content }}</div>
                    {% if article.tags %}
                        <div class="tags">
                            {% for item in article.tags %}
                                <span class="layui-btn layui-btn-xs">{{ item.name }}</span>
                            {% endfor %}
                        </div>
                    {% endif %}
                    {% if article.source_url %}
                        <div class="source-tips kg-center">
                            <a href="{{ article.source_url }}" target="_blank">查看原文</a>
                        </div>
                    {% endif %}
                </div>
            </div>
            <div class="layui-tab-item">
                <table class="layui-table kg-table">
                    <colgroup>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th>举报用户</th>
                        <th>举报理由</th>
                        <th>举报时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for item in reports %}
                        {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
                        <tr>
                            <td><a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></td>
                            <td>{{ item.reason }}</td>
                            <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
            <div class="layui-tab-item">
                <form class="layui-form kg-form kg-mod-form" method="POST" action="{{ report_url }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">有效举报</label>
                        <div class="layui-input-block">
                            <input type="radio" name="accepted" value="1" title="是" lay-filter="accepted">
                            <input type="radio" name="accepted" value="0" title="否" lay-filter="accepted">
                        </div>
                    </div>
                    <div class="layui-form-item" id="delete-block" style="display:none;">
                        <label class="layui-form-label">删除问题</label>
                        <div class="layui-input-block">
                            <input type="radio" name="deleted" value="1" title="是" lay-filter="deleted">
                            <input type="radio" name="deleted" value="0" title="否" lay-filter="deleted">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(accepted)', function (data) {
                var $block = $('#delete-block');
                if (data.value === '1') {
                    $block.show();
                } else {
                    $block.hide();
                }
            });

        });

    </script>

{% endblock %}