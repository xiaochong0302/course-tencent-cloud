{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro show_sales(sales) %}
        <table class="layui-table kg-table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>排序</th>
                <th>名称</th>
                <th>数量</th>
                <th>金额</th>
            </tr>
            </thead>
            <tbody>
            {% for sale in sales %}
                <tr>
                    <td>{{ loop.index }}</td>
                    <td>{{ sale.title }}</td>
                    <td>{{ sale.total_count }}</td>
                    <td>{{ '￥%0.2f'|format(sale.total_amount) }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    {%- endmacro %}

    {% set types = {'1':'课程','2':'套餐','3':'赞赏','4':'会员'} %}
    {% set year = request.get('year','int',date('Y')) %}
    {% set month = request.get('month','int',date('m')) %}
    {% set type = request.get('type','int',1) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>热卖商品统计</cite></a>
            </span>
        </div>
    </div>

    <form class="layui-form kg-search-form" method="GET" action="{{ url({'for':'admin.stat.hot_sales'}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">选择类型</label>
            <div class="layui-input-inline">
                <select name="type">
                    {% for key,value in types %}
                        <option value="{{ key }}" {% if key == type %}selected{% endif %}>{{ value }}</option>
                    {% endfor %}
                </select>
            </div>
            <label class="layui-form-label">选择年份</label>
            <div class="layui-input-inline">
                <select name="year">
                    {% for value in years %}
                        <option value="{{ value }}" {% if value == year %}selected{% endif %}>{{ value }}年</option>
                    {% endfor %}
                </select>
            </div>
            <label class="layui-form-label">选择月份</label>
            <div class="layui-input-inline">
                <select name="month">
                    {% for value in months %}
                        <option value="{{ value }}" {% if value == month %}selected{% endif %}>{{ value }}月</option>
                    {% endfor %}
                </select>
            </div>
            <div class="layui-input-inline">
                <button class="layui-btn" lay-submit="true">查询</button>
            </div>
        </div>
    </form>

    <div class="kg-sale-list layui-row layui-col-space15">
        {% for item in items %}
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">{{ item.title }}</div>
                    <div class="layui-card-body">{{ show_sales(item.sales) }}</div>
                </div>
            </div>
        {% endfor %}
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        .kg-sale-list {
            padding: 10px;
            background: #f2f2f2;
        }
    </style>

{% endblock %}