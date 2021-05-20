{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form report-form" method="POST" action="{{ url({'for':'home.report.create'}) }}">
        <div class="layui-form-item">
            <div class="layui-form-label">举报理由</div>
            <div class="layui-input-block">
                <ul class="reason-list">
                    {% for value,title in reasons %}
                        <li><input type="radio" name="reason" value="{{ value }}" title="{{ title }}" lay-filter="reason"></li>
                    {% endfor %}
                </ul>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-form-label">补充说明</div>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="remark"></textarea>
            </div>
        </div>
        <div class="layui-form-item center">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="report">确定举报</button>
                <input type="hidden" name="item_id" value="{{ request.getQuery('item_id') }}">
                <input type="hidden" name="item_type" value="{{ request.getQuery('item_type') }}">
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/report.js') }}

{% endblock %}