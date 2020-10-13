{% extends 'templates/main.volt' %}

{% block content %}

    {% set year = request.get('year','int',date('Y')) %}
    {% set month = request.get('month','int',date('m')) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>售后退款统计</cite></a>
            </span>
        </div>
    </div>

    <form class="layui-form kg-search-form" method="GET" action="{{ url({'for':'admin.stat.refunds'}) }}">
        <div class="layui-form-item">
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

    <div class="kg-chart" id="chart"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.bootcdn.net/ajax/libs/echarts/4.8.0/echarts.min.js', false) }}

{% endblock %}

{% block inline_js %}

    <script>

        var myChart = echarts.init(document.getElementById('chart'));

        var option = {
            legend: {},
            tooltip: {},
            dataset: {
                source: {{ data|json_encode }}
            },
            xAxis: {type: 'category'},
            yAxis: {},
            series: [
                {type: 'line'},
                {type: 'line'}
            ]
        };

        myChart.setOption(option);

    </script>

{% endblock %}